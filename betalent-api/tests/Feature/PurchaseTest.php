<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Gateway;
use Illuminate\Support\Facades\Hash;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_success()
    {
        // Arrange
        $user = User::create([
            'email' => 'user@test.com',
            'password' => Hash::make('123456'),
            'role' => 'USER'
        ]);
        $token = $user->createToken('api-token')->plainTextToken;

        $produto1 = Product::create(['name' => 'Produto 1', 'amount' => 1000]);
        $produto2 = Product::create(['name' => 'Produto 2', 'amount' => 2000]);
        Gateway::create(['name' => 'gateway1', 'is_active' => true, 'priority' => 1]);
        Gateway::create(['name' => 'gateway2', 'is_active' => true, 'priority' => 2]);

        // Act
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/purchase', [
                'client_name' => 'João Silva',
                'client_email' => 'joao@email.com',
                'products' => [
                    ['id' => $produto1->id, 'quantity' => 1],
                    ['id' => $produto2->id, 'quantity' => 1]
                ],
                'card_number' => '5569000000006063',
                'cvv' => '010'
            ]);

        // Assert
        $response->assertStatus(201)
                ->assertJsonStructure(['id', 'status', 'gateway_id', 'amount']);
    }
}
