<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiptRequest extends FormRequest
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

    public function rules()
    {
        return [
            'receipt_no' => 'required|digits_between:1,20|unique:receipts,receipt_no',
            'receipt_date' => 'required|date',
            'payor_name' => 'required',
            'period_id' => 'required',
            'fees' => ['required', 'array', 'min:1'],
            'total_amount' => ['required', 'regex:/^\d{1,3}(,\d{3})*(\.\d+)?$/', 'min:0.01'],
            'bank_id' => 'nullable',
            'check_no' => 'required_with:bank_id',
            'deposit_date' => 'nullable|required_with:bank_id|date',
        ];
    }

    public function messages()
    {
        return [
            'check_no.required_with' => 'The check number field is required when the bank field is not empty.',
            'deposit_date.required_with' => 'The deposit date field is required when the bank field is not empty.',
            'deposit_date.date' => 'The deposit date field must be a valid date format.',
        ];
    }
}
