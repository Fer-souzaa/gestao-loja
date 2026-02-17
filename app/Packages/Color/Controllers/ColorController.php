<?php

namespace App\Packages\Color\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Base\Traits\CacheTrait;
use App\Packages\Color\DTO\StoreColorDTO;
use App\Packages\Color\Requests\StoreColorRequest;
use App\Packages\Color\Services\ListColorsService;
use App\Packages\Color\Services\StoreColorService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ColorController extends BaseController {

    use CacheTrait;

    /**
     * @param ListColorsService $service
     * @return JsonResponse
     */
    public function index(ListColorsService $service): JsonResponse {
        return self::successResponse(
            data: $service->execute(),
            message: 'Listagem de cores retornada com sucesso!'
        );
    }

    /**
     * @param StoreColorRequest $request
     * @param StoreColorService $service
     * @return JsonResponse
     */
    public function store(StoreColorRequest $request, StoreColorService $service): JsonResponse {
        return self::successResponse(
            data: $service->execute(
                new StoreColorDTO(
                    name: $request->validated('name'),
                    active: $request->validated('active')
                )
            ),
            message: 'Cor cadastrada com sucesso!',
            status_code: Response::HTTP_CREATED
        );
    }
}
