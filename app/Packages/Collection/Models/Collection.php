<?php

namespace App\Packages\Collection\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

    /**
     * The table associated with the model.
     */
    protected $table = 'public.collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'active',
    ];

    /**
     * The attributes that should be cast.
     * Laravel 12 recommends using casts() method.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
