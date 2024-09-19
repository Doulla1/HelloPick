<?php

namespace App\Services;

use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class TokenService
{
    /**
     * Extract the token from the Authorization header.
     *
     * @param string|null $authorizationHeader
     * @return string|null
     */
    public function extractToken(?string $authorizationHeader): ?string
    {
        if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Validate the token and return the associated user.
     *
     * @param string $token
     * @return User|null
     */
    public function validateToken(string $token): ?User
    {
        $accessToken = PersonalAccessToken::findToken($token);

        // Return the associated user if the token is valid
        return $accessToken ? $accessToken->tokenable : null;
    }
}
