<?php

namespace App\Packages\Customer\Repositories;

use App\Base\Repository\BaseRepository;
use App\Packages\Customer\Models\Customer;
use App\Packages\Person\Models\Person;

class CustomerRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Customer::class);
    }

    /**
     * @param string $cpf
     * @return Person|null
     */
    public function findPersonByCpf(string $cpf): ?Person
    {
        return Person::query()->where('cpf', $cpf)->first();
    }

    /**
     * @param array $data
     * @return Person
     */
    public function createPerson(array $data): Person
    {
        return Person::create($data);
    }

    /**
     * @param array $data
     * @return Customer
     */
    public function createCustomer(array $data): Customer
    {
        return Customer::create($data);
    }
}
