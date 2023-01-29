<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSetupFeeRequest extends FormRequest
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
            'college_id' => [],
            'program_id' => [],
            'subject_id' => [],
            'fee_id' => 'required',
            'year_level' => [],
            'sex' => [],
            'new'          => [],
            'old'          => [],
            'returnee'      => [],
            'transferee'   => [],
            'cross_enrollee' => [],
            'professional' => [],
            'foreigner' => [],
            'payment_scheme' => 'required',
            'rate' => 'required|regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
