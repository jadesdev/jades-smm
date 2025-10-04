<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Korapay
{
    protected $publicKey;

    protected $secretKey;

    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('payment.korapay.public');
        $this->secretKey = config('payment.korapay.secret');
        $this->baseUrl = 'https://api.korapay.com/merchant/api/v1/';
    }

    private function request($method, $url, $data = [])
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->$method("{$this->baseUrl}/{$url}", $data);
    }

    // Generate a payment link
    public function createPayment($amount, $details, $currency = 'NGN')
    {
        $amount = round($amount);
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'reference' => $details['reference'],
            'redirect_url' => route('korapay.success'),
            'metadata' => [
                'trx_id' => $details['trx_id'],
                'amount' => $details['amount'],
                'reference' => $details['reference'],
                'currency' => $details['currency'],
            ],
            'customer' => [
                'email' => $details['email'],
                'name' => $details['name'],
            ],
            'merchant_bears_cost' => true,
            'notification_url' => "https://webhook.site/200263f0-e6d6-4eb3-b5c6-36b23d2c2ae8",
        ];
        $response = $this->request('post', 'charges/initialize', $data);
        \Log::info($response);
        return $response->json();
    }

    // Check the status of a payment
    public function getTransactionStatus($reference)
    {
        $response = $this->request('get', "charges/{$reference}");

        return $response->json();
    }

    /**
     * Validate webhook signature
     */
    public function validateWebhookHash(array $payload): bool
    {
        $receivedHash = request()->header('x-korapay-signature');
        if (! $receivedHash || ($receivedHash !== hash_hmac('sha256', json_encode($payload), (string) $this->secretKey))) {
            // This request isn't from korapay; discard
            return false;
        }

        return true;
    }
}
