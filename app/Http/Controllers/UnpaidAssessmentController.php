<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ProgramService;

class UnpaidAssessmentController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_unpaidassessment.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);

        $unpaid_assessments = $this->unpaidassessments(session('current_period'));
        
        return view('assessment.unpaid.index', compact('periods', 'programs', 'unpaid_assessments'));
    }

    public function unpaidassessments($period_id, $educational_level_id = '', $college_id = '', $program_id = '', $year_level = '')
    {
        $query = Enrollment::with([
            'student', 
            'student.user:id,idno',
            'program',
            'program.level',
            'section:id,code',
            'enrolledby:id,idno'
        ])->where('period_id', $period_id)->where('assessed', 1)->where('validated', 0)->withCount('enrolled_classes');

        $query->when(isset($program_id) && !empty($program_id), function ($query) use($program_id) {
            $query->where('program_id', $program_id);
        });

        $query->when(isset($year_level) && !empty($year_level), function ($query) use($year_level) {
            $query->where('year_level', $year_level);
        });

        $query->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use($educational_level_id) {
            $query->whereHas('program.level', function($query) use($educational_level_id){
                $query->where('educational_level_id', $educational_level_id);
            });
        });

        $query->when(isset($college_id) && !empty($college_id), function ($query) use($college_id) {
            $query->whereHas('program.level', function($query) use($college_id){
                $query->where('college_id', $college_id);
            });
        });

        return $query->get();
    }

    public function filterunpaidassessments(Request $request)
    {
        $unpaid_assessments = $this->unpaidassessments($request->period_id, $request->educational_level, $request->college, $request->program_id, $request->year_level);
        
        return view('assessment.unpaid.return_unpaid', compact('unpaid_assessments'));
    }

    public function deleteunpaidassessments(Request $request)
    {
        try {
          
            $enrollments = Enrollment::whereIn("id", $request->enrollment_ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Selected unpaid enrollments successfully deleted!',
                'alert' => 'alert-success',
                'status' => 200
            ]);

        } catch (\Exception $e) {
        
            return [
                'success' => false,
                'message' => 'Something went wrong! Can not perform requested action!',
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }
    }
}
