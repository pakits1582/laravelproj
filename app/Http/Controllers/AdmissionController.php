<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Models\AdmissionDocument;
use App\Services\AdmissionService;
use App\Services\ApplicationService;
use App\Http\Requests\StoreAdmissionDocumentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdmissionController extends Controller
{
    protected $admissionService;

    public function __construct(AdmissionService $admissionService)
    {
        $this->admissionService = $admissionService;
        Helpers::setLoad(['jquery_admission.js', 'select2.full.min.js']);
    }

    public function index(Request $request)
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);

        $applicants = (new ApplicationService)->applicantList($request, 10, false, Student::APPLI_ACCEPTED);

        if($request->ajax()){
            return view('admission.return_applications', compact('applicants'));
        }

        // dd($applicants);
        return view('admission.index', compact('periods', 'programs', 'applicants'));
    }

    public function show(Student $application)
    {
        $applicant = $application;
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);

        return view('admission.admit_applicant', compact('applicant', 'programs'));
    }

    public function documents()
    {
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $documents = $this->admissionService->admissionDocuments();

        return view('admission.documents.index', compact('documents','programs'));
    }

    public function savedocument(StoreAdmissionDocumentRequest $request)
    {
        $insert = AdmissionDocument::firstOrCreate($request->validated(), $request->validated());

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Admission document successfully added!',
                'alert' => 'alert-success'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Duplicate entry, admission document already exists!',
            'alert' => 'alert-danger'
        ], 200);
    }

    public function returndocuments()
    {
        $documents = $this->admissionService->admissionDocuments();

        return view('admission.documents.return_documents', compact('documents'));
    }

    public function editdocument(AdmissionDocument $document)
    {
        try {
            return response()->json(['data' => $document]);

        } catch (ModelNotFoundException $e) {
           
            return response()->json([
                'success' => false,
                'message' => 'Document not found',
            ], 404);
        }
    }

    public function updatedocument(StoreAdmissionDocumentRequest $request, AdmissionDocument $document)
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
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate entry, admission document already exists!',
                    'alert' => 'alert-danger'
                ], 200);
            }

            $document->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Admission document successfully updated!',
                'alert' => 'alert-success'
            ], 200);

        } catch (ModelNotFoundException $e) {
           
            return response()->json([
                'success' => false,
                'message' => 'Document not found',
            ], 404);
        }
    }

    public function deletedocument(AdmissionDocument $document)
    {
        try {
            //CHECK FIRST IF DOCUMENT ALREADY IN USE

        } catch (ModelNotFoundException $e) {
           
            return response()->json([
                'success' => false,
                'message' => 'Document not found',
            ], 404);
        }
    }
}
