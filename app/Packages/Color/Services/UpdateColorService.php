<?php

namespace App\Packages\Color\Services;

use App\Packages\Color\DTO\UpdateColorDTO;
use App\Packages\Color\Models\Color;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class UpdateColorService
{
    /**
     * @param int $id
     * @param UpdateColorDTO $data
     * @return Color
     * @throws ModelNotFoundException
     */
    public function execute(int $id, UpdateColorDTO $data): Color
    {
        $color = Color::query()->find($id);

        if (!$color) {
            throw new ModelNotFoundException('Cor não encontrada!');
        }

        $color->update($data->toArray());

        Cache::forget('colors');

        return $color;
    }
}
