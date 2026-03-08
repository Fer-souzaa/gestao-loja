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
     * @param int $id
     * @param int $companyId
     * @return array|null
     */
    public function findByIdAndCompany(int $id, int $companyId): ?array
    {
        $data = \DB::table('social.customer as c')
            ->join('social.person as p', 'c.person_id', '=', 'p.id')
            ->where('c.id', $id)
            ->where('c.company_id', $companyId)
            ->whereNull('c.deleted_at')
            ->select([
                'c.id',
                'c.address',
                'c.billing_date',
                'p.name',
                'p.cpf',
                'p.phone',
                'p.registration_date'
            ])
            ->first();

        return $data ? (array) $data : null;
    }

    /**
     * @param int $companyId
     * @return array
     */
    public function listByCompany(int $companyId): array
    {
        return \DB::table('social.customer as c')
            ->join('social.person as p', 'c.person_id', '=', 'p.id')
            ->where('c.company_id', $companyId)
            ->whereNull('c.deleted_at')
            ->select([
                'c.id',
                'p.name',
                'p.cpf',
                'p.phone'
            ])
            ->orderBy('p.name')
            ->get()
            ->toArray();
    }

    /**
     * @param int $id
     * @param int $companyId
     * @param array $customerData
     * @param array $personData
     * @return bool
     */
    public function updateCustomerAndPerson(int $id, int $companyId, array $customerData, array $personData): bool
    {
        $customer = Customer::query()
            ->where('id', $id)
            ->where('company_id', $companyId)
            ->first();

        if (!$customer) {
            return false;
        }

        $customer->update($customerData);
        $customer->person->update($personData);

        return true;
    }

    /**
     * @param int $id
     * @param int $companyId
     * @return bool
     */
    public function destroy(int $id, int $companyId): bool
    {
        return Customer::query()
            ->where('id', $id)
            ->where('company_id', $companyId)
            ->delete();
    }
}
