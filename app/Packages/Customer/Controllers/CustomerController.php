<?php

namespace App\Packages\Customer\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Packages\Customer\DTOs\CustomerStoreDTO;
use App\Packages\Customer\Requests\CustomerStoreRequest;
use App\Packages\Customer\Resources\CustomerDetailResource;
use App\Packages\Customer\Resources\CustomerResource;
use App\Packages\Customer\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Throwable;

class CustomerController extends BaseController {
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        try {
            $data = app(CustomerService::class)->list();

            return $this->successResponse(
                CustomerResource::collection($data),
                'Clientes listados com sucesso!'
            );
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }

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

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {
        try {
            $data = app(CustomerService::class)->show($id);

            return $this->successResponse(
                new CustomerDetailResource($data),
                'Dados do cliente recuperados com sucesso!'
            );
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }

    /**
     * @param CustomerStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CustomerStoreRequest $request, int $id): JsonResponse {
        try {
            $data = app(CustomerService::class)->update(
                $id,
                CustomerStoreDTO::fromRequest($request->validated())
            );

            return $this->successResponse($data, 'Cliente atualizado com sucesso!');
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse {
        try {
            app(CustomerService::class)->destroy($id);

            return $this->successResponse([], 'Cliente removido com sucesso!');
        } catch (Throwable $exception) {
            return $this->returnError($exception);
        }
    }
}
