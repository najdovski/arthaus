<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Repositories\ActivityRepository;
use App\Repositories\ReportRepository;
use Carbon\Carbon;

class ActivitiesHelper
{
    /**
     * Check if the access to a single activity is allowed, based on
     * an email shared token
     */
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

    /**
     * Get all unavailable date ranges
     */
    public static function getUnavailableDateRanges($excludeActivityId = null): array
    {
        $activitiesRepo = new ActivityRepository();
        $activities = $activitiesRepo->getAllActivities();

        if ($excludeActivityId) {
            $activities = $activities->reject(function($activity) use ($excludeActivityId) {
                return $activity->id === (int)$excludeActivityId;
            });
        }

        $dateRanges = [];
        foreach ($activities as $activity) {
            $range['from'] = $activity->started_at;
            $range['to'] = $activity->finished_at;
            array_push($dateRanges, $range);
        }
        return $dateRanges;
    }

    /**
     * Check if the date range is available for adding/editing an activity
     */
    public static function dateRangeAvailable($startAt, $finishAt, $excludeActivityId = null): bool
    {
        if (!$startAt || !$finishAt) {
            return false;
        }

        $unavailableRanges = self::getUnavailableDateRanges($excludeActivityId);

        if (!$unavailableRanges) {
            return true;
        }

        foreach ($unavailableRanges as $range) {
            if (Carbon::create($startAt)->between($range['from'], $range['to'])
                || Carbon::create($finishAt)->between($range['from'], $range['to'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get fully unavailable/disabled dates (24h of activity)
     */
    public static function getDisabledDates($excludeActivityId = null)
    {
        $reportRepo = new ReportRepository();
        $activitiesRepo = new ActivityRepository();
        $activities = $activitiesRepo->getAllActivities();

        if ($excludeActivityId) {
            $activities = $activities->reject(function($activity) use ($excludeActivityId) {
                return $activity->id === $excludeActivityId;
            });
        }

        if (sizeof($activities) < 1) {
            return null;
        }

        $allDatesWithTotalTime = $reportRepo->getAllDatesWithTotalTime(
            $activities,
            $activities->first()->started_at,
            $activities->last()->finished_at,
        );

        $disabledDates = array_filter($allDatesWithTotalTime, function($minutes, $date) {
            return $minutes === 1440;
        }, ARRAY_FILTER_USE_BOTH);

        $disabledDates = array_keys($disabledDates);
        $json = json_encode($disabledDates);
        $json = str_replace('"', '', $json);
        $json = trim($json, '[]');
        return $json;
    }

    /**
     * Check if the selected activity exists and belongs to the current user
     */
    public static function activityExists($activityId = null)
    {
        if (!$activityId) {
            return true;
        }

        $activitiesRepo = new ActivityRepository();
        $activities = $activitiesRepo->getAllActivities();
        $findInCollection = $activities->where('id', $activityId);

        if (sizeof($findInCollection) > 0) {
            return true;
        }

        return false;
    }
}
