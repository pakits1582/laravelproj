<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigurationRequest extends FormRequest
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
            'name' => ['required'],
            'logo' => [],
            'contactno' => ['required'],
            'address' => ['required'],
            'accronym' => ['required'],
            'president' => ['required'],
            'pres_initials' => ['required'],
            'registrar' => ['required'],
            'reg_initials' => ['required'],
            'treasurer' => ['required'],
            'tres_initials' => ['required'],
            'balanceallowed' => ['required', 'regex:/^\d*(\.\d{1,2})?$/'],
            'due' => ['required', 'integer'],
            'note' => ['required'],
            'current_period' => ['required'],
            'application_period' => ['required'],
            'datefrom' => ['required', 'date'],
            'dateto' => ['required', 'date', 'after_or_equal:datefrom'],
            'status' => [],
            'announcement' => [],
            'pres_sig' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'reg_sig' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tres_sig' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
