<?php

namespace App\Packages\Collection\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Collection\DTO\StoreCollectionDTO;
use App\Packages\Collection\Models\Collection;

class StoreCollectionService {

    use CacheTrait;

    /**
     * @param StoreCollectionDTO $data
     * @return Collection
     */
    public function execute(StoreCollectionDTO $data): Collection {
        $collection = Collection::firstOrCreate($data->toArray());
        $this->clearCache('collections');
        return $collection;
    }
}
