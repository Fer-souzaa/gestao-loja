<?php

namespace App\Packages\Collection\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Collection\Models\Collection;

class ListCollectionsService {

    use CacheTrait;

    /**
     * @return mixed
     */
    public function execute(): mixed {
        return $this->cache(
            key: 'collections',
            callback: function () {
                return Collection::select(['id', 'name', 'active'])
                    ->orderBy('name')
                    ->get();
            }
        );
    }
}
