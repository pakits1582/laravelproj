<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGradingSystemRequest extends FormRequest
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
            'educational_level_id.unique' => 'A grade with the following code, value and level already exists.',
            'code.unique' => 'A grade with the following code, value and level already exists.',
            'value.unique' => 'A grade with the following code, value and level already exists.',
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
            'educational_level_id' => ['required', Rule::unique('grading_systems')->where(fn ($query) => $query
                                                                                ->where('code', $this->code)
                                                                                ->where('value', $this->value)
            )->ignore($this->gradingsystem->id)],
            'code' => ['required', Rule::unique('grading_systems')->where(fn ($query) => $query
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('value', $this->value)
            )->ignore($this->gradingsystem->id)],
            'value' => ['required', Rule::unique('grading_systems')->where(fn ($query) => $query
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('code', $this->code)
            )->ignore($this->gradingsystem->id)],
            'remark_id' => 'required'
        ];
    }
}
