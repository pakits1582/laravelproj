<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
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
            'program_id.required' => 'The program field is required.',
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
            'code' => ['required',  Rule::unique('sections')->where(fn ($query) => $query->where('name', $this->name))->ignore($this->section->id)],
            'name' => ['required',  Rule::unique('sections')->where(fn ($query) => $query->where('code', $this->code))->ignore($this->section->id)],
            'program_id' => 'required',
            'year' => ['required', 'integer'],
            'minenrollee' => ['required', 'integer'],
        ];
    }
}
