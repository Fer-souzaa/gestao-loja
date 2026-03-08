<?php

use App\Packages\Customer\Models\Customer;
use App\Packages\Person\Models\Person;
use App\Packages\Company\Models\Company;

it('pode criar um cliente vinculado a uma pessoa e uma empresa', function () {
    $person = Person::factory()->create([
        'registration_date' => '2026-03-08'
    ]);
    $company = Company::factory()->create();

    $customer = Customer::create([
        'person_id' => $person->id,
        'company_id' => $company->id,
        'billing_date' => '2026-04-10',
        'address' => 'Rua de Teste, 123'
    ]);

    expect($customer->person_id)->toBe($person->id)
        ->and($customer->company_id)->toBe($company->id)
        ->and($customer->billing_date->format('Y-m-d'))->toBe('2026-04-10')
        ->and($customer->address)->toBe('Rua de Teste, 123');

    expect($person->registration_date->format('Y-m-d'))->toBe('2026-03-08');
});

it('pode criar um cliente com campos opcionais nulos', function () {
    $person = Person::factory()->create(['registration_date' => null]);
    $company = Company::factory()->create();

    $customer = Customer::create([
        'person_id' => $person->id,
        'company_id' => $company->id,
        'billing_date' => null,
        'address' => null
    ]);

    expect($customer->billing_date)->toBeNull()
        ->and($customer->address)->toBeNull()
        ->and($person->registration_date)->toBeNull();
});

it('tem relacionamentos funcionando', function () {
    $customer = Customer::factory()->create();

    expect($customer->person)->toBeInstanceOf(Person::class)
        ->and($customer->company)->toBeInstanceOf(Company::class);
});
