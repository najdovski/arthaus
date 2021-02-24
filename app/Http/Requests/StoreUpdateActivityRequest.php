<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateActivityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $activityIdRequired = '';
        if ($this->request->get('_method') === 'put') {
            $activityIdRequired = '|required';
        }

        return [
            'activity-id' => 'integer'.$activityIdRequired,
            'started-at' => 'required|date|before:finished-at|before_or_equal:today',
            'finished-at' => 'required|date|after:started-at|before_or_equal:today',
            'description' => 'required|min:10|max:500',
        ];
    }
}
