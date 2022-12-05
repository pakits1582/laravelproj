<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRequest extends FormRequest
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
            'units.numeric' => 'Must be a number.',
            'tfunits.numeric' =>  'Must be a number.',
            'loadunits.numeric' =>  'Must be a number.',
            'lecunits.numeric' =>  'Must be a number.',
            'labunits.numeric' =>  'Must be a number.',
            'hours.numeric' =>  'Must be a number.',
            'slots.integer' => 'Must be a number.',
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
            'units' => 'numeric',
            'tfunits' =>  'numeric',
            'loadunits' =>  'numeric',
            'lecunits' =>  'numeric',
            'labunits' =>  'numeric',
            'hours' =>  'numeric',
            'slots' => 'sometimes|integer|nullable',
            'tutorial' => [],
            'dissolved' => [],
            'f2f' => [],
            'isprof' => [],
            'instructor_id' => [],
            'schedule' => ''
        ];
    }
}
