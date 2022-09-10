<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfigScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'required',
            'educational_level_id' => [],
            'college_id' => [],
            'period_id' => [],
            'year' => ['nullable','integer'],
            'date_from' => ['required', 'date'],
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ];
    }
}
