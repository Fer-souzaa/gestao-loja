<?php

namespace App\Packages\EmployeeFunction\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFunction extends Model {
    protected $table = 'social.employee_function';

    protected $fillable = [
        'name',
        'slug',
    ];
}
