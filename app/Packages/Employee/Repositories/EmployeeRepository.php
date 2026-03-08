<?php

namespace App\Packages\Employee\Repositories;

use App\Base\Repository\BaseRepository;
use App\Packages\Employee\Models\Employee;
use DB;

class EmployeeRepository extends BaseRepository {

    public function __construct() {
        $this->setModel(Employee::class);
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
}
