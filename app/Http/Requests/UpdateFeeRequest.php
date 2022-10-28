<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeeRequest extends FormRequest
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
            'fee_type_id.unique' => 'A fee with the following code, name and fee type already exists.',
            'code.unique' => 'A fee with the following code, name and fee type already exists.',
            'name.unique' => 'A fee with the following code, name and fee type already exists.',
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
            'fee_type_id' => ['required', Rule::unique('fees')->where(fn ($query) => $query
                                                                                ->where('code', $this->code)
                                                                                ->where('name', $this->name)
            )->ignore($this->fee->id)],
            'code' => ['required', Rule::unique('fees')->where(fn ($query) => $query
                                                                                ->where('fee_type_id', $this->fee_type_id)
                                                                                ->where('name', $this->name)
            )->ignore($this->fee->id)],
            'name' => ['required', Rule::unique('fees')->where(fn ($query) => $query
                                                                                ->where('fee_type_id', $this->fee_type_id)
                                                                                ->where('code', $this->code)
            )->ignore($this->fee->id)],
            'iscompound' => '',
            'default_value' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
