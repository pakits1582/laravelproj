<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScholarshipdiscountRequest extends FormRequest
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
            'code' => 'required|max:50',
            'description' => 'required|max:255',
            'tuition_type' => [],
            'tuition' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'miscellaneous_type' => [],
            'miscellaneous' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'othermisc_type' => [],
            'othermisc' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'laboratory_type' => [],
            'laboratory' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'totalassessment_type' => [],
            'totalassessment' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'type' => 'required'
        ];
    }
}
