<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramRequest extends FormRequest
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
            'code' => ['required',  Rule::unique('programs')->where(fn ($query) => $query->where('name', $this->name))->ignore($this->program->id)],
            'name' => ['required',  Rule::unique('programs')->where(fn ($query) => $query->where('code', $this->code))->ignore($this->program->id)],
            'years' => 'required|integer',
            'educational_level_id' => 'required',
            'college_id' => 'required',
            'head' => '',
            'source' => '',
            'active' => '',
        ];
    }
}
