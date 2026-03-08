<?php

use App\Packages\Auth\Services\UserDataInCacheService;
use App\Packages\Customer\Models\Customer;
use App\Packages\Person\Models\Person;
use App\Packages\Company\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pode listar clientes da empresa do usuario logado', function () {
    // Preparação
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $person1 = Person::factory()->create(['name' => 'Cliente Empresa 1']);
    $person2 = Person::factory()->create(['name' => 'Cliente Empresa 2']);

    Customer::factory()->create([
        'person_id' => $person1->id,
        'company_id' => $company1->id,
    ]);

    Customer::factory()->create([
        'person_id' => $person2->id,
        'company_id' => $company2->id,
    ]);

    // Mock do UserDataInCacheService para simular usuário da empresa 1
    $this->mock(UserDataInCacheService::class, function ($mock) use ($company1) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company1->id,
                'name' => $company1->name,
            ]
        ]);
    });

    // Execução
    $response = $this->getJson(route('v1.customers.index'), [
        'Authorization' => 'Bearer fake-token'
    ]);

    // Verificação
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Clientes listados com sucesso!',
        ])
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Cliente Empresa 1')
        ->assertJsonFragment(['purchases' => 0]);
});

test('retorna lista vazia se a empresa nao tiver clientes', function () {
    $company = Company::factory()->create();

    $this->mock(UserDataInCacheService::class, function ($mock) use ($company) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company->id,
            ]
        ]);
    });

    $response = $this->getJson(route('v1.customers.index'), [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');
});
