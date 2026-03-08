<?php

namespace App\Packages\Employee\Models;

use App\Packages\Company\Models\Company;
use App\Packages\EmployeeFunction\Models\EmployeeFunction;
use App\Packages\Person\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model {
    protected $table = 'social.employee';

    protected $fillable = [
        'person_id',
        'company_id',
        'employee_function_id',
        'start_at',
        'ends_at',
    ];

    public function person(): BelongsTo {
        return $this->belongsTo(Person::class);
    }

    public function company(): BelongsTo {
        return $this->belongsTo(Company::class);
    }

    public function employeeFunction(): BelongsTo {
        return $this->belongsTo(EmployeeFunction::class);
    }

    protected function casts(): array {
        return [
            'start_at' => 'date',
            'ends_at' => 'date',
        ];
    }
}
