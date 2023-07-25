<?php

namespace App\Services;

use App\Models\User;

class ApplicationService
{
   
    public function applicants()
    {
        return [];

    }

    public function saveApplication($validatedData)
    {
        $student = $this->insertStudent($validatedData);

        return $student;
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
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return $studentData;
    }

    public function studentAcademicInformation()
    {

    }

    public function studentContactInformation()
    {

    }

    public function studentPersonalInformation()
    {
        
    }

    public function processPicture($file)
    {

    }

    public function processReportCard($file)
    {

    }

    

}