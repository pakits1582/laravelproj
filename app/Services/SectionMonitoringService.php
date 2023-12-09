<?php

namespace App\Services;

use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class SectionMonitoringService
{
    public function sectionmonitoring($period_id, $program_id = '', $year_level = '', $status = '')
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

        $query->when(isset($year_level) && !empty($year_level), function ($query) use ($year_level) {
            $query->where('sections.year', $year_level);
        });

        $query->when(isset($status) && !empty($status), function ($query) use ($status) {
            $query->where('section_monitorings.status', $status);
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

    public function updateMonitoring($request, $sectionmonitoring)
    {
        $validator =  Validator::make($request->all(), [
            'minimum_enrollees' => 'sometimes|required|integer',
            'allowed_units' => 'sometimes|required|integer',
            'status' => 'sometimes|required|integer',
        ]);
    
        if ($validator->fails()) 
        {
            return [
                'success' => false,
                'message' => 'Field should be integer value!',
                'alert' => 'alert-danger'
            ];
        }
        
        $validatedData = $validator->validated();
        $sectionmonitoring->update($validatedData);

        return [
            'success' => true,
            'message' => 'Field successfully updated!',
            'alert' => 'alert-success'
        ];
    }
}