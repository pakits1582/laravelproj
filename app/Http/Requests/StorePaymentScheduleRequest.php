<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentScheduleRequest extends FormRequest
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
            'educational_level_id' => 'required',
            'year_level' => [],
            'payment_mode_id' => 'required',
            'description' => 'required',
            'tuition' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'miscellaneous'=> 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'others'       => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
            'payment_type' => 'required'
        ];
    }
}
