<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\AdmissionDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdmissionService
{
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
            //DB::commit();

            return [
                'success' => true,
                'message' => 'Student successfully admitted',
                'alert' => 'alert-success'
            ];

        } catch (ModelNotFoundException $e) {
           
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }
    }

    public function insertDocumentSubmitted($student, $documents)
    {
        if($documents)
        {
            
        }
    }
}