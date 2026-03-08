<?php

namespace App\Packages\Employee\Services;

use App\Packages\Auth\Services\UserDataInCacheService;
use App\Packages\Employee\DTOs\EmployeeListDTO;
use App\Packages\Employee\DTOs\SellerDTO;
use App\Packages\Employee\Repositories\EmployeeRepository;
use App\Packages\EmployeeFunction\Enum\FunctionSlugEnum;
use App\Packages\Person\Repositories\PersonRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\App\Packages\Employee\Models\_IH_Employee_C;
use Throwable;

class EmployeeService {


    /**
     * @return mixed
     */
    public function listSellers(): mixed {
        $user_data = app(UserDataInCacheService::class)->execute(getToken());
        $company_id = data_get($user_data, 'company.id');
        return app(EmployeeRepository::class)->listSellersByCompany($company_id);
    }

    /**
     * @param SellerDTO $dto
     * @return mixed
     * @throws Throwable
     */
    public function storeSeller(SellerDTO $dto): mixed {
        return DB::transaction(function () use ($dto) {
            $user_data = app(UserDataInCacheService::class)->execute(getToken());
            $person = app(PersonRepository::class)->create([
                'name' => $dto->name,
                'phone' => $dto->phone,
                'email' => $dto->email,
            ]);

            $employee = app(EmployeeRepository::class)->create([
                'person_id' => $person->id,
                'employee_function_id' => FunctionSlugEnum::getId(FunctionSlugEnum::VENDEDOR),
                'company_id' => data_get($user_data, 'company.id'),
                'start_at' => now(),
                'ends_at' => null,
            ]);

            return [
                'id' => $employee->id,
                'name' => $person->name,
                'phone' => $person->phone,
                'email' => $person->email,
                'is_active' => is_null($employee->ends_at),
            ];
        });
    }
}
