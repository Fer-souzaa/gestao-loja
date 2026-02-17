<?php

namespace App\Packages\Auth\Services;

use App\Packages\Auth\Models\PersonalAccessToken;
use App\Packages\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class GeneratePersonalAccessTokenService {

    /**
     * @param User $user
     * @return PersonalAccessToken
     */
    public function execute(User $user): PersonalAccessToken {
        return PersonalAccessToken::create([
            'tokenable_type' => 'public.users',
            'tokenable_id' => $user->id,
            'token' => Hash::make(uniqid()),
            'created_at' => now(),
        ]);
    }
}
