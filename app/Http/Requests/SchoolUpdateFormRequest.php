<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SchoolUpdateFormRequest extends FormRequest
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
            'code' => ['required',  Rule::unique('schools')->where(fn ($query) => $query->where('name', $this->name))->ignore($this->school->id)],
            'name' => ['required',  Rule::unique('schools')->where(fn ($query) => $query->where('code', $this->code))->ignore($this->school->id)]
        ];
    }
}
