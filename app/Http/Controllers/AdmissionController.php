<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Services\AdmissionService;
use App\Services\ApplicationService;

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
        dd($application);
    }
}
