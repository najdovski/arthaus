<?php

namespace App\Repositories;

use App\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\FilterActivitiesRequest;
use App\Helpers\AppHelper;
use App\Models\ActivityAccessToken;

class ActivityRepository
{
    private $userId = null;

    /**
     * Get activities
     */
    public function get(FilterActivitiesRequest $request = null): LengthAwarePaginator
    {
        $startedAt = $request && $request->input('started-at') ? $request->input('started-at') : '';
        $finishedAt = $request && $request->input('finished-at') ? $request->input('finished-at') : AppHelper::formatDateTimeInput(now(), true);

        $orderBy = 'created_at';
        $orderType = 'DESC';
        if ($request && ($request->input('started-at') || $request->input('finished-at'))) {
            $orderBy = 'started_at';
            $orderType = 'ASC';
        }

        try {
            $activities = Activity::
            where([
                ['user_id', $this->userId ?? auth()->user()->id],
                ['started_at', '>=', $startedAt],
                ['started_at', '<=', AppHelper::formatDateTimeInput(now(), true)],
                ['finished_at', '<=', $finishedAt],
                ['finished_at', '<=', AppHelper::formatDateTimeInput(now(), true)],
            ])
            ->orderBy($orderBy, $orderType)
            ->with('user')
            ->paginate(config('app.pagination_items'));
        } catch (Exception $exception) {
            throw $exception;
        }

        return $activities;
    }

    /**
     * Get a single activity
     */
    public function getSingle(string $id): Activity | null
    {
        try {
            $activity = Activity::where([
                ['id', '=', $id],
                ['user_id', '=', auth()->user()->id]
            ])->first();

            return $activity;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Store an activity
     */
    public function store($request): void
    {
        try {
            Activity::create([
                'user_id' => auth()->user()->id,
                'started_at' => $request->input('started-at'),
                'finished_at' => $request->input('finished-at'),
                'description' => $request->input('description'),
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Update an activity
     */
    public function update($request): void
    {
        try {
            $activity = $this->getSingle($request->input('activity-id'));
            $activity->description = $request->input('description');
            $activity->started_at = $request->input('started-at');
            $activity->finished_at = $request->input('finished-at');
            $activity->save();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Remove an activity
     */
    public function remove(string $id): void
    {
        try {
            $activity = Activity::where([
                ['id', '=', $id],
                ['user_id', '=', auth()->user()->id]
            ])->first();

            if ($activity) {
                $activity->delete();
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get request by passed token from the URL
     */
    public function getRequestByToken(string $token): FilterActivitiesRequest
    {
        $queryInDatabase = ActivityAccessToken::where('url_token', '=', $token)->first();
        $request = new FilterActivitiesRequest([
            'started-at' => AppHelper::formatDateTimeInput($queryInDatabase->started_at),
            'finished-at' => AppHelper::formatDateTimeInput($queryInDatabase->finished_at),
            'email' => $queryInDatabase->email,
        ]);

        $this->userId = $queryInDatabase->user_id;

        return $request;
    }
}
