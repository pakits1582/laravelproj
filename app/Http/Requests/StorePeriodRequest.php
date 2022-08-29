<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodRequest extends FormRequest
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
            'code' => ['required'],
            'year' => ['required'],
            'term' => ['required'],
            'name' => ['required'],
            'class_start' => ['required', 'date'],
            'class_end' => ['required', 'date'],
            'class_ext' => ['required', 'date'],
            'enroll_start' => ['required', 'date'],
            'enroll_end' => ['required', 'date'],
            'enroll_ext' => ['required', 'date'],
            'adddrop_start' => ['required', 'date'],
            'adddrop_end' => ['required', 'date'],
            'adddrop_ext' => ['required', 'date'],
            'idmask' => ['required'],
            'source' => ['required'],
            'display' => ['required'],
        ];
    }
}
