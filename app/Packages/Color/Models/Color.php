<?php

namespace App\Packages\Color\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ColorFactory;

class Color extends Model
{
    /** @use HasFactory<ColorFactory> */
    use HasFactory;

    protected static function newFactory(): ColorFactory
    {
        return ColorFactory::new();
    }

    /**
     * The table associated with the model.
     */
    protected $table = 'public.color';

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
