<?php

namespace App\Packages\Auth\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Packages\Person\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model {


    protected $fillable = [
        'username',
        'password',
        'active',
        'person_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Person::class);
    }
}
