<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\AppHelper;
use Exception;
use DateTime;

class FilterActivitiesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Check for valid date inputs
        try {
            $nowWithTimezone = new DateTime(AppHelper::formatDateTimeInput(now(), true));
            $startedAtInput = $this->request->get('started-at') ? new DateTime($this->request->get('started-at')) : null;
            $finishedAtInput = $this->request->get('finished-at') ? new DateTime($this->request->get('finished-at')) : null;
        } catch (Exception $exception) {
            throw new HttpResponseException(
                redirect()
                ->route('activities')
                ->withInput()
                ->withErors([
                    'error' => 'Invalid start/finish input provided',
                ])
            );
        }

        // Check if the start at and finish at inputs are before the current timestamp
        if (($nowWithTimezone < $startedAtInput) || ($nowWithTimezone < $finishedAtInput)) {
            throw new HttpResponseException(
                redirect()
                ->route('activities')
                ->withInput()
                ->withErrors([
                    'error' => 'The activity can\'t be started or finished after the current timestamp',
                ])
            );
        }

        return [
            'started-at' => 'nullable|date|before:finished-at',
            'finished-at' => 'nullable|date|after:started-at',
        ];
    }
}
