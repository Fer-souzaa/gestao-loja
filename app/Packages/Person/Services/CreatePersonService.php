<?php

namespace App\Packages\Person\Services;

use App\Packages\Person\DTO\CreatePersonDTO;
use App\Packages\Person\Models\Person;

class CreatePersonService {

    /**
     * @param CreatePersonDTO $data
     * @return Person
     */
    public function execute(CreatePersonDTO $data): Person {
        return Person::create($data->toArray());
    }
}
