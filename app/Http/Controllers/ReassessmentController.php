<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Services\ReassessmentService;

class ReassessmentController extends Controller
{
    protected $reassessmentService;

    public function __construct(ReassessmentService $reassessmentService)
    {
        $this->reassessmentService = $reassessmentService;

        Helpers::setLoad(['jquery_reassessment.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);

        $enrolled_students = $this->reassessmentService->enrolledstudents(session('current_period'));
        
        return view('reassessment.index', compact('periods', 'programs', 'enrolled_students'));
    }

    public function filterenrolled(Request $request)
    {
        $enrolled_students = $this->reassessmentService->enrolledstudents($request->period_id, $request->educational_level, $request->college, $request->program_id, $request->year_level);

        return response()->json(['enrolled_count' => count($enrolled_students) ?? 0]); 
    }

    public function reassess(Request $request)
    {
        $reassess = $this->reassessmentService->reassessEnrollments($request->period_id, $request->educational_level, $request->college, $request->program_id, $request->year_level);

        return $reassess;

        //return response()->json(['enrolled_count' => count($enrolled_students) ?? 0]); 

    }


}
