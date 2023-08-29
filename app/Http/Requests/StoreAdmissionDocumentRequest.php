<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmissionDocumentRequest extends FormRequest
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
            'educational_level_id.required' => 'The educational level field is required.',
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
            'educational_level_id' => 'required|integer', 
            'program_id' => 'nullable|integer', 
            'classification' => 'nullable|integer', 
            'description' => 'required|string|min:2|max:255', 
            'display' => 'nullable|boolean', 
            'is_required' => 'nullable|boolean'
        ];
    }
}
