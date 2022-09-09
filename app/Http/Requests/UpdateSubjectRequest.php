<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
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
            'code' => ['required',  Rule::unique('subjects')->where(fn ($query) => $query->where('name', $this->name)
                                                                                        ->where('units', $this->name)
            )->ignore($this->subject->id)],
            'name' => ['required',  Rule::unique('subjects')->where(fn ($query) => $query->where('code', $this->code)
            ->where('units', $this->name)
            )->ignore($this->subject->id)],
            'units' => ['required',  Rule::unique('subjects')->where(fn ($query) => $query->where('code', $this->code)
                ->where('name', $this->name)
            )->ignore($this->subject->id)],
            'educational_level_id' => 'required',
            'college_id' => '',
            'tfunits' => [],
            'lecunits' => [],
            'labunits' => [],
            'hours' => [],
            'loadunits' => [],
            'professional' => [],
            'laboratory' => [],
            'gwa' => [],
            'nograde' => [],
            'notuition' => [],
            'exclusive' => [],
        ];
    }
}
