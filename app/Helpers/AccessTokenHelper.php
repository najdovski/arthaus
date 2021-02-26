<?php

namespace App\Helpers;

use App\Models\ActivityAccessToken;
use Illuminate\Support\Facades\Session;
use DateTime;

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

        if ($tokenInDatabase
            && in_array($path, self::$allowedPaths)
            && !self::tokenExpired($tokenInDatabase->created_at)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the token is expired
     */
    private static function tokenExpired(string $created_at): bool
    {
        // If lifetime is not set int the ENV
        if (!config('app.token_lifetime_hours')) {
            return false;
        }

        if (config('app.token_lifetime_hours')) {
            $now = new DateTime();
            $created = new DateTime($created_at);
            $diffInHours = ($created->diff($now)->h);

            if ($diffInHours > config('app.token_lifetime_hours')) {
                Session::forget('allowedActivityIds');
                return true;
            }
        }

        return false;
    }
}
