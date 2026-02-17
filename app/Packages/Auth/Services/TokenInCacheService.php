<?php

namespace App\Packages\Auth\Services;

use App\Base\Traits\CacheTrait;
use App\Packages\Auth\Models\PersonalAccessToken;

class TokenInCacheService {

    use CacheTrait;

    /**
     * @param $token
     * @param mixed|null $data
     * @return mixed
     */
    public function execute($token, mixed $data = null): mixed {
        return $this->cache(
            key: 'token_' . $token,
            callback: function () use ($data, $token) {
                return $data ?? PersonalAccessToken::where('token', $token)->first();
            }
        );

    }
}
