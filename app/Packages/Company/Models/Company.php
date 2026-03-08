<?php

namespace App\Packages\Company\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }
    protected $table = 'social.company';

    protected $fillable = [
        'name',
        'cnpj',
        'email',
        'phone',
    ];
}
