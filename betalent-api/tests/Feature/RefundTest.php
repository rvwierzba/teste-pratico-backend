<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Gateway;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

class RefundTest extends TestCase
{
    use RefreshDatabase;

    public function test_refund_transaction()
    {
        // Arrange
        $user = User::create([
            'email' => 'finance@test.com',
            'password' => Hash::make('123456'),
            'role' => 'FINANCE'
        ]);
        $token = $user->createToken('api-token')->plainTextToken;

        $gateway = Gateway::create(['name' => 'gateway1', 'is_active' => true, 'priority' => 1]);
        $client = \App\Models\Client::create(['name' => 'Cliente Teste', 'email' => 'cliente@email.com']);

        $transaction = Transaction::create([
            'client_id' => $client->id,
            'gateway_id' => $gateway->id,
            'external_id' => 'abc123',
            'status' => 'SUCCESS',
            'amount' => 3000,
            'card_last_numbers' => '6063'
        ]);

        // Act
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/transactions/{$transaction->id}/refund");

        // Assert
        $response->assertStatus(200)
                ->assertJsonStructure(['status']);
    }
}
