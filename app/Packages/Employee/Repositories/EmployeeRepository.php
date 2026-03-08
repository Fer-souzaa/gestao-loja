<?php

namespace App\Packages\Employee\Repositories;

use App\Base\Repository\BaseRepository;
use App\Packages\Employee\Models\Employee;
use App\Packages\EmployeeFunction\Enum\FunctionSlugEnum;
use DB;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Packages\Employee\Models\_IH_Employee_C;

class EmployeeRepository extends BaseRepository {

    public function __construct() {
        $this->setModel(Employee::class);
    }


    /**
     * @param int $company_id
     * @return Collection|_IH_Employee_C|array
     */
    public function listSellersByCompany(int $company_id): Collection|_IH_Employee_C|array {
        return Employee::query()
            ->from('social.employee as e')
            ->join('social.person as p', 'e.person_id', '=', 'p.id')
            ->where('e.company_id', '=', $company_id)
            ->where('e.employee_function_id', '=', FunctionSlugEnum::getId(FunctionSlugEnum::VENDEDOR))
            ->whereNull('e.ends_at')
            ->select([
                'e.id',
                'p.name',
                'p.phone',
                'p.email',
                'e.start_at'
            ])
            ->get();
    }

    /**
     * @param $person_id
     * @return mixed
     */
    public function getEmployeeByPersonId($person_id): mixed {
        return DB::selectOne(
            query: "SELECT
                        json_build_object(
                            'person',
                            json_build_object('id', P.id, 'name', P.name, 'cpf', P.cpf),
                            'company',
                            json_build_object('id', C.id, 'name', C.name, 'function', EF.name, 'employee_id', E.id)
                        ) AS data
                    FROM social.employee E
                    JOIN social.employee_function EF ON E.employee_function_id = EF.id
                    JOIN social.company C ON E.company_id = C.id
                    JOIN social.person P ON E.person_id = P.id
                    WHERE E.ends_at IS NULL
                      AND E.person_id = ?;",
            bindings: [$person_id]
        )?->data;
    }

    /**
     * @param Employee $employee
     * @return bool
     */
    public function terminate(Employee $employee): bool {
        return $employee->update([
            'ends_at' => now()
        ]);
    }
}
