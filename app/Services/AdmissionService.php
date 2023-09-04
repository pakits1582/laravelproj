<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\Upload;
use App\Models\Student;
use App\Models\AdmissionDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdmissionService
{
    use Upload;//add this trait

    public function admissionDocuments()
    {
        $documents = AdmissionDocument::select('admission_documents.*', 'educational_levels.code AS educlevel', 'programs.code AS program')
        ->join('educational_levels', 'educational_levels.id', '=', 'admission_documents.educational_level_id')
        ->leftJoin('programs', 'programs.id', '=', 'admission_documents.program_id')
        ->orderBy('educational_levels.id')
        ->get();
        
        return $documents;
    }

    public function updateDocument($request, $document)
    {
        try {

            $duplicate = AdmissionDocument::where('educational_level_id', $request->educational_level_id)
                        ->where('program_id', $request->program_id)
                        ->where('classification', $request->classification)
                        ->where('description', $request->description)
                        ->where('id', '!=', $document->id)
                        ->first();
            if($duplicate)
            {
            return [
                'success' => false,
                'message' => 'Duplicate entry, admission document already exists!',
                'alert' => 'alert-danger'
            ];
            }

            $document->update($request->validated());

            return [
                'success' => true,
                'message' => 'Admission document successfully updated!',
                'alert' => 'alert-success'
            ];

        } catch (ModelNotFoundException $e) {
           
            return [
                'success' => false,
                'message' => 'Document not found',
            ];
        }
    }

    public function admitApplicant($request)
    {
        try {
            $student = Student::findOrfail($request->student);
            
            DB::beginTransaction();
            $validated = $request->validated();
            
            $student->update([
                'program_id' => $validated['program_id'],
                'curriculum_id' => $validated['curriculum_id'],
                'admission_status' => 1,
                'admission_date' => now(),
            ]);
    
            $user = User::create([
                'idno' => $request->idno,
                'password' => Hash::make('password'),
                'utype' => User::TYPE_STUDENT,
            ]);
    
            $accesses = (new StudentService)->returnStudentAccesses();
            $user->access()->saveMany($accesses);
            
            $this->insertDocumentSubmitted($student, $validated['documents_submitted']);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Student successfully admitted',
                'alert' => 'alert-success'
            ];

        } catch (ModelNotFoundException $e) {
           
            return [
                'success' => false,
                'message' => 'Student not found',
            ];
        }
    }

    public function insertDocumentSubmitted($student, $documents)
    {
        if($documents)
        {
            $insert_documents = [];
            foreach ($documents as $document_id) {
                $insert_documents[] = [
                    'student_id' => $student->id,
                    'admission_documents_id' => $document_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $student->submitted_documents()->insert($insert_documents);
        }
    }

    public function applicationInformation($application_no)
    {
        try {
            $applicant = Student::with('personal_info', 'contact_info', 'program')->where('application_no', $application_no)->firstOrfail();
            $document_reqs = AdmissionDocument::where('educational_level_id', $applicant->program->educational_level_id)->where('display', 1)->get();

            $documents = [];
            if($document_reqs)
            {
                foreach ($document_reqs as $key => $document) 
                {
                    $documents[] = [ 
                        'label' => ($document->is_required == 1) ? '* '.$document->description : $document->description,
                        'name' => strtolower(str_replace(' ', '_', $document->description)),
                        'is_required' => ($document->is_required == 1) ? 'required' : '',
                    ];
                }
            }
            return [
                'applicant' => [
                    'name' => $applicant->name,
                    'classification' => Student::STUDENT_CLASSIFICATION[$applicant->classification] ?? '',
                    'program' => $applicant->program->name,
                    'civil_status' => $applicant->personal_info->civil_status,
                    'birth_date' => Carbon::parse($applicant->personal_info->birth_date)->format('F d, Y'),
                    'birth_place' => $applicant->personal_info->birth_place,
                    'nationality' => $applicant->personal_info->nationality,
                    'sex' => Student::SEX[$applicant->sex] ?? '',
                    'email' => $applicant->contact_info->email,
                    'mobileno' => $applicant->contact_info->mobileno,
                    'contact_email' => $applicant->contact_info->contact_email,
                    'contact_no' => $applicant->contact_info->contact_no,
                    'picture' => $applicant->picture,
                ],
                'documents' => $documents
            ];

        } catch (ModelNotFoundException $e) {
           
            return [
                'success' => false,
                'message' => 'Sorry there is no application found using the application number provided!',
            ];
        }
    }

    public function saveOnlineAdmission($request)
    {
        try {
            $applicant = Student::with('documents_submitted', 'contact_info', 'program')->where('application_no', $request->application_no)->firstOrfail();
            $document_reqs = AdmissionDocument::where('educational_level_id', $applicant->program->educational_level_id)->where('display', 1)->get();

            $documents_submitted = [];

            if($document_reqs)
            {
                foreach ($document_reqs as $key => $document) 
                {
                    $name = strtolower(str_replace(' ', '_', $document->description));
                   
                    $filename = time().rand(1,50);
                    $path = $this->UploadFile($request->file($filename), 'documents_submitted', 'public', $filename);//use the method in the trait

                    return $path;
                }
            }


        } catch (ModelNotFoundException $e) {
           
            return [
                'success' => false,
                'message' => 'Sorry there is no application found using the application number provided!',
            ];
        }
    }
}