<?php

use App\Packages\Employee\Models\Employee;
use App\Packages\Person\Models\Person;
use App\Packages\EmployeeFunction\Models\EmployeeFunction;
use App\Packages\Company\Models\Company;
use App\Packages\Auth\Models\User;
use App\Packages\Auth\Services\UserDataInCacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    DB::statement("CREATE SCHEMA IF NOT EXISTS social;");
    DB::statement("CREATE SCHEMA IF NOT EXISTS public;");

    $company = Company::query()->firstOrCreate(
        ['cnpj' => '12345678000199'],
        [
            'id' => 1,
            'name' => 'Empresa Teste',
            'email' => 'teste@empresa.com',
        ]
    );

    $function = EmployeeFunction::query()->where('slug', 'vendedor')->first();

    // Simular usuário logado na empresa 1
    $person = Person::create(['name' => 'Admin', 'phone' => '123', 'email' => 'admin@test.com']);
    $user = User::query()->create(['person_id' => $person->id, 'username' => 'admin', 'password' => '123']);

    // Mock UserDataInCacheService para retornar a empresa 1
    $this->mock(UserDataInCacheService::class, function ($mock) {
        $mock->shouldReceive('execute')->andReturn([
            'company' => ['id' => 1]
        ]);
    });
});

it('deve desvincular um vendedor com sucesso', function () {
    $person = Person::create(['name' => 'Vendedor 1', 'phone' => '11999999999']);
    $employee = Employee::query()->create([
        'person_id' => $person->id,
        'company_id' => 1,
        'employee_function_id' => EmployeeFunction::query()->where('slug', 'vendedor')->first()->id,
        'start_at' => now(),
    ]);

    $response = $this->deleteJson(route('v1.employees.terminate-seller', ['id' => $employee->id]));

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Vendedor desvinculado com sucesso!');

    $this->assertDatabaseHas('social.employee', [
        'id' => $employee->id,
    ]);

    $updatedEmployee = Employee::query()->find($employee->id);
    expect($updatedEmployee->ends_at)->not->toBeNull();
});

it('não deve desvincular um vendedor de outra empresa', function () {
    $otherCompany = Company::query()->create([
        'id' => 2,
        'name' => 'Outra Empresa',
        'cnpj' => '87654321000199',
        'email' => 'outro@teste.com',
    ]);

    $person = Person::create(['name' => 'Vendedor 2', 'phone' => '11888888888']);
    $employee = Employee::query()->create([
        'person_id' => $person->id,
        'company_id' => $otherCompany->id,
        'employee_function_id' => EmployeeFunction::query()->where('slug', 'vendedor')->first()->id,
        'start_at' => now(),
    ]);

    $response = $this->deleteJson(route('v1.employees.terminate-seller', ['id' => $employee->id]));

    $response->assertStatus(403);

    $updatedEmployee = Employee::query()->find($employee->id);
    expect($updatedEmployee->ends_at)->toBeNull();
});

it('deve retornar erro ao tentar desvincular vendedor inexistente', function () {
    $response = $this->deleteJson(route('v1.employees.terminate-seller', ['id' => 999]));

    $response->assertStatus(404);
});
