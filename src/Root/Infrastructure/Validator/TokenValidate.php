<?php
namespace Infrastructure\Root\Validator;

use Laravel\Sanctum\PersonalAccessToken;

class TokenValidate {
    /**
     * Simple Token Validator Class
     * @param $token
     * @return bool
     */
    public function __invoke($token): bool
    {
        $sanctum = PersonalAccessToken::query()->where('token', '=', $token);
        if($sanctum->count() == 1) {
            return true;
        }
        return false;
    }
}
