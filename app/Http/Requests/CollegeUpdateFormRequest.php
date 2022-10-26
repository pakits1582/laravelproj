<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CollegeUpdateFormRequest extends FormRequest
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
            'code' => ['required',  Rule::unique('colleges')->where(fn ($query) => $query
                                                                                ->where('name', $this->name)
                                                                                ->where('class_code', $this->class_code)
                                                                                )->ignore($this->college->id)],
            'name' => ['required',  Rule::unique('colleges')->where(fn ($query) => $query
                                                                                ->where('code', $this->code)
                                                                                ->where('class_code', $this->class_code)
                                                                                )->ignore($this->college->id)],
            'class_code' => ['required', 'alpha', 'min:1', 'max:2', Rule::unique('colleges', 'class_code')->ignore($this->college->id),],
            'dean' => '',
        ];
    }
}
