<?php

use App\Packages\Auth\Services\UserDataInCacheService;
use App\Packages\Customer\Models\Customer;
use App\Packages\Person\Models\Person;
use App\Packages\Company\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pode remover um cliente da mesma empresa (soft delete)', function () {
    $company = Company::factory()->create();
    $person = Person::factory()->create();
    $customer = Customer::factory()->create([
        'person_id' => $person->id,
        'company_id' => $company->id,
    ]);

    $this->mock(UserDataInCacheService::class, function ($mock) use ($company) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company->id,
            ]
        ]);
    });

    $response = $this->deleteJson(route('v1.customers.destroy', ['id' => $customer->id]), [], [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Cliente removido com sucesso!',
        ]);

    // Verificar se foi removido do banco (soft delete)
    $this->assertSoftDeleted('social.customer', [
        'id' => $customer->id,
    ]);
});

test('nao pode remover um cliente de outra empresa', function () {
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

    $response = $this->deleteJson(route('v1.customers.destroy', ['id' => $customer->id]), [], [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Cliente não encontrado ou não pertence a sua empresa.'
        ]);

    // Verificar se NÃO foi removido do banco
    $this->assertDatabaseHas('social.customer', [
        'id' => $customer->id,
        'deleted_at' => null,
    ]);
});

test('clientes removidos nao aparecem na listagem', function () {
    $company = Company::factory()->create();
    $person = Person::factory()->create();
    $customer = Customer::factory()->create([
        'person_id' => $person->id,
        'company_id' => $company->id,
        'deleted_at' => now(),
    ]);

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

test('clientes removidos nao podem ser visualizados no detalhamento', function () {
    $company = Company::factory()->create();
    $person = Person::factory()->create();
    $customer = Customer::factory()->create([
        'person_id' => $person->id,
        'company_id' => $company->id,
        'deleted_at' => now(),
    ]);

    $this->mock(UserDataInCacheService::class, function ($mock) use ($company) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => [
                'id' => $company->id,
            ]
        ]);
    });

    $response = $this->getJson(route('v1.customers.show', ['id' => $customer->id]), [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(404);
});
