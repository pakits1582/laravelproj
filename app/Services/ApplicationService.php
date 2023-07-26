<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentAcademicInformationModel;
use App\Models\StudentContactInformationModel;
use App\Models\StudentPersonalInformationModel;
use Illuminate\Support\Facades\DB;

class ApplicationService
{
   
    public function applicants()
    {
        return [];

    }

    public function saveApplication($validatedData)
    {
        DB::beginTransaction();
        try {
          
            $student = $this->insertStudent($validatedData);
                       $this->studentPersonalInformation($student,$validatedData);
                       $this->studentContactInformation($student,$validatedData);
                       $this->studentAcademicInformation($student,$validatedData);

            return [
                'success' => true,
                'message' => 'Selected unpaid enrollments successfully deleted!',
                'alert' => 'alert-success',
                'status' => 200
            ];

        } catch (\Exception $e) {
        
            return [
                'success' => false,
                'message' => 'Something went wrong! Can not perform requested action!'.$e->getMessage(),
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }
                  
        //DB::commit();

    }

    public function insertUser($data)
    {

    }

    public function insertStudent($data)
    {
        $studentData = [
            'last_name' => $data['last_name'], 
            'first_name' => $data['first_name'], 
            'middle_name' => $data['middle_name'], 
            'name_suffix' => $data['name_suffix'],  
            'sex' => $data['sex'],  
            'program_id' => $data['program_id'], 
            'year_level' => 1,
            'classification' => $data['classification'], 
            'application_status' => 1,
            'entry_period' => $data['entry_period'], 
            'entry_date' => now(), 
        ];

        $student = Student::updateOrCreate([
            'last_name' => $data['last_name'], 
            'first_name' => $data['first_name'], 
            'middle_name' => $data['middle_name'], 
            'name_suffix' => $data['name_suffix']  
        ], $studentData);

        return $student;
    }

    public function studentPersonalInformation($student, $data)
    {
        $studentPersonalData = [
            'student_id'       => $student->id,
            'civil_status'     => $data['civil_status'], 
            'birth_date'       => $data['birth_date'], 
            'birth_place'      => $data['birth_place'], 
            'nationality'      => $data['nationality'], 
            'religion'         => $data['religion'], 
            'religion_specify' => $data['religion_specify'], 
            'baptism'          => $data['baptism'], 
            'communion'        => $data['communion'], 
            'confirmation'     => $data['confirmation'], 

            'father_alive'     => $data['father_alive'], 
            'father_name'      => $data['father_name'], 
            'father_contactno' => $data['father_contactno'], 
            'father_address'   => $data['father_address'], 

            'mother_alive'     => $data['mother_alive'], 
            'mother_name'      => $data['mother_name'], 
            'mother_contactno' => $data['mother_contactno'], 
            'mother_address'   => $data['mother_address'], 

            'guardian_name'             => $data['guardian_name'], 
            'guardian_relationship'     => $data['guardian_relationship'], 
            'guardian_contactno'        => $data['guardian_contactno'], 
            'guardian_address'          => $data['guardian_address'], 
            'guardian_occupation'       => $data['guardian_occupation'], 
            'guardian_employer'         => $data['guardian_employer'], 
            'guardian_employer_address' => $data['guardian_employer_address'], 

            'occupation'       => $data['occupation'], 
            'employer'         => $data['employer'], 
            'employer_address' => $data['employer_address'], 
            'employer_contact' => $data['employer_contact'], 
            'occupation_years' => $data['occupation_years'] ?? 0, 
        ];

        StudentPersonalInformationModel::updateOrCreate(['student_id' => $student->id], $studentPersonalData);
    }

    public function studentAcademicInformation($student, $data)
    {
        $academicInformation = [
            'student_id' => $student->id,
            'elem_school'  => $data['elem_school'],
            'elem_address' => $data['elem_address'],
            'elem_period'  => $data['elem_period'],

            'jhs_school'  => $data['jhs_school'],
            'jhs_address' => $data['jhs_address'],
            'jhs_period'  => $data['jhs_period'],

          
            'shs_school'          => $data['shs_school'],
            'shs_address'         => $data['shs_address'],
            'shs_period'          => $data['shs_period'],
            'shs_strand'          => $data['shs_strand'],
            'shs_techvoc_specify' => $data['shs_techvoc_specify'],

            'college_program' => $data['college_program'],
            'college_school'  => $data['college_school'],
            'college_address' => $data['college_address'],
            'college_period'  => $data['college_period'],

            'graduate_program' => $data['graduate_program'],
            'graduate_school'  => $data['graduate_school'],
            'graduate_address' => $data['graduate_address'],
            'graduate_period'  => $data['graduate_period'],
        ];

        StudentAcademicInformationModel::updateOrCreate(['student_id' => $student->id], $academicInformation);
    }

    public function studentContactInformation($student, $data)
    {
        $contactInformations = [
            'student_id' => $student->id,
            'current_region'       => $data['current_region'], 
            'current_province'     => $data['current_province'], 
            'current_municipality' => $data['current_municipality'], 
            'current_barangay'     => $data['current_barangay'], 
            'current_address'      => $data['current_address'], 
            'current_zipcode'      => $data['current_zipcode'], 

            'permanent_region'       => $data['permanent_region'], 
            'permanent_province'     => $data['permanent_province'], 
            'permanent_municipality' => $data['permanent_municipality'], 
            'permanent_barangay'     => $data['permanent_barangay'], 
            'permanent_address'      => $data['permanent_address'], 
            'permanent_zipcode'      => $data['permanent_zipcode'], 

            'telno'    => $data['telno'], 
            'mobileno' => $data['mobileno'], 
            'email'    => $data['email'], 

            'contact_no' => $data['contact_no'], 
            'contact_email' => $data['contact_email'], 
        ];

        StudentContactInformationModel::updateOrCreate(['student_id' => $student->id], $contactInformations);

    }

    

    public function processPicture($file)
    {

    }

    public function processReportCard($file)
    {

    }

    

}