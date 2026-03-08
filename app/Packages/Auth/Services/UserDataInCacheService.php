<?php

namespace App\Packages\Auth\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Auth\Models\User;
use App\Packages\Employee\Repositories\EmployeeRepository;

class UserDataInCacheService {
    use CacheTrait;

    /**
     * @param $token
     * @param $user_data
     * @return mixed
     */
    public function execute($token, $user_data = null): mixed {
        $token = app(TokenInCacheService::class)->execute($token);
        $user = app(UserInCacheService::class)->execute($token->tokenable_id);

        return $this->cache(
            key: 'user_data_' . $token->token,
            callback: function () use ($user_data, $user, $token) {
                return $user_data ?? [
                    'access_token' => $token->token,
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username
                    ],
                    ...json_decode(app(EmployeeRepository::class)->getEmployeeByPersonId($user->person_id), true)
                ];
            }
        );
    }
}
