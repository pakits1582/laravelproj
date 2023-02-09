<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
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
            'enrollment_id' => 'required',
            'student_id' => 'required',
            'program_id' => 'required', 
            'year_level' => 'required', 
            'curriculum_id' => 'required', 
            'section_id' => 'required',
            'enrolled_units' => [],
            'new'          => [],
            'old'          => [],
            'returnee'      => [],
            'transferee'   => [],
            'cross_enrollee' => [],
            'probationary' => [],
            'foreigner' => [],
        ];
    }
}
