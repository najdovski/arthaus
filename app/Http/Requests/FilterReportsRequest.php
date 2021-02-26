<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\AppHelper;
use Exception;
use DateTime;

class FilterReportsRequest extends FormRequest
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
            $startDateInput = $this->request->get('start-date') ? new DateTime($this->request->get('start-date')) : null;
            $endDateInput = $this->request->get('end-date') ? new DateTime($this->request->get('end-date')) : null;
        } catch (Exception $exception) {
            throw new HttpResponseException(
                redirect()
                ->route('reports')
                ->withInput()
                ->withErors([
                    'error' => 'Invalid start/end date input provided',
                ])
            );
        }

        // Check if the start at and finish at inputs are before the current timestamp
        if (($nowWithTimezone < $startDateInput) || ($nowWithTimezone < $endDateInput)) {
            throw new HttpResponseException(
                redirect()
                ->route('reports')
                ->withInput()
                ->withErrors([
                    'error' => 'You can\'t search for reports in the future',
                ])
            );
        }

        return [
            'start-date' => 'nullable|date|before_or_equal:end-date',
            'end-date' => 'nullable|date|after_or_equal:start-date',
        ];
    }
}
