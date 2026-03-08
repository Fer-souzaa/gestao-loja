<?php

use App\Packages\Employee\Models\Employee;
use App\Packages\Person\Models\Person;
use App\Packages\EmployeeFunction\Models\EmployeeFunction;
use App\Packages\Company\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Criar uma empresa e a função de vendedor para os testes
    DB::statement("CREATE SCHEMA IF NOT EXISTS social;");
    DB::statement("CREATE SCHEMA IF NOT EXISTS public;");

    Company::create([
        'id' => 1,
        'name' => 'Empresa Teste',
        'cnpj' => '12345678000199',
    ]);

    EmployeeFunction::create([
        'name' => 'Vendedor',
        'slug' => 'vendedor',
    ]);
});

it('deve cadastrar um vendedor com sucesso', function () {
    $payload = [
        'name' => 'João Silva',
        'phone' => '11999999999',
        'email' => 'joao@example.com',
        'is_active' => true,
    ];

    $response = $this->postJson(route('v1.employees.store-seller'), $payload);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Vendedor cadastrado com sucesso!')
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'phone',
                'email',
                'is_active',
            ]
        ]);

    $this->assertDatabaseHas('social.person', [
        'name' => 'João Silva',
        'phone' => '11999999999',
        'email' => 'joao@example.com',
    ]);

    $this->assertDatabaseHas('social.employee', [
        'person_id' => Person::firstWhere('name', 'João Silva')->id,
        'ends_at' => null,
    ]);
});

it('deve cadastrar um vendedor inativo', function () {
    $payload = [
        'name' => 'Maria Souza',
        'phone' => '11888888888',
        'is_active' => false,
    ];

    $response = $this->postJson(route('v1.employees.store-seller'), $payload);

    $response->assertStatus(200)
        ->assertJsonPath('data.is_active', false);

    $this->assertDatabaseHas('social.employee', [
        'person_id' => Person::firstWhere('name', 'Maria Souza')->id,
    ]);

    $employee = Employee::whereHas('person', fn($q) => $q->where('name', 'Maria Souza'))->first();
    expect($employee->ends_at)->not->toBeNull();
});

it('deve falhar ao cadastrar vendedor sem campos obrigatórios', function () {
    $response = $this->postJson(route('v1.employees.store-seller'), []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'phone', 'is_active']);
});
