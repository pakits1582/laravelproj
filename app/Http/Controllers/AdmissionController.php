<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Models\AdmissionDocument;
use App\Services\AdmissionService;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AdmitApplicantRquest;
use App\Http\Requests\OnlineAdmissionRequest;
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

        return view('admission.index', compact('periods', 'programs', 'applicants'));
    }

    public function show(Student $application)
    {
        $applicant = $application->load('user', 'personal_info', 'contact_info', 'program.curricula');
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $documents = AdmissionDocument::where('educational_level_id', $applicant->program->educational_level_id)->where('display', 1)->get();

        return view('admission.admit_applicant', compact('applicant', 'programs', 'documents'));
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
        $update_document = $this->admissionService->updateDocument($request, $document);

        return response()->json($update_document);
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

    public function admitapplicant(AdmitApplicantRquest $request)
    {
        $admit_applicant = $this->admissionService->admitApplicant($request);

        return response()->json($admit_applicant);
    }

    public function onlineadmission()
    {
        $configuration = Configuration::take(1)->first();

        return view('admission.online_admission', compact('configuration'));
    }

    public function getapplicantinfo(Request $request)
    {
        $rules = [
            'application_no' => 'bail|required|numeric|min_digits:10|max_digits:255',
        ];
    
        $messages = [
            'application_no.required' => 'The application number is required.',
            'application_no.numeric' => 'The application number must be numeric.',
            'application_no.min_digits' => 'The application number must have at least 10 digits.',
            'application_no.max_digits' => 'The application number cannot exceed 255 digits.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) 
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    

        $application_info = $this->admissionService->applicationInformation($request->input('application_no'));

        return response()->json($application_info);
    }

    public function saveonlineadmission(OnlineAdmissionRequest $request)
    {
        return $request;
    }
}
