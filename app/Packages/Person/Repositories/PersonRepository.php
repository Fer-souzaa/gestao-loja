<?php

namespace App\Packages\Person\Repositories;

use App\Base\Repository\BaseRepository;
use App\Packages\Person\Models\Person;

class PersonRepository extends BaseRepository {

    public function __construct() {
        $this->setModel(Person::class);
    }
}
