<?php

namespace App\Packages\Auth\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Auth\Models\User;
use App\Packages\Employee\Repositories\EmployeeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
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
                throw new ModelNotFoundException('Usuário ou senha incorretos!');
            }

            if (!Hash::check($password, $user->password)) {
                throw new ModelNotFoundException('Usuário ou senha incorretos!');
            }

            $employee_data = app(EmployeeRepository::class)->getEmployeeByPersonId($user->person_id);
            if (!$employee_data) {
                throw new ConflictHttpException('Você não está vinculado a uma empresa!');
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
                ],
                ...json_decode($employee_data, true)
            ];
        });
    }
}
