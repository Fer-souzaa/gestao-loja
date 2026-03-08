<?php

namespace App\Packages\Customer\Services;

use App\Packages\Auth\Services\UserDataInCacheService;
use App\Packages\Customer\DTOs\CustomerStoreDTO;
use App\Packages\Customer\Repositories\CustomerRepository;
use App\Packages\Person\DTO\CreatePersonDTO;
use App\Packages\Person\Models\Person;
use App\Packages\Person\Services\CreatePersonService;
use DB;
use Throwable;

class CustomerService {
    /**
     * @param CustomerStoreDTO $dto
     * @return array
     * @throws Throwable
     */
    public function store(CustomerStoreDTO $dto): array {
        return DB::transaction(function () use ($dto) {
            $person = null;
            $user_data = app(UserDataInCacheService::class)->execute(getToken());

            if ($dto->cpf) {
                $person = app(CustomerRepository::class)->findPersonByCpf($dto->cpf);
            }

            if (!$person) {
                $person = app(CreatePersonService::class)->execute(
                    new CreatePersonDTO(
                        name: $dto->name,
                        cpf: $dto->cpf,
                        phone: $dto->phone,
                        registration_date: $dto->registration_date,
                    )
                );
            }

            $customer = app(CustomerRepository::class)->createCustomer([
                'person_id' => $person->id,
                'company_id' => data_get($user_data, 'company.id'),
                'billing_date' => $dto->billing_date,
                'address' => $dto->address,
            ]);

            return [
                'id' => $customer->id,
                'person_id' => $person->id,
                'name' => $person->name,
                'phone' => $person->phone,
                'cpf' => $person->cpf,
                'billing_date' => $customer->billing_date?->format('Y-m-d'),
                'address' => $customer->address,
            ];
        });
    }

    /**
     * @return array
     */
    public function list(): array {
        $user_data = app(UserDataInCacheService::class)->execute(getToken());
        $companyId = (int) data_get($user_data, 'company.id');

        return app(CustomerRepository::class)->listByCompany($companyId);
    }

    /**
     * @param int $id
     * @return array
     * @throws Throwable
     */
    public function show(int $id): array {
        $user_data = app(UserDataInCacheService::class)->execute(getToken());
        $companyId = (int) data_get($user_data, 'company.id');

        $customer = app(CustomerRepository::class)->findByIdAndCompany($id, $companyId);

        if (!$customer) {
            throw new \Exception('Cliente não encontrado ou não pertence a sua empresa.', 404);
        }

        return $customer;
    }

    /**
     * @param int $id
     * @return bool
     * @throws Throwable
     */
    public function destroy(int $id): bool {
        $user_data = app(UserDataInCacheService::class)->execute(getToken());
        $companyId = (int) data_get($user_data, 'company.id');

        $deleted = app(CustomerRepository::class)->destroy($id, $companyId);

        if (!$deleted) {
            throw new \Exception('Cliente não encontrado ou não pertence a sua empresa.', 404);
        }

        return $deleted;
    }
}
