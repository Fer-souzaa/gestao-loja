<?php

namespace App\Packages\Color\Services;

use App\Packages\Color\Models\Color;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class DeleteColorService
{
    /**
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function execute(int $id): bool
    {
        $color = Color::query()->find($id);

        if (!$color) {
            throw new ModelNotFoundException('Cor não encontrada!');
        }

        $deleted = (bool) $color->delete();

        if ($deleted) {
            Cache::forget('colors');
        }

        return $deleted;
    }
}
