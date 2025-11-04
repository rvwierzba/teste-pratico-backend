<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product()
    {
        // Arrange: cria um usuário admin e autentica
        $user = User::create([
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'role' => 'ADMIN'
        ]);
        $token = $user->createToken('api-token')->plainTextToken;

        // Act: faz a requisição autenticada
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/products', [
                'name' => 'Produto Teste',
                'amount' => 1500
            ]);

        // Assert
        $response->assertStatus(201)
                 ->assertJson(['name' => 'Produto Teste']);
    }
}
