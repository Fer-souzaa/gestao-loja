<?php

namespace App\Packages\Auth\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Auth\Models\User;

class UserInCacheService {

    use CacheTrait;

    /**
     * @param $user_id
     * @param mixed|null $user_data
     * @return mixed
     */
    public function execute($user_id, mixed $user_data = null): mixed {
        return $this->cache(
            key: 'user_id_' . $user_id,
            callback: function () use ($user_data, $user_id) {
                return $user_data ?? User::find($user_id);
            }
        );
    }
}
