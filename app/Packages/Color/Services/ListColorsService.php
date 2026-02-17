<?php

namespace App\Packages\Color\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Color\Models\Color;

class ListColorsService {

    use CacheTrait;

    /**
     * @return mixed
     */
    public function execute(): mixed {
        return $this->cache(
            key: 'colors',
            callback: function () {
                return Color::select(['id', 'name', 'active'])
                    ->orderBy('name')
                    ->get();
            }
        );
    }
}
