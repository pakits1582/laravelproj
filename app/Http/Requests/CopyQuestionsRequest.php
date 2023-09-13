<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CopyQuestionsRequest extends FormRequest
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
            'copy_from' => 'required|integer|exists:educational_levels,id',
            'copy_to' => 'required|integer|exists:educational_levels,id|different:copy_from'
        ];
    }
}
