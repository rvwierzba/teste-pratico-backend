<?php

// app/Services/Gateways/GatewayOneService.php
namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class GatewayOneService implements GatewayInterface
{
    private string $baseUrl = 'http://localhost:3001';
    private string $token = 'FEC9BB078BF338F464F96B48089EB498'; // mock token

    public function charge(array $data): array
    {
        $response = Http::withToken($this->token)
            ->post("{$this->baseUrl}/transactions", [
                'amount' => $data['amount'],
                'name' => $data['name'],
                'email' => $data['email'],
                'cardNumber' => $data['cardNumber'],
                'cvv' => $data['cvv']
            ]);

        return [
            'status' => $response->successful() ? 'SUCCESS' : 'FAILED',
            'external_id' => $response->json('id') ?? null
        ];
    }

    public function refund(string $transactionId): array
    {
        $response = Http::withToken($this->token)
            ->post("{$this->baseUrl}/transactions/{$transactionId}/charge_back");

        return [
            'status' => $response->successful() ? 'REFUNDED' : 'FAILED'
        ];
    }
}
