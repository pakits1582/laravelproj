<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeriodRequest extends FormRequest
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
            'code.unique' => 'A period with the following code, term and year already exists.',
            'term_id.unique' => 'A period with the following code, term and year already exists.',
            'year.unique' => 'A period with the following code, term and year already exists.',
            'term_id.required' => 'The term field is required.',

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
            'code' => ['required', Rule::unique('periods')->where(fn ($query) => $query
                                                                                ->where('term_id', $this->term_id)
                                                                                ->where('year', $this->year)
            )->ignore($this->period->id)],
            'term_id' => ['required', Rule::unique('periods')->where(fn ($query) => $query
                                                                                ->where('code', $this->code)
                                                                                ->where('year', $this->year)
            )->ignore($this->period->id)],
            'year' => ['required', Rule::unique('periods')->where(fn ($query) => $query
            ->where('code', $this->code)
            ->where('term_id', $this->term_id)
            )->ignore($this->period->id)],
            'name' => ['required'],
            'class_start' => ['required', 'date'],
            'class_end' => ['required', 'date'],
            'class_ext' => ['required', 'date'],
            'enroll_start' => ['required', 'date'],
            'enroll_end' => ['required', 'date'],
            'enroll_ext' => ['required', 'date'],
            'adddrop_start' => ['required', 'date'],
            'adddrop_end' => ['required', 'date'],
            'adddrop_ext' => ['required', 'date'],
            'idmask' => ['required'],
            'source' => ['required'],
            'display' => ['required'],
        ];
    }
}
