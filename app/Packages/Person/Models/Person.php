<?php

namespace App\Packages\Person\Models;

use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model {
    /** @use HasFactory<PersonFactory> */
    use HasFactory;

    protected static function newFactory(): PersonFactory
    {
        return PersonFactory::new();
    }

    protected $table = 'social.person';

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'email',
        'registration_date'
    ];
}
