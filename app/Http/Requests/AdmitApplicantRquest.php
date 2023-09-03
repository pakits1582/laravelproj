<?php

namespace App\Http\Requests;

use App\Rules\IdnoRule;
use Illuminate\Foundation\Http\FormRequest;

class AdmitApplicantRquest extends FormRequest
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
            'student' => 'bail|required|numeric|exists:students,id',
            'idno' => ['bail','required', 'numeric',  new IdnoRule, 'unique:users,idno'],
            'program_id' => 'bail|required|numeric|exists:programs,id',
            'curriculum_id' => 'bail|required|numeric|exists:curricula,id',
            'documents_submitted' => 'required|array|min:1|exists:admission_documents,id',
            'contact_no' => 'bail|required|string|min:11|max:20',
            'contact_email' => 'bail|required|email|max:150',
        ];
    }
}
