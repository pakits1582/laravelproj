<?php

namespace App\Http\Requests;

use App\Rules\IdnoRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
            'guardian_name.required_without_all' => 'This field is required when none of father nor mother fields are present.',
            'guardian_relationship.required_without_all' => 'This field is required when none of father nor mother fields are present.',
            'guardian_contactno.required_without_all' => 'This field is required when none of father nor mother fields are present.',
            'guardian_address.required_without_all' => 'This field is required when none of father nor mother fields are present.',
            
            'elem_address.required_with' => 'This field is required when school name field is present.',
            'elem_period.required_with' => 'This field is required when school name field is present.',
            
            'jhs_address.required_with' => 'This field is required when school name field is present.',
            'jhs_period.required_with' => 'This field is required when school name field is present.',

            'shs_strand.required_with' => 'This field is required when school name field is present.',
            'shs_period.required_with' => 'This field is required when school name field is present.',
            'shs_address.required_with' => 'This field is required when school name field is present.',

            'agree_terms.accepted' => 'Please accept terms and agreement by clicking the checkbox.',
            'picture.max' => 'The picture must not be greater than 1mb.'
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
            'idno' => ['nullable', 'numeric',  new IdnoRule],
            'classification' => 'required',
            'program_id' => 'required',

            'last_name' => 'bail|required|string|min:2|max:255',
            'first_name' => 'bail|required|string|min:2|max:255',
            'middle_name' => 'bail|nullable|string|min:3|max:255',
            'name_suffix' => 'nullable',
            'sex' => 'required',
            'civil_status' => 'required',
            'birth_date' => 'bail|required|date|date_format:Y-m-d',
            'birth_place' => 'bail|nullable|string|min:2|max:255',
            'nationality' => 'required',
            'religion' => 'required',
            'religion_specify' => 'bail|nullable|string|min:2|max:150',
            'baptism' => 'integer',
            'communion' => 'integer',
            'confirmation' => 'integer',

            'current_region' => 'required',
            'current_province' => 'required',
            'current_municipality' => 'required',
            'current_barangay' => 'required',
            'current_address' => 'bail|required|string|min:2|max:255',
            'current_zipcode' => 'bail|required|string|min:2|max:20',

            'permanent_region' => 'nullable',
            'permanent_province' => 'required_with:permanent_region',
            'permanent_municipality' => 'required_with:permanent_province',
            'permanent_barangay' => 'required_with:permanent_municipality',
            'permanent_address' => 'bail|required_with:permanent_barangay|nullable|string|min:2|max:255',
            'permanent_zipcode' => 'bail|required_with:permanent_barangay|nullable|string|min:2|max:20',
            'telno' => 'bail|nullable|string|min:4|max:20',
            'mobileno' => 'bail|required|string|min:11|max:20',
            'email' => 'bail|required|email|max:150',

            'elem_school' => 'bail|nullable|string|min:2|max:255',
            'elem_address' => 'bail|required_with:elem_school|nullable|string|min:2|max:255',
            'elem_period' => 'bail|required_with:elem_school|nullable|string|min:4|max:50',

            'jhs_school' => 'bail|nullable|string|min:2|max:255',
            'jhs_address' => 'bail|required_with:jhs_school|nullable|string|min:2|max:255',
            'jhs_period' => 'bail|required_with:jhs_school|nullable|string|min:4|max:50',

          
            'shs_school' => 'bail|nullable|string|min:2|max:255',
            'shs_address' => 'bail|required_with:shs_school|nullable|string|min:2|max:255',
            'shs_period' => 'bail|required_with:shs_school|nullable|string|min:4|max:50',
            'shs_strand' => 'bail|required_with:shs_school|nullable',
            'shs_techvoc_specify' => 'bail|required_with:shs_strand|nullable|string|min:2|max:50',

            'college_program' => 'bail|nullable|string|min:2|max:255',
            'college_school' => 'bail|required_with:college_program|nullable|string|min:2|max:255',
            'college_address' => 'bail|required_with:college_program|nullable|string|min:2|max:255',
            'college_period' => 'bail|required_with:college_program|nullable|string|min:4|max:50',

            'gradute_program' => 'bail|nullable|string|min:2|max:255',
            'graduate_school' => 'bail|required_with:gradute_program|nullable|string|min:2|max:255',
            'graduate_address' => 'bail|required_with:gradute_program|nullable|string|min:2|max:255',
            'graduate_period' => 'bail|required_with:gradute_program|nullable|string|min:4|max:50',

            'father_alive' => 'integer',
            'father_name' => 'bail|nullable|string|min:2|max:255',
            'father_contactno' => 'bail|nullable|string|min:4|max:30',
            'father_address' => 'bail|nullable|string|min:2|max:255',

            'mother_alive' => 'integer',
            'mother_name' => 'bail|nullable|string|min:2|max:255',
            'mother_contactno' => 'bail|nullable|string|min:4|max:30',
            'mother_address' => 'bail|nullable|string|min:2|max:255',

            'guardian_name' => 'bail|nullable|string|required_without_all:father_name,father_contactno,father_address,mother_name,mother_contactno,mother_address|min:2|max:255',
            'guardian_relationship' => 'bail|nullable|string|required_without_all:father_name,father_contactno,father_address,mother_name,mother_contactno,mother_address|min:2|max:100',
            'guardian_contactno' => 'bail|nullable|string|required_without_all:father_name,father_contactno,father_address,mother_name,mother_contactno,mother_address|min:4|max:30',
            'guardian_address' => 'bail|nullable|string|required_without_all:father_name,father_contactno,father_address,mother_name,mother_contactno,mother_address|min:2|max:255',
            'guardian_occupation' => 'bail|nullable|string|min:2|max:255',
            'guardian_employer' => 'bail|nullable|string|min:2|max:255',
            'guardian_employer_address' => 'bail|nullable|string|min:2|max:255',

            'occupation' => 'bail|nullable|string|min:2|max:255',
            'employer' => 'bail|nullable|string|min:2|max:255',
            'employer_address' => 'bail|nullable|string|min:2|max:255',
            'employer_contact' => 'bail|nullable|string|min:2|max:30',
            'occupation_years' => 'bail|nullable|integer|min:1|max:2',

            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
            'report_card' => 'required|array|max:5', // Max 5 files can be uploaded (you can adjust this number as needed)
            'report_card.*' => 'file|mimes:jpeg,png,jpg,gif,pdf|max:1024', // Each file should be an image (jpeg, png, jpg, gif) or PDF, max size 1024 KB (1MB)

            'contact_no' => 'bail|required|string|min:11|max:20',
            'contact_email' => 'bail|required|email|max:150',
            'agree_terms' => 'accepted'
        ];
    }
}
