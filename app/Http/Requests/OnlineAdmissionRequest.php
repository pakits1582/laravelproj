<?php

namespace App\Http\Requests;

use App\Models\Student;
use App\Models\AdmissionDocument;
use Illuminate\Foundation\Http\FormRequest;

class OnlineAdmissionRequest extends FormRequest
{

    protected $applicant;
    protected $documents;

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
        $rules = [];

        $rules['contact_no'] = 'bail|required|string|min:11|max:20';
        $rules['contact_email'] = 'bail|required|email|max:150';
        $rules['application_no'] = 'bail|exists:students,application_no|required|numeric|min_digits:10|max_digits:255';

        $this->applicant = Student::with('program')->where('application_no', $this->input('application_no'))->firstOrFail();
        $this->documents = AdmissionDocument::where('educational_level_id', $this->applicant->program->educational_level_id)
            ->where('display', 1)
            ->get();

        if ($this->documents->isNotEmpty()) {
            foreach ($this->documents as $document) {
                $name = strtolower(str_replace(' ', '_', $document->description));
                $requiredRule = ($document->is_required == 1) ? 'required|array|max:5' : 'nullable|array|max:5';

                $rules[$name] = $requiredRule;
                $rules[$name.'.*'] = 'file|mimes:jpeg,png,jpg,gif,pdf|max:1024';
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        if ($this->documents->isNotEmpty()) {
            foreach ($this->documents as $document) {
                $name = strtolower(str_replace(' ', '_', $document->description));
                $messages[$name.'.*.max'] = 'One of the file uploaded is greater than 1mb.';
            }
        }

        $messages['application_no.required'] = 'The application number is required.';
        $messages['application_no.numeric'] = 'The application number must be numeric.';
        $messages['application_no.min_digits'] = 'The application number must have at least 10 digits.';
        $messages['application_no.max_digits'] = 'The application number cannot exceed 255 digits.';

        return $messages;
    }

}
