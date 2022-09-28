<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCurriculumRequest extends FormRequest
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
            'program_id' => ['required',  Rule::unique('curricula')->where(fn ($query) => $query->where('curriculum', $this->curriculum))],
            'curriculum' => ['required',  Rule::unique('curricula')->where(fn ($query) => $query->where('program_id', $this->program_id))],
        ];
    }
}
