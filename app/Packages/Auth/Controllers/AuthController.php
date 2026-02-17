<?php

namespace App\Packages\Auth\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Packages\Auth\Requests\LoginRequest;
use App\Packages\Auth\Services\LoginService;
use Exception;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends BaseController {

    /**
     * @param LoginRequest $request
     * @param LoginService $service
     * @return JsonResponse|Response
     * @throws Throwable
     */
    public function login(LoginRequest $request, LoginService $service): JsonResponse|Response {
        try {
            return self::successResponse(
                data: $service->execute(
                    username: $request->validated('username'),
                    password: $request->validated('password')
                ),
                message: 'Login realizado com sucesso!',
                status_code: Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return self::returnError($exception);
        }
    }

}
