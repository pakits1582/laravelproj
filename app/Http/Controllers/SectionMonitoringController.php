<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Models\SectionMonitoring;
use App\Services\SectionMonitoringService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SectionMonitoringController extends Controller
{
    protected $sectionMonitoringService;

    public function __construct(SectionMonitoringService $sectionMonitoringService)
    {
        $this->sectionMonitoringService = $sectionMonitoringService;
        Helpers::setLoad(['jquery_sectionmonitoring.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        $section_monitorings = $this->sectionMonitoringService->sectionmonitoring(session('current_period'));
        $programs = $section_monitorings->unique('program_id');
        $grouped_sectiomonitorings = $this->sectionMonitoringService->groupsectionmonitoringbyprogram($section_monitorings);
        
        return view('section.monitoring.index', compact('grouped_sectiomonitorings', 'programs', 'periods'));
    }

    public function filtersectionmonitoring(Request $request)
    {
        $section_monitorings = $this->sectionMonitoringService->sectionmonitoring($request->period_id, $request->program_id);
        $grouped_sectiomonitorings = $this->sectionMonitoringService->groupsectionmonitoringbyprogram($section_monitorings);
        
        return view('section.monitoring.return_sectionmonitoring', compact('grouped_sectiomonitorings'));
    }

    public function update(Request $request, SectionMonitoring $sectionmonitoring)
    {
        $update = $this->sectionMonitoringService->updateMonitoring($request, $sectionmonitoring);

        return response()->json($update);
    }

    public function viewenrolledinsection(Section $section)
    {
        $period_id = session('current_period');

        $section->load(['enrollments' => function ($query) use ($period_id) {
                $query->where('period_id', $period_id)
                    ->with([
                        'student', 
                        'student.user:id,idno',
                        'program'
                    ]);
            },
            'programinfo'
        ]);

        return view('section.monitoring.viewenrolledinsection', compact('section'));    
    }

}

