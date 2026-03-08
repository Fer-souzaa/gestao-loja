<?php

use App\Packages\Auth\Services\UserDataInCacheService;
use App\Packages\Customer\Models\Customer;
use App\Packages\Person\Models\Person;
use App\Packages\Company\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pode atualizar dados de um cliente da própria empresa', function () {
    // Preparação
    $company = Company::factory()->create();
    $person = Person::factory()->create([
        'name' => 'Nome Antigo',
        'cpf' => '12345678901',
        'phone' => '11999999999'
    ]);
    $customer = Customer::factory()->create([
        'person_id' => $person->id,
        'company_id' => $company->id,
        'address' => 'Endereco Antigo'
    ]);

    // Mock do UserDataInCacheService para simular usuário logado da empresa
    $this->mock(UserDataInCacheService::class, function ($mock) use ($company) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
            ]
        ]);
    });

    $newData = [
        'name' => 'Nome Atualizado',
        'cpf' => '12345678901',
        'phone' => '11888888888',
        'registration_date' => now()->format('Y-m-d'),
        'billing_date' => now()->addDays(10)->format('Y-m-d'),
        'address' => 'Endereco Novo'
    ];

    // Execução
    $response = $this->putJson(route('v1.customers.update', $customer->id), $newData, [
        'Authorization' => 'Bearer fake-token'
    ]);

    // Verificação
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Cliente atualizado com sucesso!',
            'data' => [
                'name' => 'Nome Atualizado',
                'address' => 'Endereco Novo',
                'phone' => '11888888888'
            ]
        ]);

    $this->assertDatabaseHas('social.person', [
        'id' => $person->id,
        'name' => 'Nome Atualizado'
    ]);

    $this->assertDatabaseHas('social.customer', [
        'id' => $customer->id,
        'address' => 'Endereco Novo'
    ]);
});

test('não pode atualizar dados de um cliente de outra empresa', function () {
    // Preparação
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $person = Person::factory()->create();
    $customer = Customer::factory()->create([
        'person_id' => $person->id,
        'company_id' => $company2->id,
    ]);

    // Mock para empresa 1
    $this->mock(UserDataInCacheService::class, function ($mock) use ($company1) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company1->id,
            ]
        ]);
    });

    $newData = [
        'name' => 'Tentativa de Hack',
        'cpf' => '00000000000',
        'phone' => '00000000000',
        'registration_date' => now()->format('Y-m-d'),
    ];

    // Execução
    $response = $this->putJson(route('v1.customers.update', $customer->id), $newData, [
        'Authorization' => 'Bearer fake-token'
    ]);

    // Verificação
    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Cliente não encontrado ou não pertence a sua empresa.'
        ]);
});

test('retorna 404 ao tentar atualizar cliente inexistente', function () {
    $company = Company::factory()->create();

    $this->mock(UserDataInCacheService::class, function ($mock) use ($company) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company->id,
            ]
        ]);
    });

    $newData = [
        'name' => 'Inexistente',
        'cpf' => '00000000000',
        'phone' => '00000000000',
        'registration_date' => now()->format('Y-m-d'),
    ];

    $response = $this->putJson(route('v1.customers.update', 9999), $newData, [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(404);
});
