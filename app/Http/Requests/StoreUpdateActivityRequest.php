<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\AppHelper;
use App\Helpers\ActivitiesHelper;
use Exception;
use DateTime;

class StoreUpdateActivityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        /**
         * Check if the activity being edited exists and belongs to the current user,
         * so it can be excluded from the unavailable date range
         */
        if ($this->request->get('activity-id')
            && !ActivitiesHelper::activityExists($this->request->get('activity-id'))) {
            throw new HttpResponseException(
                back()
                ->withInput()
                ->withErrors([
                    'error' => 'The activity you are trying to edit doesn\'t exist',
                ])
            );
        }

        // Check if the date range is available
        if (!ActivitiesHelper::dateRangeAvailable(
            $this->request->get('started-at'),
            $this->request->get('finished-at'),
            $this->request->get('activity-id'))) {
            throw new HttpResponseException(
                back()
                ->withInput()
                ->withErrors([
                    'error' => 'The selected date range isn\'t available',
                ])
            );
        }

        $activityIdRequired = '';
        if ($this->request->get('_method') === 'put') {
            $activityIdRequired = '|required';
        }

        // Check for valid date inputs
        try {
            $nowWithTimezone = new DateTime(AppHelper::formatDateTimeInput(now(), true));
            $startedAtInput = new DateTime($this->request->get('started-at'));
            $finishedAtInput = new DateTime($this->request->get('finished-at'));
        } catch (Exception $exception) {
            throw new HttpResponseException(
                back()
                ->withInput()
                ->withErors([
                    'error' => 'Invalid start/finish input provided',
                ])
            );
        }

        // Check if the start at and finish at inputs are before the current timestamp
        if (($nowWithTimezone < $startedAtInput) || ($nowWithTimezone < $finishedAtInput)) {
            throw new HttpResponseException(
                back()
                ->withInput()
                ->withErrors([
                    'error' => 'The activity can\'t be started or finished after the current timestamp',
                ])
            );
        }

        return [
            'activity-id' => 'integer'.$activityIdRequired,
            'started-at' => 'required|date|before:finished-at',
            'finished-at' => 'required|date|after:started-at',
            'description' => 'required|min:10|max:500',
        ];
    }
}
