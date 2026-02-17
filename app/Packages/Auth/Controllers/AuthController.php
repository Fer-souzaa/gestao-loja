<?php

namespace App\Packages\Auth\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Packages\Auth\Requests\LoginRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController {

    /**
     * @param LoginRequest $request
     * @return JsonResponse|Response
     */
    public function login(LoginRequest $request): JsonResponse|Response {
        try {
            $username = $request->validated('username');
            $password = $request->validated('password');

            if ($username != 'admin' || $password != config('auth.test_password')) {
                throw new InvalidArgumentException('Usuário ou senha incorretos');
            }

            return self::successResponse(
                message: 'Seja bem vinda Ananda! Login realizado com sucesso!',
                status_code: 201
            );
        } catch (Exception $exception) {
            return self::returnError($exception);
        }
    }

}
