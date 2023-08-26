<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\Student;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\File;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
        Helpers::setLoad(['jquery_application.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);

        $applicants = $this->applicationService->applicantList(session('current_period'));

        return view('application.index', compact('periods', 'programs', 'applicants'));
    }

    public function onlineapplication()
    {
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $configuration = Configuration::take(1)->first();
        $regions = json_decode(File::get(public_path('json/region.json')), true);
        $withperiod = false;

        return view('application.online_application', compact('programs', 'configuration', 'regions', 'withperiod'));
    }

    public function show(Student $application)
    {
        $applicant = $application->load(['assessedby', 'entryperiod','academic_info', 'contact_info', 'personal_info']);

        return view('application.view', compact('applicant'));
    }

    public function create()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $regions = json_decode(File::get(public_path('json/region.json')), true);
        $withperiod = true;

        return view('application.create', compact('periods','programs', 'regions', 'withperiod'));
    }

    public function store(StoreApplicationRequest $request)
    {
        $application = $this->applicationService->saveApplication($request);
        
        return response()->json($application);
    }

    public function edit(Student $application)
    {
        $applicant = $application->load(['user', 'entryperiod','academic_info', 'contact_info', 'personal_info']);
        $withperiod = true;

        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $regions = json_decode(File::get(public_path('json/region.json')), true);
        $provinces = json_decode(File::get(public_path('json/province.json')), true);
        $cities = json_decode(File::get(public_path('json/city.json')), true);
        $barangays = json_decode(File::get(public_path('json/barangay.json')), true);

        return view('application.edit', compact('periods', 'programs', 'regions', 'provinces', 'cities', 'barangays', 'applicant', 'withperiod'));
    }

    public function update(StoreApplicationRequest $request)
    {
        return response()->json($request->student_applicant);
    }

    public function applicationaction(Request $request, Student $application)
    {
        $action = $this->applicationService->applicationAction($request->action, $request->application_no, $application);

        return response()->json($action);
    }
}
