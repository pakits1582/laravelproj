<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentadjustmentRequest extends FormRequest
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
            'period_id' => 'required',
            'student_id' => 'required',
            'type' => 'required',
            'created_at' => '',
            'particular' => 'required|max:255',
            'amount' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/'
        ];
    }
}
