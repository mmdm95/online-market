<?php

namespace App\Logic\Adapters;

use Pecee\Http\Security\ITokenProvider;

class SessionTokenProvider implements ITokenProvider
{
    /**
     * Refresh existing token
     * @throws \Exception
     */
    public function refresh(): void
    {
        csrf()->regenerateToken(trim(url()->getOriginalUrl(), '/'));
    }

    /**
     * Validate valid CSRF token
     *
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    public function validate(string $token): bool
    {
        return csrf()->validate($token, trim(url()->getOriginalUrl(), '/'));
    }

    /**
     * Get token token
     *
     * @param string|null $defaultValue
     * @return string|null
     * @throws \Exception
     */
    public function getToken(?string $defaultValue = null): ?string
    {
        return csrf()->getToken(trim(url()->getOriginalUrl(), '/')) ?? $defaultValue;
    }
}
