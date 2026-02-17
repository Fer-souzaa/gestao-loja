<?php

namespace App\Packages\Color\Services;

use App\Packages\Color\DTO\StoreColorDTO;
use App\Packages\Color\Models\Color;
use Illuminate\Support\Facades\Cache;

class StoreColorService {

    /**
     * @param StoreColorDTO $data
     * @return Color
     */
    public function execute(StoreColorDTO $data): Color {
        $color = Color::firstOrCreate($data->toArray());
        Cache::forget('colors');
        return $color;
    }
}
