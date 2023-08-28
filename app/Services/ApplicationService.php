<?php

namespace App\Services;

use App\Traits\Upload;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentContactInformationModel;
use App\Models\StudentAcademicInformationModel;
use App\Models\StudentPersonalInformationModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApplicationService
{
    use Upload;//add this trait
   
    public function applicantList($request, $limit = 10,  $all = false, $application_status = Student::APPLI_NEW)
    {
        $query = Student::with(['program' => ['level', 'collegeinfo']])->orderBy('entry_date', 'DESC')->orderBy('last_name');

        $period = $request->period_id ?? session('current_period');

        $query->where('entry_period', $period)->where('application_status', $application_status);
        
        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('last_name', 'like', $request->keyword.'%')
                ->orWhere('first_name', 'like', $request->keyword.'%')
                ->orWhere('middle_name', 'like', $request->keyword.'%');
            });
        }

        $query->when(isset($request->program_id) && !empty($request->program_id), function ($query) use($request) {
            $query->where('program_id', $request->program_id);
        });

        $query->when(isset($request->educational_level) && !empty($request->educational_level), function ($query) use($request) {
            $query->whereHas('program.level', function($query) use($request){
                $query->where('educational_levels.id', $request->educational_level);
            });
        });

        if($all){
            return $query->get();
        }
    
        return $query->paginate($limit);
    }

    public function saveApplication($request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $idno = $validatedData['idno'];

            $query = Student::with(['user']);

            $query->when($idno , function ($query) use ($idno) 
            {
                $query->whereHas('user', function($query) use($idno){
                    $query->where('idno', $idno);
                });
            });

            $studentinfo =  $query->first();
            
            if(!$request->filled('idno')) 
            {
                $checkResult = $this->checkIfNameExists($validatedData);
                if($checkResult)
                {
                    return $checkResult;
                }
                $student = $this->insertStudent($validatedData);
            }else{
                $checkResult = $this->checkIfNameAndIdnoMatch($studentinfo, $validatedData);
                if($checkResult) 
                {
                    return $checkResult;
                }
                $student = $this->updateStudent($studentinfo, $validatedData);
            }

            $this->studentPersonalInformation($student,$validatedData);
            $this->studentContactInformation($student,$validatedData);
            $this->studentAcademicInformation($student,$validatedData);

            $picture = $this->processPicture($request);
            $report_card = $this->processReportCard($request);
            $student->picture = $picture;
            $student->report_card = $report_card;
            $student->save();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Thank you for applying to Saint Louis College. Your information and credentials will be evaluated and you will receive a notice of your status of application from the School\'s Guidance Center within three(3) working days.',
                'alert' => 'alert-success',
                'status' => 200
            ];

        } catch (\Exception $e) {
        
            return [
                'success' => false,
                'message' => 'Something went wrong! Can not perform requested action!',
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }  
    }

    private function  checkIfNameExists($validatedData, $id = null)
    {
        $query = Student::query();
        
        $query->when($validatedData , function ($query) use ($validatedData) 
        {
            $query-> where('last_name', $validatedData['last_name'])
            ->where('first_name', $validatedData['first_name'])
            ->where('middle_name', $validatedData['middle_name'])
            ->where('name_suffix', $validatedData['name_suffix']);
        });

        $query->when(!is_null($id), function ($query) use ($id) 
        {
            $query->where('id', '!=', $id);
        });

        $applicant =  $query->first();

        if($applicant)
        {
            switch ($applicant->application_status) {
                case '1':
                    $status = 'on process.';
                    break;
                case '2':
                    $status = 'accepted.';
                    break;
                case '3':
                    $status = 'rejected';
                    break;
                default:
                    $status = '';
                    break;
            }

            return [
                'success' => true,
                'message' => 'Application with the same name already exists and already '.$status,
                'alert' => 'alert-warning',
                'status' => 401
            ];
        }

        return false;
    }

    private function checkIfNameAndIdnoMatch($student, $validatedData)
    {
        if($student)
        {
           if(($student->last_name !== $validatedData['last_name'] 
            || $student->first_name !== $validatedData['first_name'] 
            || $student->middle_name !== $validatedData['middle_name']
            || $student->name_suffix !== $validatedData['name_suffix'])
            && $student->sex == Student::SEX_MALE
            ){
                return [
                    'success' => false,
                    'message' => 'The name under the ID number provided does not match the name in the application form!',
                    'alert' => 'alert-danger',
                    'status' => 401
                ];
           }
        }

        return false;
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

        return Student::Create($studentData);
    }

    public function updateStudent($student, $data)
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

        $student->update($studentData);

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

            'contact_no'    => $data['contact_no'], 
            'contact_email' => $data['contact_email'], 
        ];

        StudentContactInformationModel::updateOrCreate(['student_id' => $student->id], $contactInformations);

    }

    public function processPicture($request)
    {
        if($request->hasfile('picture'))
        {
            $filename = time().rand(1,50);
            $path = $this->UploadFile($request->file('picture'), 'image_uploads', 'public', $filename);//use the method in the trait

            return $path;
        }
    }

    public function processReportCard($request)
    {
        $files = [];
        if($request->hasfile('report_card'))
        {
            foreach($request->file('report_card') as $file)
            {
                $filename = time().rand(1,50);
                $path = $this->UploadFile($file, 'report_cards', 'public', $filename);
                $files[] = $path;  
            }
        }

        return implode(',', $files);
    }

    public function applicationAction($action, $application_no, $application)
    {
        if ($action === 'delete') {
            return $this->deleteApplication($application);
        }
        
        $status = null;
        if ($action === 'accept') {
            $status = Student::APPLI_ACCEPTED;
        } elseif ($action === 'reject') {
            $status = Student::APPLI_REJECTED;
        }
        
        if ($status !== null) {
            return $this->updateApplicationStatus($application, $application_no, $status);
        }
    }

    private function deleteApplication($application)
    {
        $this->removePicture($application);
        $this->removeReportCards($application);
        $application->delete();

        return [
            'success' => true,
            'message' => 'Application successfully deleted!',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    private function removePicture($application)
    {
        if(!is_null($application->picture))
        {
            $this->deleteFile($application->picture);
        }
    }

    private function removeReportCards($application)
    {
        if(!is_null($application->report_card))
        {
            $reportcards = explode(',', $application->report_card);

            foreach ($reportcards as $reportcard) 
            {
                $this->deleteFile($reportcard);
            }
        }
    }

    private function updateApplicationStatus($application, $application_no, $status)
    {
        $application->application_no = $application_no;
        $application->application_status = $status;
        $application->assessed_by = Auth::id();
        $application->assessed_date = now();
        
        $application->save();

        return [
            'success' => true,
            'message' => 'Application action successfully executed!',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    public function updateApplicant($request)
    {
        $validatedData = $request->validated();

        try 
        {
            DB::beginTransaction();

            $student = Student::findOrFail($request->student_applicant);
            
            if(!$request->filled('idno')) 
            {
                $checkResult = $this->checkIfNameExists($validatedData, $student->id);
                if($checkResult)
                {
                    return $checkResult;
                }
            }else{
                $checkResult = $this->checkIfNameAndIdnoMatch($student, $validatedData);
                if($checkResult) 
                {
                    return $checkResult;
                }
            }

            $student = $this->updateStudent($student, $validatedData);
            $this->studentPersonalInformation($student,$validatedData);
            $this->studentContactInformation($student,$validatedData);
            $this->studentAcademicInformation($student,$validatedData);

            if($request->hasfile('picture'))
            {
                $this->removePicture($student);
                $picture = $this->processPicture($request);
                $student->picture = $picture;
            }

            if($request->hasfile('report_card'))
            {
                $this->removeReportCards($student);
                $report_card = $this->processReportCard($request);
                $student->report_card = $report_card;
            }

            $student->save();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Application successfully updated!',
                'alert' => 'alert-success',
                'status' => 200
            ];

        } catch (ModelNotFoundException $exception) {
            return [
                'success' => false,
                'message' => 'Student application not found!',
                'alert' => 'alert-danger',
                'status' => 404
            ];
        }
    }
}