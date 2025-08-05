<?php

namespace App\Services;

use App\Http\Controllers\PaymentController;
use App\Models\Transaction;
use App\Models\User;
use Exception;
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
    public function initiateDeposit(float $amount, string $gateway, User $user)
    {
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
            Log::error('Deposit initiation failed: '.$exception->getMessage());
            throw new Exception('Unable to process deposit. Please try again.');
        }
    }

    /**
     * Calculate deposit fee based on gateway
     */
    protected function calculateDepositFee(float $amount, string $gateway): float
    {
        $rate = (float) sys_setting('card_fee');
        $cap = (float) sys_setting('card_fee_cap');

        $fee = $amount * ($rate / 100);

        return ($fee > $cap) ? $cap : $fee;
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
            creditUser($user, $transaction->amount);

            // Update transaction status
            if ($transaction->status != 'successful') {
                $transaction->update([
                    'status' => 'successful',
                    'new_balance' => $user->balance,
                    'response' => $paymentData,
                ]);
            }

            // TODO: Send notification to user
            sendNotification('DEPOSIT_SUCCESSFUL', $user, [
                'name' => $user->name,
                'email' => $user->email,
                'deposit_amount' => format_price($transaction->amount),
                'payment_gateway' => $transaction->meta['gateway'],
                'deposit_details' => $transaction->message,
                'transaction_id' => $transaction->code,
                'transaction_code' => $transaction->code,
                'new_balance' => format_price($transaction->new_balance),
            ], [
                'link' => route('user.dashboard'),
                'link_text' => 'View Dashboard',
            ]);
        } catch (Exception $e) {
            Log::error('Failed to complete deposit: '.$e->getMessage());
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
            Log::error('Failed to fail deposit: '.$e->getMessage());
            throw $e;
        }
    }
}
