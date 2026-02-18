<?php

namespace App\Packages\Collection\Services;

use App\Packages\Collection\Models\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class DeleteCollectionService
{
    /**
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function execute(int $id): bool
    {
        $collection = Collection::query()->find($id);

        if (!$collection) {
            throw new ModelNotFoundException('Coleção não encontrada!');
        }

        $deleted = (bool) $collection->delete();

        if ($deleted) {
            Cache::forget('collections');
        }

        return $deleted;
    }
}
