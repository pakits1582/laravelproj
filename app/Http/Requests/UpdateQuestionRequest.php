<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
            'educational_level_id.require' => 'The educational level field is required.',
            'question_category_id.required' => 'The question category field is required.',
            'question.unique' => 'A question with the following educational level, category, subcategory and group already exists.',

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
            'educational_level_id' => 'bail|required|numeric|exists:educational_levels,id',
            'question_category_id' => 'bail|required|numeric|exists:question_categories,id',
            'question_subcategory_id' => 'bail|nullable|numeric|exists:question_subcategories,id',
            'question_group_id' => 'bail|nullable|numeric|exists:question_groups,id',
            'question' => ['bail','required','string', Rule::unique('questions')->where(fn ($query) => $query
                    ->where('educational_level_id', $this->educational_level_id)
                    ->where('question_category_id', $this->question_category_id)
                    ->where('question_subcategory_id', $this->question_subcategory_id)
                    ->where('question_group_id', $this->question_group_id)
                    ->where('question', $this->question)
                )
                ->ignore($this->questioninfo->id)
            ],
        ];
    }
}
