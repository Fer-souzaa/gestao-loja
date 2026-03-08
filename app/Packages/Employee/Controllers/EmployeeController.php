<?php

namespace App\Packages\Employee\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Packages\Employee\DTOs\SellerDTO;
use App\Packages\Employee\Requests\SellerStoreRequest;
use App\Packages\Employee\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class EmployeeController extends BaseController {


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {
        try {
            return $this->successResponse(
                data: app(EmployeeService::class)->listSellers(),
                message: 'Vendedores listados com sucesso!');
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }

    /**
     * @param SellerStoreRequest $request
     * @return JsonResponse
     */
    public function storeSeller(SellerStoreRequest $request): JsonResponse {
        try {
            $data = app(EmployeeService::class)->storeSeller(
                SellerDTO::fromRequest($request->validated())
            );

            return $this->successResponse($data, 'Vendedor cadastrado com sucesso!');
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function terminateSeller(int $id): JsonResponse {
        try {
            app(EmployeeService::class)->terminateSeller($id);
            return $this->successResponse(message: 'Vendedor desvinculado com sucesso!');
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }
}
