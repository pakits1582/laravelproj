<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
            'code' => ['required',  Rule::unique('departments')->where(fn ($query) => $query->where('name', $this->name))->ignore($this->room->id)],
            'name' => ['required',  Rule::unique('departments')->where(fn ($query) => $query->where('code', $this->code))->ignore($this->room->id)],
            'capacity' => ['required', 'integer'],
            'excludechecking' => ''
        ];
    }
}
