<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstructorRequest extends FormRequest
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
            'educational_level_id.required' => 'The field educational level is required.',
            'college_id.required' => 'The field college is required.',
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
            'idno' => 'required|unique:users|max:10',
            'last_name' => 'required',
            'first_name' => 'required',
            'college_id' => 'required',
            'educational_level_id' => 'required',
            'designation' => 'required',
            'middle_name' => 'required',
            'name_suffix' => '',
            'name_prefix' => '',
            'department_id' => '',
        ];
    }
}
