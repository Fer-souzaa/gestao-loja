<?php

namespace App\Packages\Color\Controllers;

use App\Base\Http\Controllers\BaseController;
use App\Base\Traits\CacheTrait;
use App\Packages\Color\Services\ListColorsService;
use Illuminate\Http\JsonResponse;

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
}
