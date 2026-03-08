<?php

namespace App\Packages\Person\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model {
    protected $table = 'social.person';

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'email',
    ];
}
