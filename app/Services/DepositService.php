<?php

namespace App\Services;

use App\Http\Controllers\PaymentController;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DepositService
{
    protected PaymentController $paymentController;

    public function __construct()
    {
        $this->paymentController = app(PaymentController::class);
    }

    /**
     * Initiate a new deposit
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function initiateDeposit(float $amount, string $gateway, ?User $user = null)
    {
        $user = $user ?? Auth::user();

        $fee = $this->calculateDepositFee($amount, $gateway);
        $total = $amount + $fee;

        try {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'code' => getTrx(),
                'service' => 'deposit',
                'message' => 'Wallet deposit funding',
                'gateway' => $gateway,
                'amount' => $amount,
                'image' => 'deposit.png',
                'charge' => $fee,
                'old_balance' => $user->balance,
                'meta' => [
                    'gateway' => $gateway,
                    'amount' => $amount,
                    'fee' => $fee,
                ],
                'status' => 'initiated',
            ]);

            $paymentData = [
                'email' => $user->email,
                'name' => $user->name,
                'amount' => $total,
                'usd_amount' => $total,
                'trx_id' => $transaction->id,
                'currency' => 'NGN',
                'reference' => $transaction->code,
                'description' => $transaction->message,
            ];

            return $this->initiateGatewayPayment($gateway, $paymentData);
        } catch (Exception $exception) {
            Log::error('Deposit initiation failed: ' . $exception->getMessage());
            throw new Exception('Unable to process deposit. Please try again.');
        }
    }

    /**
     * Calculate deposit fee based on gateway
     */
    protected function calculateDepositFee(float $amount, string $gateway): float
    {
        // TODO: Implement dynamic fee calculation based on gateway
        // For now, return a flat fee of 10 NGN
        return 10.00;
    }

    /**
     * Initialize payment with the selected gateway
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function initiateGatewayPayment(string $gateway, array $paymentData)
    {
        return match ($gateway) {
            'paystack' => $this->paymentController->initPaystack($paymentData),
            'flutterwave' => $this->paymentController->initFlutter($paymentData),
            'paypal' => $this->paymentController->initPaypal($paymentData),
            'crypto' => $this->paymentController->initCryptomus($paymentData),
            default => throw new Exception('Invalid payment gateway selected'),
        };
    }

    /**
     * Complete a successful deposit
     */
    public function completeDeposit(Transaction $transaction, $paymentData = null): void
    {
        try {
            $user = $transaction->user;

            // Update user balance
            $user->increment('balance', $transaction->amount);

            // Update transaction status
            if ($transaction->status != 'successful') {
                $transaction->update([
                    'status' => 'successful',
                    'new_balance' => $user->balance,
                    'response' => $paymentData,
                ]);
            }

            // TODO: Send notification to user

        } catch (Exception $e) {
            Log::error('Failed to complete deposit: ' . $e->getMessage());
            throw $e;
        }
    }

    public function failDeposit(Transaction $transaction): void
    {
        try {
            $user = $transaction->user;

            // Update transaction status
            if ($transaction->status != 'failed') {
                $transaction->update([
                    'status' => 'failed',
                    'new_balance' => $user->balance,
                ]);
            }

            // TODO: Send notification to user

        } catch (Exception $e) {
            Log::error('Failed to fail deposit: ' . $e->getMessage());
            throw $e;
        }
    }
}
