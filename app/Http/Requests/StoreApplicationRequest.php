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

            'last_name' => 'required|string|min:2|max:255',
            'first_name' => 'required|string|min:2|max:255',
            'middle_name' => 'nullable|string|min:3|max:255',
            'name_suffix' => 'nullable',
            'sex' => 'required',
            'civil_status' => 'required',
            'birth_date' => 'required|date|date_format:Y-m-d',
            'birth_place' => 'nullable|string|min:2|max:255',
            'nationality' => 'required',
            'religion' => 'required',
            'religion_specify' => 'nullable|string|min:2|max:150',
            'baptism' => 'integer',
            'communion' => 'integer',
            'confirmation' => 'integer',

            // 'current_region' => '01',
            // 'current_province' => '0133',
            // 'current_municipality' => '013314',
            // 'current_barangay' => '013314006',
            // 'current_address' => '27 m. marcos rd. ext.',
            // 'current_zipcode' => '2500',
            // 'permanent_region' => '01',
            // 'permanent_province' => '0129',
            // 'permanent_municipality' => '012902',
            // 'permanent_barangay' => '012902002',
            // 'permanent_address' => '12 sadas ss',
            // 'permanent_zipcode' => '12312',
            // 'telno' => null,
            // 'mobileno' => '24234234234243',
            // 'email' => 'adsas@email.com',

            // 'elem_school' => null,
            // 'elem_address' => null,
            // 'elem_period' => null,
            // 'jhs_school' => null,
            // 'jhs_address' => null,
            // 'jhs_period' => null,
            // 'shs_strand' => null,
            // 'shs_techvoc_specify' => null,
            // 'shs_school' => null,
            // 'shs_address' => null,
            // 'shs_period' => null,
            // 'college_program' => null,
            // 'college_school' => null,
            // 'college_address' => null,
            // 'college_period' => null,
            // 'gradute_program' => null,
            // 'graduate_school' => null,
            // 'graduate_address' => null,
            // 'graduate_period' => null,

            // 'father_alive' => '1',
            // 'father_name' => null,
            // 'father_contactno' => null,
            // 'father_address' => null,
            // 'mother_alive' => '1',
            // 'mother_name' => null,
            // 'mother_contactno' => null,
            // 'mother_address' => null,
            // 'guardian_name' => null,
            // 'guardian_relationship' => null,
            // 'guardian_contactno' => null,
            // 'guardian_address' => null,
            // 'guardian_occupation' => null,
            // 'guardian_employer' => null,
            // 'guardian_employer_address' => null,
            // 'occupation' => null,
            // 'employer' => null,
            // 'employer_address' => null,
            // 'employer_contact' => null,
            // 'occupation_years' => null,

            // 'picture' => 'certificate_jm_badua.jpg',
            // 'report_card' => '',
            // 'contact_email' => 'sample@email.com',
            // 'contact_no' => '12345678',
        ];
    }
}
