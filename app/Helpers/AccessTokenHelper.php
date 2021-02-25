<?php

namespace App\Helpers;

use App\Models\ActivityAccessToken;

class AccessTokenHelper
{
    private static $allowedPaths = [
        'activities',
    ];

    /**
     * Check if the token is valid
     */
    public static function checkToken($request): bool
    {
        if (!$request || !array_key_exists('token', $request->query())) {
            return false;
        }

        $token = $request->query()['token'];
        $path = $request->path();
        
        $tokenInDatabase = ActivityAccessToken::where('url_token', '=', $token)->first();

        if ($tokenInDatabase && in_array($path, self::$allowedPaths)) {
            return true;
        }

        return false;
    }
}
