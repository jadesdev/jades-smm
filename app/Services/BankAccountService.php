<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Str;

class BankAccountService
{
    protected Korapay $korapay;

    public function __construct()
    {
        $this->korapay = app(Korapay::class);
    }

    public function generateAccount(User $user)
    {
        $provider = 'korapay';
        if ($provider == 'korapay') {
            return $this->generateKorapayAccount($user);
        }
    }

    public function generateKorapayAccount(User $user)
    {
        $ref = Str::random(30);
        $payload = [
            'account_name' => $user->name,
            'account_reference' => $ref,
            'permanent' => true,
            'bank_code' => '035',
            'customer' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'kyc' => [
                $user->kyc_type => decrypt($user->kyc_number),
            ],
        ];

        $response = $this->korapay->createVirtualAccount($payload);
        Log::info($response);

        if ($response['status'] != true) {
            throw new \Exception('Unable to create account');
        }

        return $this->storeAccount([
            'number' => $response['data']['account_number'],
            'bank_code' => $response['data']['bank_code'],
            'bank_name' => $response['data']['bank_name'],
            'name' => $response['data']['account_name'],
            'reference' => $ref,
            'type' => 'static',
            'provider' => 'korapay',
            'response' => $response,
        ], $user);
    }

    private function storeAccount(array $data, $user)
    {
        return BankAccount::create([
            'user_id' => $user->id,
            'number' => $data['number'] ?? null,
            'bank_code' => $data['bank_code'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'name' => $data['name'] ?? null,
            'reference' => $data['reference'] ?? null,
            'type' => $data['type'] ?? 'static',
            'provider' => $data['provider'] ?? 'korapay',
            'meta' => $data['response'] ?? null,
        ]);
    }
}
