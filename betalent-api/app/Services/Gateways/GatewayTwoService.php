<?php

// app/Services/Gateways/GatewayTwoService.php
namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class GatewayTwoService implements GatewayInterface
{
    private string $baseUrl = 'http://localhost:3002';
    private array $headers = [
        'Gateway-Auth-Token' => 'tk_f2198cc671b5289fa856',
        'Gateway-Auth-Secret' => '3d15e8ed6131446ea7e3456728b1211f'
    ];

    public function charge(array $data): array
    {
        $response = Http::withHeaders($this->headers)
            ->post("{$this->baseUrl}/transacoes", [
                'valor' => $data['amount'],
                'nome' => $data['name'],
                'email' => $data['email'],
                'numeroCartao' => $data['cardNumber'],
                'cvv' => $data['cvv']
            ]);

        return [
            'status' => $response->successful() ? 'SUCCESS' : 'FAILED',
            'external_id' => $response->json('id') ?? null
        ];
    }

    public function refund(string $transactionId): array
    {
        $response = Http::withHeaders($this->headers)
            ->post("{$this->baseUrl}/transacoes/reembolso", [
                'id' => $transactionId
            ]);

        return [
            'status' => $response->successful() ? 'REFUNDED' : 'FAILED'
        ];
    }
}
