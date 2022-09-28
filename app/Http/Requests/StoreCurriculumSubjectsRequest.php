<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurriculumSubjectsRequest extends FormRequest
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
            'curriculum_id.required' => 'The curriculum field is required.',
            'term_id.required' => 'The term field is required.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'curriculum_id' => $this->curriculum,
            'term_id' => $this->term,
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'program_id' => 'required',
            'curriculum_id' => 'required',
            'term_id' => 'required',
            'year_level' => 'required',
            'subjects' => 'required',
        ];
    }
}
