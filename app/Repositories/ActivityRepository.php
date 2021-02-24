<?php

namespace App\Repositories;

use App\Models\Activity;

class ActivityRepository
{
    /**
     * Get activities
     */
    public function get()
    {
        try {
            $activities = Activity::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->with('user')
            ->paginate(config('app.pagination_items'));
        } catch (Exception $exception) {
            throw $exception;
        }

        return $activities;
    }

    /**
     * Get single activity
     */
    public function getSingle(string $id): Activity | null
    {
        try {
            $activity = Activity::where([
                ['id', '=', $id],
                ['user_id', '=', auth()->user()->id]
            ])->get()->first();

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
            ])->get();

            if ($activity->first()) {
                $activity->first()->delete();
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
