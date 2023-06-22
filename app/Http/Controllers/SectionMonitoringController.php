<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SectionMonitoringController extends Controller
{

    public function __construct()
    {
        Helpers::setLoad(['jquery_sectionmonitoring.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        $section_monitorings = $this->sectionmonitoring(session('current_period'));
        $programs = $section_monitorings->unique('program_id');
        $grouped_sectiomonitorings = $this->groupsectionmonitoringbyprogram($section_monitorings);
        
        return view('section.monitoring.index', compact('grouped_sectiomonitorings', 'programs', 'periods'));
    }

    public function sectionmonitoring($period_id, $program_id = '')
    {
        $query = SectionMonitoring::query();
        $query->select(
            'section_monitorings.*',
            'sections.code as section_code',
            'sections.name as section_name',
            'sections.year as section_year',
            'programs.id as program_id',
            'programs.code as program_code',
            'programs.name as program_name',
            DB::raw('COUNT(enrollments.id) AS enrolled_count')
        );
        $query->join('sections', 'section_monitorings.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query ->leftJoin('enrollments', function ($join) use ($period_id) {
            $join->on('section_monitorings.section_id', '=', 'enrollments.section_id')
                ->where('enrollments.period_id', '=', $period_id);
        });
        $query->where('section_monitorings.period_id', $period_id);

        $query->when(isset($program_id) && !empty($program_id), function ($query) use ($program_id) {
            $query->where('programs.id', $program_id);
        });

        $query->groupBy('sections.id')
        ->orderBy('programs.code')
        ->orderBy('sections.year');

        $slot_monitoring = $query->get();

        return $slot_monitoring;
    }

    public function groupsectionmonitoringbyprogram($section_monitorings)
    {
        $grouped_sectiomonitoring = [];

        if($section_monitorings)
        {
            foreach ($section_monitorings as $section) 
            {
                if($section->program_id)
                {
                    $program_id = $section->program_id;
                    if (!isset($grouped_sectiomonitoring[$program_id])) 
                    {
                        $grouped_sectiomonitoring[$program_id] = [
                            'program_id'   => $program_id, 
                            'program_code' => $section->program_code,
                            'program_name' => $section->program_name,
                            'sections' => []
                        ];
                    }
                    $grouped_sectiomonitoring[$program_id]['sections'][] = 
                        [
                            'section_id' => $section->section_id,
                            'section_code' => $section->section_code,
                            'section_name' => $section->section_name,
                            'section_year' => $section->section_year,
                            'enrolled_count' => $section->enrolled_count,
                            'sectionmonitoring_id' => $section->id,
                            'minimum_enrollees' => $section->minimum_enrollees,
                            'sectionmonitoring_allowed_units' => $section->allowed_units,
                            'sectionmonitoring_status' => $section->status,
                        ];
                }
            }
        }

        return array_values($grouped_sectiomonitoring);
    }

    public function filtersectionmonitoring(Request $request)
    {
        $section_monitorings = $this->sectionmonitoring($request->period_id, $request->program_id);
        $grouped_sectiomonitorings = $this->groupsectionmonitoringbyprogram($section_monitorings);
        
        return view('section.monitoring.return_sectionmonitoring', compact('grouped_sectiomonitorings'));
    }

    public function update(Request $request, SectionMonitoring $sectionmonitoring)
    {
        $validator =  Validator::make($request->all(), [
            'minimum_enrollees' => 'sometimes|required|integer',
            'allowed_units' => 'sometimes|required|integer',
            'status' => 'sometimes|required|integer',
        ]);
    
        if ($validator->fails()) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Field should be integer value!',
                'alert' => 'alert-danger'
            ]);
        }
        
        $validatedData = $validator->validated();
        $sectionmonitoring->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Field successfully updated!',
            'alert' => 'alert-success'
        ]);
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

