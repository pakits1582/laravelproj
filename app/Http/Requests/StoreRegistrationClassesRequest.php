<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationClassesRequest extends FormRequest
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

    public function messages()
    {
        return [
            'class_ids.*.exists' => 'One or more selected classes do not exist.',
            'class_ids.required' => 'Please select at least one class subject to add.',
            'enrollment_id.required' => 'Enrollment not found, please refresh page.',
            'enrollment_id.exists' => 'Enrollment not found, please refresh page.',
            'section_id.required' => 'Section not found, please refresh page',
            'section_id.exists' => 'Section not found, please refresh page',
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'enrollment_id' => 'required|exists:enrollments,id',
            'class_ids' => 'required|array|min:1',
            'class_ids.*' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'units_allowed' => 'required|integer',
        ];
    }
}
