<?php

namespace App\Packages\Color\DTO;

use Spatie\LaravelData\Data;

class UpdateColorDTO extends Data {

    public function __construct(
        public string $name,
        public bool $active
    ) {
    }
}
