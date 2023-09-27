<?php

namespace App\Http\Requests;

use App\Rules\AllChoicesSelected;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationAnswerRequest extends FormRequest
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
            'choice.*.required' => 'This question must have a choice selected.',
            'choice.*.in' => 'This question must have a choice selected.',
            'overallrate.required' => 'The over all rate of instructor is required.',
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
            'faculty_evaluation_id' => 'bail|required|numeric|exists:faculty_evaluations,id',
            'question_id' => 'required|array',
            'question_id.*' => 'required|numeric|exists:questions,id',
            'choice' => ['required','array'],
            'choice.*' => 'required|in:1,2,3,4',
            'overallrate' => 'required|in:1,2,3,4',
            'strongpoint' => 'nullable|string|min:2|max:255',
            'weakpoint' => 'nullable|string|min:2|max:255',
            'suggestion' => 'nullable|string|min:2|max:255',
            'studentservices' => 'nullable|string|min:2|max:255',
        ];
    }
}
