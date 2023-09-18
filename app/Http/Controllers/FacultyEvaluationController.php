<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\FacultyEvaluationService;
use App\Services\SlotMonitoringService;
use Illuminate\Support\Facades\Auth;

class FacultyEvaluationController extends Controller
{
    protected $facultyEvaluationService;

    public function __construct(FacultyEvaluationService $facultyEvaluationService)
    {
        $this->facultyEvaluationService = $facultyEvaluationService;
        Helpers::setLoad(['jquery_facultyevaluation.js', 'select2.full.min.js']);
    }

    public function index(Request $request)
    {
        $classes = $this->facultyEvaluationService->classesForEvaluation(Auth::user(), $request);
        $classeswithslots = (new SlotMonitoringService)->getClassesSlots($classes);
        
        if($request->ajax())
        {
            return view('facultyevaluation.return_evaluations', compact('classeswithslots'));
        }

        return view('facultyevaluation.index', compact('classeswithslots'));
    }



}
