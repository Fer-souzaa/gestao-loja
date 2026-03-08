<?php

namespace App\Packages\Company\DTO;

use Spatie\LaravelData\Data;

class CreateCompanyDTO extends Data {

    public function __construct(
        public string $name,
        public ?string $cnpj,
        public string $email,
        public ?string $phone = null,
    ) {
    }

}
