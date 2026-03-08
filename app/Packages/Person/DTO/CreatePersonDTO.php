<?php

namespace App\Packages\Person\DTO;

use Spatie\LaravelData\Data;

class CreatePersonDTO extends Data {

    public function __construct(
        public string $name,
        public ?string $cpf = null,
        public ?string $email = null,
        public ?string $phone = null
    ) {
    }
}
