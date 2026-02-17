<?php

use App\Packages\Admin\User\Services\UserDataInCacheByTokenService;

/**
 * @return string
 */
function getClientIp(): string {
    return request()->header('X-Client-Ip') ?? request()->getClientIp();
}

function userObject(): mixed {
    return data_get(app(UserDataInCacheByTokenService::class)->execute(), 'user');
}
