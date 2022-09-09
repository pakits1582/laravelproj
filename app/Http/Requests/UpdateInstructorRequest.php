<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstructorRequest extends FormRequest
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
            'last_name.unique' => 'An instructor with the following first, last, middle name and suffix already exists.',
            'first_name.unique' => 'An instructor with the following first, last, middle name and suffix already exists.',
            'middle_name.unique' => 'An instructor with the following first, last, middle name and suffix already exists.',
            'name_suffix.unique' => 'An instructor with the following first, last, middle name and suffix already exists.',
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
            'idno' => ['required',  Rule::unique('users')->ignore($this->instructor->user_id)],
            'last_name' => ['required',  Rule::unique('instructors')->where(fn ($query) => $query
                ->where('first_name', $this->first_name)
                ->where('middle_name', $this->middle_name)
                ->where('name_suffix', $this->name_suffix)
            )->ignore($this->instructor->id)],
            'first_name' => ['required',  Rule::unique('instructors')->where(fn ($query) => $query
                ->where('last_name', $this->last_name)
                ->where('middle_name', $this->middle_name)
                ->where('name_suffix', $this->name_suffix)
            )->ignore($this->instructor->id)],
            'middle_name' => [Rule::unique('instructors')->where(fn ($query) => $query
                ->where('last_name', $this->last_name)
                ->where('first_name', $this->first_name)
                ->where('name_suffix', $this->name_suffix)
            )->ignore($this->instructor->id)],
            'name_suffix' => [Rule::unique('instructors')->where(fn ($query) => $query
                ->where('last_name', $this->last_name)
                ->where('middle_name', $this->middle_name)
                ->where('first_name', $this->first_name)
            )->ignore($this->instructor->id)],
            'college_id' => 'required',
            'educational_level_id' => 'required',
            'designation' => 'required',
            'name_prefix' => '',
            'department_id' => '',
        ];
    }
}
