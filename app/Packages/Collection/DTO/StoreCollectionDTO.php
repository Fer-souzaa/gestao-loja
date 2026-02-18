<?php

namespace App\Packages\Collection\DTO;

use Spatie\LaravelData\Data;

class StoreCollectionDTO extends Data {

    public function __construct(
        public string $name,
        public bool $active = true
    ) {
    }
}
