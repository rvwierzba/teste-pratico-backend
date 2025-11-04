<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // Importa o trait
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Garante que o banco será limpo a cada teste

    public function test_login_success()
    {
        // Arrange: cria um usuário no banco de teste
        User::create([
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'role' => 'ADMIN'
        ]);

        // Act: faz a requisição de login
        $response = $this->postJson('/api/login', [
            'email' => 'admin@test.com',
            'password' => '123456'
        ]);

        // Assert: verifica se retornou sucesso e o formato esperado
        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'role']);
    }

    public function test_login_fail()
    {
        // Não cria usuário, tenta logar com dados inválidos
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@test.com',
            'password' => 'wrongpass'
        ]);

        $response->assertStatus(401);
    }
}
