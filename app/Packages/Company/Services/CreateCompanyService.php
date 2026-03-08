<?php

namespace App\Packages\Company\Services;

use App\Packages\Company\DTO\CreateCompanyDTO;
use App\Packages\Company\Models\Company;

class CreateCompanyService {

    /**
     * @param CreateCompanyDTO $data
     * @return Company
     */
    public function execute(CreateCompanyDTO $data): Company {
        return Company::create($data->toArray());
    }
}
