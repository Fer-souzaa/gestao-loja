<?php

use App\Packages\Auth\Services\UserDataInCacheService;
use App\Packages\Customer\Models\Customer;
use App\Packages\Person\Models\Person;
use App\Packages\Company\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pode visualizar detalhes de um cliente da mesma empresa', function () {
    $company = Company::factory()->create();
    $person = Person::factory()->create([
        'name' => 'Maria Silva',
        'cpf' => '12345678900',
        'phone' => '11999991234',
        'registration_date' => '1990-05-14'
    ]);

    $customer = Customer::factory()->create([
        'person_id' => $person->id,
        'company_id' => $company->id,
        'address' => 'Rua das Flores, 123 - Centro, São Paulo - SP',
        'billing_date' => '2026-03-08'
    ]);

    $this->mock(UserDataInCacheService::class, function ($mock) use ($company) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
            ]
        ]);
    });

    $response = $this->getJson(route('v1.customers.show', ['id' => $customer->id]), [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Dados do cliente recuperados com sucesso!',
            'data' => [
                'id' => $customer->id,
                'name' => 'Maria Silva',
                'cpf' => '12345678900',
                'phone' => '11999991234',
                'birth_date' => '1990-05-14',
                'address' => 'Rua das Flores, 123 - Centro, São Paulo - SP',
                'total_purchases' => 0,
                'total_spent' => 0,
                'pending_value' => 0,
                'purchase_history' => []
            ]
        ]);
});

test('nao pode visualizar detalhes de um cliente de outra empresa', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $person = Person::factory()->create();
    $customer = Customer::factory()->create([
        'person_id' => $person->id,
        'company_id' => $company2->id,
    ]);

    $this->mock(UserDataInCacheService::class, function ($mock) use ($company1) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company1->id,
            ]
        ]);
    });

    $response = $this->getJson(route('v1.customers.show', ['id' => $customer->id]), [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Cliente não encontrado ou não pertence a sua empresa.'
        ]);
});

test('retorna 404 para cliente inexistente', function () {
    $company = Company::factory()->create();

    $this->mock(UserDataInCacheService::class, function ($mock) use ($company) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company->id,
            ]
        ]);
    });

    $response = $this->getJson(route('v1.customers.show', ['id' => 9999]), [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(404);
});
