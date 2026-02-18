<?php

namespace App\Packages\Collection\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Collection\DTO\UpdateCollectionDTO;
use App\Packages\Collection\Models\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class UpdateCollectionService
{

    use CacheTrait;

    /**
     * @param int $id
     * @param UpdateCollectionDTO $data
     * @return Collection
     * @throws ModelNotFoundException
     */
    public function execute(int $id, UpdateCollectionDTO $data): Collection
    {
        $collection = Collection::query()->find($id);

        if (!$collection) {
            throw new ModelNotFoundException('Coleção não encontrada!');
        }

        $collection->update($data->toArray());

        $this->clearCache('collections');

        return $collection;
    }
}
