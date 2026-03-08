<?php

namespace App\Packages\Company\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    protected $table = 'social.company';

    protected $fillable = [
        'name',
        'cnpj',
        'email',
        'phone',
    ];
}
