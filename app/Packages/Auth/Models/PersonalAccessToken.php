<?php

namespace App\Packages\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model {
    public $timestamps = false;

    protected $table = 'auth.personal_access_tokens';

    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'token',
        'created_at',
        'is_revoked',
    ];

    protected function casts(): array {
        return [
            'created_at' => 'timestamp',
            'is_revoked' => 'boolean',
        ];
    }
}
