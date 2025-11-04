<?php

// app/Services/PaymentService.php
namespace App\Services;

use App\Models\Gateway;
use App\Services\Gateways\GatewayOneService;
use App\Services\Gateways\GatewayTwoService;

class PaymentService
{
    public function processPayment(array $data): array
    {
        $gateways = Gateway::where('is_active', true)->orderBy('priority')->get();

        foreach ($gateways as $gateway) {
            $service = $this->getGatewayService($gateway->name);
            $result = $service->charge($data);

            if ($result['status'] === 'SUCCESS') {
                return [
                    'status' => 'SUCCESS',
                    'gateway_id' => $gateway->id,
                    'external_id' => $result['external_id']
                ];
            }
        }

        return ['status' => 'FAILED'];
    }

    public function refund($transaction): array
    {
        $gateway = Gateway::find($transaction->gateway_id);
        $service = $this->getGatewayService($gateway->name);

        return $service->refund($transaction->external_id);
    }

    private function getGatewayService(string $name)
    {
        return match ($name) {
            'gateway1' => new GatewayOneService(),
            'gateway2' => new GatewayTwoService(),
            default => throw new \Exception('Gateway não suportado')
        };
    }
}
