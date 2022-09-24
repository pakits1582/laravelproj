<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'idno' => 'required|int',
            'last_name' => 'required', 
            'first_name' => 'required', 
            'middle_name' => [], 
            'name_suffix' => [], 
            'sex' => 'required', 
            'program_id' => 'required', 
            'year_level' => 'required', 
            'curriculum_id' => 'required', 
            'academic_status' => 'required'
        ];
    }
}
