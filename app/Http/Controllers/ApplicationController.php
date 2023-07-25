<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\Configuration;
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

        $applicants = $this->applicationService->applicants(session('current_period'));
        
        return view('application.index', compact('periods', 'programs', 'applicants'));
    }

    public function onlineapplication()
    {
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $configuration = Configuration::take(1)->first();
        $regions = json_decode(File::get(public_path('json/region.json')), true);

        return view('application.online_application', compact('programs', 'configuration', 'regions'));
    }

    public function store(StoreApplicationRequest $request)
    {
        $application = $this->applicationService->saveApplication($request);
        
        return response()->json($request);
    }


}
