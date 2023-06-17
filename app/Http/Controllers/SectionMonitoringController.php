<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;

class SectionMonitoringController extends Controller
{
    public function index()
    {
        $section_monitorings = $this->sectionmonitoring(session('current_period'));
        $grouped_sectiomonitorings = $this->groupsectionmonitoringbyprogram($section_monitorings);
        
        return view('section.monitoring.index', compact('grouped_sectiomonitorings'));
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
        $query->leftJoin('enrollments', 'section_monitorings.section_id', '=', 'enrollments.section_id');

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
}
