<?php

namespace App\Packages\Collection\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Packages\Collection\DTO\StoreCollectionDTO;
use App\Packages\Collection\DTO\UpdateCollectionDTO;
use App\Packages\Collection\Requests\StoreCollectionRequest;
use App\Packages\Collection\Requests\UpdateCollectionRequest;
use App\Packages\Collection\Services\DeleteCollectionService;
use App\Packages\Collection\Services\ListCollectionsService;
use App\Packages\Collection\Services\StoreCollectionService;
use App\Packages\Collection\Services\UpdateCollectionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CollectionController extends BaseController {

    /**
     * @param ListCollectionsService $service
     * @return JsonResponse
     */
    public function index(ListCollectionsService $service): JsonResponse {
        return self::successResponse(
            data: $service->execute(),
            message: 'Listagem de coleções retornada com sucesso!'
        );
    }

    /**
     * @param StoreCollectionRequest $request
     * @param StoreCollectionService $service
     * @return JsonResponse
     */
    public function store(StoreCollectionRequest $request, StoreCollectionService $service): JsonResponse {
        return self::successResponse(
            data: $service->execute(
                new StoreCollectionDTO(
                    name: $request->validated('name'),
                    active: $request->validated('active') ?? true
                )
            ),
            message: 'Coleção cadastrada com sucesso!',
            status_code: Response::HTTP_CREATED
        );
    }

    /**
     * @param int $id
     * @param UpdateCollectionRequest $request
     * @param UpdateCollectionService $service
     * @return JsonResponse
     */
    public function update(int $id, UpdateCollectionRequest $request, UpdateCollectionService $service): JsonResponse {
        try {
            return self::successResponse(
                data: $service->execute(
                    $id,
                    new UpdateCollectionDTO(
                        name: $request->validated('name'),
                        active: $request->validated('active')
                    )
                ),
                message: 'Coleção atualizada com sucesso!'
            );
        } catch (Exception $exception) {
            return self::returnError($exception);
        }
    }

    /**
     * @param int $id
     * @param DeleteCollectionService $service
     * @return JsonResponse
     */
    public function destroy(int $id, DeleteCollectionService $service): JsonResponse {
        try {
            return self::successResponse(
                data: $service->execute($id),
                message: 'Coleção removida com sucesso!'
            );
        } catch (Exception $exception) {
            return self::returnError($exception);
        }
    }
}
