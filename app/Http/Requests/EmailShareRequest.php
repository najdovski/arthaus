<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\AppHelper;
use Exception;
use DateTime;

class EmailShareRequest extends FormRequest
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
            $startedAtInput = $this->request->get('email-started-at') ? new DateTime($this->request->get('started-at')) : null;
            $finishedAtInput = $this->request->get('email-finished-at') ? new DateTime($this->request->get('finished-at')) : null;
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
            'email' => 'required|email',
            'email-started-at' => 'nullable|date|before:email-finished-at',
            'email-finished-at' => 'nullable|date|after:email-started-at',
            'email-current-page' => 'nullable|numeric',
        ];
    }
}
