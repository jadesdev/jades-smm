<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Transaction;
use App\Services\CryptomusService;
use App\Services\DepositService;
use App\Services\FlutterwaveService;
use App\Services\Korapay;
use App\Services\PayPalService;
use App\Services\PaystackService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Log;

class PaymentController extends Controller
{
    use ApiResponse;

    public function initPaystack($data)
    {
        try {
            $paystack = app(PaystackService::class);
            $res = $paystack->createPayment($data['amount'], $data);
            if (isset($res['data']['authorization_url'])) {

                return redirect($res['data']['authorization_url']);
            }

            throw new Exception('Unable to initialize payment');
        } catch (Exception) {
            throw new Exception('Unable to initialize payment');
        }
    }

    public function initKorapay($data)
    {
        try {
            $korapay = app(Korapay::class);
            $res = $korapay->createPayment($data['amount'], $data);
            if (isset($res['data']['checkout_url'])) {
                return redirect($res['data']['checkout_url']);
            }

            throw new Exception('Unable to initialize payment');
        } catch (Exception) {
            throw new Exception('Unable to initialize payment');
        }
    }

    public function initFlutter($data)
    {
        try {
            $amount = $data['amount'];
            if ($data['currency'] != 'NGN') {
                $amount = $data['ngn_amount'];
            }

            $flutterwave = app(FlutterwaveService::class);
            $res = $flutterwave->createPayment($amount, $data);
            if (isset($res['data']['link'])) {
                return redirect($res['data']['link']);
            }

            throw new Exception('Unable to initialize payment');
        } catch (Exception) {
            throw new Exception('Unable to initialize payment');
        }
    }

    public function initPaypal($data)
    {
        try {
            $paypal = app(PayPalService::class);
            $res = $paypal->createPayment($data['amount'], $data);
            if (isset($res['status']) && $res['status'] === 'ERROR') {
                Log::error('PayPal init failed', $res);

                return $this->errorResponse($res['message'] ?? 'Unable to initialize payment');
            }

            foreach ($res['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect($link['href']);
                }
            }

            throw new Exception('Unable to initialize payment');
        } catch (Exception) {
            throw new Exception('Unable to initialize payment');
        }
    }

    public function initCryptomus($data)
    {
        try {
            $cryptomus = app(CryptomusService::class);
            $res = $cryptomus->createPayment($data['amount'], $data);
            if (isset($res['result']['url'])) {
                return redirect($res['result']['url']);
            }

            throw new Exception('Unable to initialize payment');
        } catch (Exception) {
            throw new Exception('Unable to initialize payment');
        }
    }

    public function paystackSuccess(Request $request)
    {
        try {
            $paystack = app(PaystackService::class);
            $paymentData = $paystack->getTransactionStatus($request->reference);
            if (! empty($paymentData['data']) && $paymentData['data']['status'] == 'success') {
                $metadata = $paymentData['data']['metadata'];
                $transaction = Transaction::findOrFail($metadata['trx_id']);
                $depositService = app(DepositService::class);
                $depositService->completeDeposit($transaction, $paymentData);

                return $this->callbackResponse('success', 'Payment was successful', route('user.transactions'));
            }

            return $this->callbackResponse('error', 'Payment was not successful', route('user.wallet'));
        } catch (Exception $exception) {
            Log::error('Paystack callback error: '.$exception->getMessage());

            return redirect()->route('user.wallet')->with('error', 'Something went wrong with your payment');
        }
    }

    public function korapaySuccess(Request $request)
    {
        try {
            $korapay = app(Korapay::class);
            $paymentData = $korapay->getTransactionStatus($request->reference);

            if (! empty($paymentData['data']) && $paymentData['data']['status'] == 'success') {
                $metadata = $paymentData['data']['metadata'];
                $transaction = Transaction::findOrFail($metadata['trx_id']);
                $depositService = app(DepositService::class);
                $depositService->completeDeposit($transaction, $paymentData);

                return $this->callbackResponse('success', 'Payment was successful', route('user.transactions'));
            }

            return $this->callbackResponse('error', 'Payment was not successful', route('user.wallet'));
        } catch (Exception $exception) {
            Log::error('Korapay callback error: '.$exception->getMessage());

            return redirect()->route('user.wallet')->with('error', 'Something went wrong with your payment');
        }
    }

    public function flutterSuccess(Request $request)
    {
        if ($request->status == 'cancelled') {
            return $this->callbackResponse('error', 'Payment Was Cancelled', route('user.wallet'));
        }

        $transactionID = $request->transaction_id;
        $flutterwave = app(FlutterwaveService::class);
        $paymentData = $flutterwave->getTransactionStatus($transactionID);
        if ($paymentData['status'] == 'success' && $paymentData['data']['status'] == 'successful') {
            $metadata = $paymentData['data']['meta'];
            $transaction = Transaction::findOrFail($metadata['trx_id']);
            $depositService = app(DepositService::class);
            $depositService->completeDeposit($transaction, $paymentData);

            return $this->callbackResponse('success', 'Payment was successful', route('user.transactions'));
        }

        $transaction = Transaction::findOrFail($paymentData['data']['meta']['trx_id']);
        $depositService = app(DepositService::class);
        $depositService->failDeposit($transaction);

        return $this->callbackResponse('error', 'Payment was not successful', route('user.wallet'));
    }

    public function paypalSuccess(Request $request)
    {
        $orderId = $request->token;
        if (empty($orderId)) {
            return $this->callbackResponse('error', 'Invalid Payment', route('user.wallet'));
        }

        $paypal = app(PaypalService::class);
        $paymentData = $paypal->getOrderDetails($orderId);
        if ($paymentData['status'] == 'APPROVED') {
            $code = $paymentData['purchase_units'][0]['custom_id'] ?? null;
            $transaction = Transaction::where('code', $code)->firstOrFail();
            $depositService = app(DepositService::class);
            $depositService->completeDeposit($transaction, $paymentData);

            return $this->callbackResponse('success', 'Payment was successful', route('user.transactions'));
        }

        $transaction = Transaction::where('code', $paymentData['purchase_units'][0]['custom_id'])->firstOrFail();
        $depositService = app(DepositService::class);
        $depositService->failDeposit($transaction);

        return $this->callbackResponse('error', 'Payment was not successful', route('user.wallet'));
    }

    public function korapayWebhook(Request $request)
    {
        $input = $request->all();
        $this->logApiResponse($input, 'korapay');
        $event = $input['event'];
        try {
            $korapay = app(Korapay::class);
            // validate signature
            // $valid = $korapay->validateWebhookHash($input);
            // if (!$valid) {
            //     return $this->callbackResponse('error', 'Invalid signature');
            // }
            $data = $input['data'] ?? [];
            $reference = $data['reference'] ?? null;

            $paymentData = $korapay->getTransactionStatus($reference);

            if (empty($paymentData['data'])) {
                return $this->callbackResponse('error', 'Transaction not found or invalid');
            }

            $transactionInfo = $paymentData['data'];
            $status = $transactionInfo['status'] ?? 'failed';
            if ($status !== 'success') {
                return $this->callbackResponse('error', 'Payment not successful');
            }

            // Detect type of payment
            $isVirtualAccount = isset($data['virtual_bank_account_details']);
            $metadata = $transactionInfo['metadata'] ?? [];

            $depositService = app(DepositService::class);
            if ($isVirtualAccount) {
                // Handle Virtual Account Deposit
                $accountReference = $transactionInfo['virtual_bank_account']['account_reference'] ?? null;

                $details = [
                    'amount' => $transactionInfo['amount'] ?? 0,
                    'code' => $transactionInfo['reference'] ?? null,
                    'fee' => $transactionInfo['fee'] ?? 0,
                    'reference' => $accountReference,
                ];
                // check if account reference exists
                $account = BankAccount::where('reference', $accountReference)->first();
                if (! $account) {
                    return $this->callbackResponse('error', 'Account not found');
                }
                $res = $depositService->completeKorapayWebhook($details, $transactionInfo);
                Log::info('Korapay Webhook: '.$res);
            } else {
                //  Handle Card / Mobile Money Deposit
                if ($event === 'charge.success') {
                    $trxId = $metadata['trx_id'] ?? null;

                    if (! $trxId) {
                        Log::warning('Missing transaction ID in metadata');

                        return $this->callbackResponse('error', 'Missing metadata transaction ID');
                    }

                    $transaction = Transaction::findOrFail($trxId);
                    $depositService->completeDeposit($transaction, $paymentData);
                }
            }

            return $this->callbackResponse('success', 'Payment processed successfully');
        } catch (Exception $exception) {
            Log::error('Korapay callback error: '.$exception->getMessage());

            return $this->callbackResponse('error', 'Something went wrong with your payment');
        }
    }

    public function callbackResponse($type, $message, $url = null)
    {
        if (request()->wantsJson()) {
            if ($type == 'success') {
                return $this->successResponse($message);
            }

            return $this->errorResponse($message);
        }

        if ($type == 'success') {
            return redirect($url)->withSuccess($message);
        }

        return redirect($url)->withError($message);
    }

    private function logApiResponse($response, $filename = 'korapay')
    {
        $logDir = public_path('logs');
        $logFile = $logDir."/{$filename}_webhook.txt";

        if (! file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $timestamp = '['.now()->format('Y-m-d H:i:s').'] ';
        $logMessage = $timestamp.json_encode($response, JSON_PRETTY_PRINT).PHP_EOL;

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
