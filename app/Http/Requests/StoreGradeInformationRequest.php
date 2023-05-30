<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeInformationRequest extends FormRequest
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
            'displays' => '',
            'remarks' => '',
            'underlines' => '',

            'grade_id' => 'required',
            'school_id' => 'nullable|required_with:graduation_date',
            'program_id' => 'nullable|required_with:graduation_date',

            'thesis_title' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date',
            'graduation_award' => 'nullable',

            'soresolution_id' => 'nullable',
            'soresolution_no' => 'nullable|required_with:soresolution_id',
            'soresolution_series' => 'nullable|required_with:soresolution_id',
            'issueing_office_id' => 'nullable|required_with:soresolution_id',
            'issued_date' => 'nullable|required_with:soresolution_id|date',

            'remark' => 'nullable|string|max:255',

        ];
    }

    public function messages()
    {
        return [
            'school_id.required_with' => 'The School field is required when the graduation field is not empty.',
            'program_id.required_with' => 'The Program field is required when the graduation field is not empty.',
            'soresolution_no.required_with' => 'The Number(No.) field is required when the S.O/Resolution field is not empty.',
            'soresolution_series.required_with' => 'The Series field is required when the S.O/Resolution field is not empty.',
            'issueing_office_id.required_with' => 'The Issued By field is required when the S.O/Resolution field is not empty.',
            'issued_date.required_with' => 'The Issued Date field is required when the S.O/Resolution field is not empty.',
            'issued_date.date' => 'The Issued Date field must be a valid date format.',
        ];
    }
}
