<?php

namespace App\Packages\Customer\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Packages\Customer\DTOs\CustomerStoreDTO;
use App\Packages\Customer\Requests\CustomerStoreRequest;
use App\Packages\Customer\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Throwable;

class CustomerController extends BaseController {
    /**
     * @param CustomerStoreRequest $request
     * @return JsonResponse
     */
    public function store(CustomerStoreRequest $request): JsonResponse {
        try {
            $data = app(CustomerService::class)->store(
                CustomerStoreDTO::fromRequest($request->validated())
            );

            return $this->successResponse($data, 'Cliente cadastrado com sucesso!', 201);
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }
}
