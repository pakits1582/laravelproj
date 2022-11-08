<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExternalGradeRequest extends FormRequest
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
            'student_id' => 'required',
            'period_id' => 'required',
            'school_id' => 'required',
            'program_id' => 'required',
            'subject_code' => 'required',
            'subject_description' => '',
            'grade' => '',
            'completion_grade' => '',
            'units' => '',
            'remark_id' => '',
            'equivalent_grade' => ''
        ];
    }
}
