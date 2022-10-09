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
            'slots' => 'integer',
            'tutorial' => [],
            'dissolved' => [],
            'f2f' => [],
            'instructor_id' => [],
            'schedule' => ''
        ];
    }
}
