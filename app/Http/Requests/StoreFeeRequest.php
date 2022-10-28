<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeRequest extends FormRequest
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
            'fee_type_id.required' => 'The fee type field is required.',
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
            'fee_type_id' => 'required',
            'code' => 'required',
            'name' => 'required',
            'iscompound' => '',
            'default_value' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
