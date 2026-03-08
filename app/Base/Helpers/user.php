<?php

use App\Packages\Auth\Services\TokenInCacheService;


/**
 * @return string
 */
function getClientIp(): string {
    return request()->header('X-Client-Ip') ?? request()->getClientIp();
}

/**
 * @return string|null
 */
function getToken(): ?string {
    return handlerRequestToken(request()->bearerToken());
}
