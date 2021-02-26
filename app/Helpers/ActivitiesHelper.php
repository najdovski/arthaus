<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class ActivitiesHelper
{
    public static function checkIfAllowed($request): bool
    {
        if (!$request
            || !str_contains($request->path(), 'activities/show')
            || !Session::get('allowedActivityIds')) {
            return false;
        }

        $urlParts = explode('/', $request->path());
        if (is_numeric(end($urlParts))) {
            $activityId = (int)end($urlParts);
            return in_array($activityId, Session::get('allowedActivityIds')['activities']);
        }

        return false;
    }
}
