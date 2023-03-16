<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateScholarshipdiscountRequest extends FormRequest
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
        $rules = [
            'code' => ['required', 'max:50',  Rule::unique('scholarshipdiscounts')->where(fn ($query) => $query->where('description', $this->description))->ignore($this->scholarshipdiscount->id)],
            'description' => ['required', 'max:255',  Rule::unique('scholarshipdiscounts')->where(fn ($query) => $query->where('code', $this->code))->ignore($this->scholarshipdiscount->id)],
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

        if ($this->input('tuition_type') == 'rate') {
            $rules['tuition'] .= '|numeric|max:100';
        } elseif ($this->input('tuition_type') == 'fixed') {
            $rules['tuition'] .= '|numeric|max:1000000';
        }

        return $rules;
    }
}
