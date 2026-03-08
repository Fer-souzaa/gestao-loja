<?php

namespace Database\Factories;

use App\Packages\Person\Models\Person;
use App\Packages\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Packages\Customer\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'company_id' => Company::factory(),
            'billing_date' => fake()->optional()->date(),
            'address' => fake()->optional()->address(),
        ];
    }
}
