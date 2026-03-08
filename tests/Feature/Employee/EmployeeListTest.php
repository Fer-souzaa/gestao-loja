<?php

namespace Tests\Feature\Employee;

use App\Packages\Auth\Services\UserDataInCacheService;
use App\Packages\Company\Models\Company;
use App\Packages\Employee\Models\Employee;
use App\Packages\EmployeeFunction\Models\EmployeeFunction;
use App\Packages\Person\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Preparar o ambiente
    DB::statement("CREATE SCHEMA IF NOT EXISTS social;");

    $company = Company::create([
        'id' => 1,
        'name' => 'Empresa Teste',
        'cnpj' => '12345678000199',
        'email' => 'teste@empresa.com',
    ]);

    $function = EmployeeFunction::firstOrCreate([
        'name' => 'Vendedor',
        'slug' => 'vendedor',
    ]);

    // Mock do serviço de cache do usuário para simular usuário logado na empresa 1
    $mockUserData = [
        'company' => ['id' => 1]
    ];

    $this->mock(UserDataInCacheService::class, function ($mock) use ($mockUserData) {
        $mock->shouldReceive('execute')->andReturn($mockUserData);
    });
});

it('deve listar os vendedores da empresa do usuário logado', function () {
    // Criar alguns vendedores vinculados à empresa 1
    $person1 = Person::create(['name' => 'Vendedor 1', 'phone' => '11911111111', 'email' => 'v1@test.com']);
    Employee::create([
        'person_id' => $person1->id,
        'company_id' => 1,
        'employee_function_id' => 1, // Vendedor
        'start_at' => '2025-01-01',
    ]);

    $person2 = Person::create(['name' => 'Vendedor 2', 'phone' => '11922222222']);
    Employee::create([
        'person_id' => $person2->id,
        'company_id' => 1,
        'employee_function_id' => 1, // Vendedor
        'start_at' => '2025-01-02',
    ]);

    // Criar um vendedor de outra empresa (não deve aparecer)
    $otherCompany = Company::create(['id' => 2, 'name' => 'Outra Empresa', 'cnpj' => '00000000000100', 'email' => 'outra@empresa.com']);
    $person3 = Person::create(['name' => 'Vendedor 3']);
    Employee::create([
        'person_id' => $person3->id,
        'company_id' => $otherCompany->id,
        'employee_function_id' => 1,
        'start_at' => '2025-01-03',
    ]);

    $response = $this->getJson(route('v1.employees.index'), [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment(['name' => 'Vendedor 1'])
        ->assertJsonFragment(['name' => 'Vendedor 2'])
        ->assertJsonMissing(['name' => 'Vendedor 3']);
});

it('deve retornar listagem vazia se a empresa não tiver vendedores', function () {
    $response = $this->getJson(route('v1.employees.index'), [
        'Authorization' => 'Bearer fake-token'
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data', []);
});
