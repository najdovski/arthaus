<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Repositories\ActivityRepository;
use App\Http\Requests\StoreUpdateActivityRequest;
use Exception;

class ActivityController extends Controller
{
    private $activityObj;

    public function __construct()
    {
        $this->activityObj = new ActivityRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $activities = $this->activityObj->get();
        } catch (Exception $exception) {
            $activities = [];
        }

        return view(
            'activities.index',
            [
                'activities' => $activities
            ]
        );
    }

    /**
     * Show the form for creating a new activity
     */
    public function create()
    {
        return view('activities.create-edit');
    }

    /**
     * Store a newly created activity
     */
    public function store(StoreUpdateActivityRequest $request)
    {
        try {
            $this->activityObj->store($request);
        } catch (Exception $exception) {
            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'The activity couldn\'t be created. Try again',
            ]);
        }

        return redirect()
        ->route('activities')
        ->withSuccess([
            'success' => 'Activity created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the edit form
     */
    public function edit(string $activityId)
    {
        try {
            $activity = $this->activityObj->getSingle($activityId);
        } catch (Exception $exception) {
            $activity = null;
        }

        if (!$activity) {
            return redirect()
            ->route('activities')
            ->withErrors([
                'error' => 'The selected activity doesn\'t exist',
            ]);
        }

        return view(
            'activities.create-edit',
            [
                'activity' => $activity
            ]
        );
    }

    /**
     * Update the specified activity
     *
     */
    public function update(StoreUpdateActivityRequest $request)
    {
        try {
            $this->activityObj->update($request);
        } catch (Exception $exception) {
            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'Couldn\'t update the activity',
            ]);
        }

        return redirect()
        ->route('activities')
        ->withSuccess([
            'success' => 'Activity updated successfully',
        ]);
    }

    /**
     * Remove the specified activity
     */
    public function destroy(string $activityId)
    {
        try {
            $this->activityObj->remove($activityId);
        } catch (Exception $exception) {
            return redirect()
            ->back()
            ->withErrors([
                'error' => 'Couldn\'t remove the selected activity',
            ]);
        }

        return redirect()
        ->back()
        ->withSuccess([
            'success' => 'Activity removed successfully',
        ]);
    }
}
