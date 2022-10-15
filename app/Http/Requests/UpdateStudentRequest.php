<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'last_name.unique' => 'A student with the following first, last, middle name and suffix already exists.',
            'first_name.unique' => 'A student with the following first, last, middle name and suffix already exists.',
            'middle_name.unique' => 'A student with the following first, last, middle name and suffix already exists.',
            'name_suffix.unique' => 'A student with the following first, last, middle name and suffix already exists.',
            'program_id.required' => 'The field program is required.',
            'curriculum_id.required' => 'The field curriculum is required.',
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
            'idno' => ['required',  Rule::unique('users')->ignore($this->student->user_id)],
            'last_name' => ['required',  Rule::unique('students')->where(fn ($query) => $query
                ->where('first_name', $this->first_name)
                ->where('middle_name', $this->middle_name)
                ->where('name_suffix', $this->name_suffix)
            )->ignore($this->student->id)],
            'first_name' => ['required',  Rule::unique('students')->where(fn ($query) => $query
                ->where('last_name', $this->last_name)
                ->where('middle_name', $this->middle_name)
                ->where('name_suffix', $this->name_suffix)
            )->ignore($this->student->id)],
            'middle_name' => [Rule::unique('students')->where(fn ($query) => $query
                ->where('last_name', $this->last_name)
                ->where('first_name', $this->first_name)
                ->where('name_suffix', $this->name_suffix)
            )->ignore($this->student->id)],
            'name_suffix' => [Rule::unique('students')->where(fn ($query) => $query
                ->where('last_name', $this->last_name)
                ->where('middle_name', $this->middle_name)
                ->where('first_name', $this->first_name)
            )->ignore($this->student->id)],
            'sex' => 'required',
            'program_id' => 'required',
            'curriculum_id' => 'required',
            'year_level' => 'required',
            'academic_status' => 'required',
        ];
    }
}
