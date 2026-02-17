<?php

namespace App\Packages\Auth\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Auth\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class LoginService {

    use CacheTrait;

    /**
     * @param $username
     * @param $password
     * @return array|mixed
     * @throws Throwable
     */
    public function execute($username, $password): mixed {
        return DB::transaction(function () use ($username, $password) {
            $user = User::firstWhere('username', $username);
            if (!$user) {
                throw new ModelNotFoundException('Usuário não encontrado!');
            }

            if (!Hash::check($password, $user->password)) {
                throw new ModelNotFoundException('Senha incorreta!');
            }

            $access_token = app(GeneratePersonalAccessTokenService::class)->execute($user);

            $this->clearUserCache($user->id);
            app(TokenInCacheService::class)->execute($access_token->token, $access_token);
            app(UserInCacheService::class)->execute($user->id, $user);

            return [
                'access_token' => $access_token->token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username
                ]
            ];
        });
    }
}
