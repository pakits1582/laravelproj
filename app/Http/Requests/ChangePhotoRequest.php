<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePhotoRequest extends FormRequest
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
            'picture.max' => 'The picture must not be greater than 1mb.',
            'picture.mimes' => 'The picture must be a file of type: jpeg, png, jpg, gif.',
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
            'id' => ['required','exists:students,id'],
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
        ];
    }
}
