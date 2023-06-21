<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use Illuminate\Support\Facades\DB;

class GeneralScheduleController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_generalschedule.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $generalschedules = $this->generalschedules(session('current_period'));
        $gensched_classes = $this->getGeneralSchedules($generalschedules);

        return view('generalschedule.index', compact('periods', 'gensched_classes'));
    }

    public function generalschedules($period_id, $educational_level_id = '', $college_id = '', $display = '')
    {
        $query = Classes::query();
        $query->select(
            'classes.*',
            DB::raw("COALESCE(SUM(CASE WHEN enrollments.acctok = '1' AND enrollments.assessed = '1' THEN enrollments.assessed ELSE 0 END), 0) AS assessedinclass"),
            DB::raw("COALESCE(SUM(CASE WHEN enrollments.validated = '1' THEN 1 ELSE 0 END), 0) AS validatedinclass"),
            DB::raw("COALESCE(SUM(enrolled_classes.class_id = classes.id), 0) AS enrolledinclass"),
            'subjects.code as subject_code',
            'subjects.name as subject_name',
            'instructors.last_name',
            'instructors.first_name',
            'instructors.middle_name',
            'instructors.name_suffix',
            'schedules.schedule',
            'sections.code as section_code',
            DB::raw("CONCAT(instructors.last_name, ', ', instructors.first_name, ' ', instructors.name_suffix, ' ', instructors.middle_name) AS full_name"),
            'mergeclass.code AS mothercode',
            'mergeclass.id AS mothercodeid',
            'mergeclass.slots AS mothercodeslots',
        );
        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id'); 
        
        $query->leftJoin('enrolled_classes', 'classes.id', '=', 'enrolled_classes.class_id');
        $query->leftJoin('enrollments', 'enrolled_classes.enrollment_id', '=', 'enrollments.id');
        $query->leftJoin('classes AS mergeclass', 'classes.merge', '=', 'mergeclass.id');

        $query->where('classes.period_id', $period_id);

        $query->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use ($educational_level_id) {
            $query->where('programs.educational_level_id', $educational_level_id);
        });

        $query->when(isset($college_id) && !empty($college_id), function ($query) use ($college_id) {
            $query->where('programs.college_id', $college_id);
        });

        $query->when(isset($display) && !empty($display), function ($query) use ($display) 
        {
            switch ($display) 
            {
                case 'dissolved':
                    $query->where('classes.dissolved', 1);
                    break;
                case 'tutorial':
                    $query->where('classes.tutorial', 1);
                    break;
                case 'f2f':
                    $query->where('classes.f2f', 1);
                    break;
                // case 'merged':
                //     $query->whereNotNull('classes.merge');
                //     break;
                default:
                  $query->where('classes.dissolved', '!=', 1);
                    break;
            }
        });

        // $query->when(isset($display) && !empty($display) && $display == 'open', function ($query) use ($display) 
        // {
        //     $query->havingRaw('classes.slots > enrolledinclass');
        // });

        // $query->when(isset($display) && !empty($display) && $display == 'closed', function ($query) use ($display) 
        // {
        //     $query->havingRaw('classes.slots <= enrolledinclass');
        // });

        $query->groupBy('classes.id');
        $query->orderBy('subjects.code')
            ->orderBy('classes.id');

        $classes = $query->get();

        return $classes;
    }

    public function getGeneralSchedules($generalschedules, $display="")
    {
        $classes_array = [];

        if($generalschedules)
        {
            foreach ($generalschedules as $key => $v) 
            {
                $totalassessed  = 0;
            	$totalvalidated = 0;
                $totalenrolled  = 0;
            	$remainingslot  = 0;

            	$classes_array[$key]['class_id']      = $v->id;
            	$classes_array[$key]['class_code']    = $v->code;
            	$classes_array[$key]['section_code']  = $v->section_code;
            	$classes_array[$key]['subject_code']  = $v->subject_code;
            	$classes_array[$key]['subject_name']  = $v->subject_name;
            	$classes_array[$key]['units']         = $v->units;
            	$classes_array[$key]['schedule']      = $v->schedule;
            	$classes_array[$key]['tutorial']      = $v->tutorial;
            	$classes_array[$key]['dissolved']     = $v->dissolved;
            	$classes_array[$key]['mothercode']    = $v->mothercode;
            	$classes_array[$key]['instructor_id'] = $v->instructor_id;
            	$classes_array[$key]['last_name']     = $v->last_name;
            	$classes_array[$key]['first_name']    = $v->first_name;
                $classes_array[$key]['merge']         = $v->merge;
            	
                $assessedinclass  =  ($v->assessedinclass == "") ? 0 : $v->assessedinclass;
            	$validatedinclass = ($v->validatedinclass == "") ? 0 : $v->validatedinclass;
                $enrolledinclass  = ($v->enrolledinclass == "") ? 0 : $v->enrolledinclass;

                if($v->ismother > 0){
					$classes_array[$key]['slots'] = $v->slots;
					$class_id = $v->id;

					$children = array_filter($generalschedules->toArray(), function($record) use($class_id){
						return $record['mothercodeid'] == $class_id;
					});

					$sum_assessedinclass  = 0;
					$sum_validatedinclass = 0;
                    $sum_enrolledinclass  = 0;

					if($children)
					{
						foreach ($children as $child) 
						{
							$sum_assessedinclass  += $child['assessedinclass'];
            				$sum_validatedinclass += $child['validatedinclass'];
                            $sum_enrolledinclass  += $child['enrolledinclass'];
						}
					}

					$totalassessed  = $assessedinclass + $sum_assessedinclass;
					$totalvalidated = $validatedinclass + $sum_validatedinclass;
                    $totalenrolled  = $enrolledinclass + $sum_enrolledinclass;
					$remainingslot  = $v->slots - $totalenrolled;

				}else{//not a mothercode
					/*
						IF SUBJECT MERGE IN != 0
						SUBJECT IS MERGED GET ENROLLED IN MOTHERCODE AND ALL MERGED CHILD
					*/
					if($v->merge != 0)
					{
						$motherclass = $v->merge;

						$mother = array_filter($generalschedules->toArray(), function($record) use($motherclass){
							return $record['id'] == $motherclass;
						});

						$sum_mother_assessedinclass  = 0;
						$sum_mother_validatedinclass = 0;
                        $sum_mother_enrolledinclass  = 0;

						if($mother)
						{
							foreach ($mother as $m) 
							{
								$sum_mother_assessedinclass  += $m['assessedinclass'];
	            				$sum_mother_validatedinclass += $m['validatedinclass'];
                                $sum_mother_enrolledinclass  += $m['enrolledinclass'];
							}
						}

						$children = array_filter($generalschedules->toArray(), function($record) use($motherclass){
							return $record['mothercodeid'] == $motherclass;
						});

						$sum_assessedinclass  = 0;
						$sum_validatedinclass = 0;
                        $sum_enrolledinclass  = 0;

						if($children)
						{
							foreach ($children as $child) 
							{
								$sum_assessedinclass  += $child['assessedinclass'];
	            				$sum_validatedinclass += $child['validatedinclass'];
                                $sum_enrolledinclass  += $child['enrolledinclass'];
							}
						}
                        
                        $classes_array[$key]['slots'] = $v->mothercodeslots;
						$totalassessed  += $sum_assessedinclass + $sum_mother_assessedinclass;
						$totalvalidated += $sum_validatedinclass + $sum_mother_validatedinclass;
                        $totalenrolled  += $sum_enrolledinclass + $sum_mother_enrolledinclass;
                        $remainingslot  = $v->mothercodeslots - $totalenrolled;

					}else{
						$classes_array[$key]['slots'] = $v->slots ?? 0;
						$totalassessed  = $assessedinclass;
						$totalvalidated = $validatedinclass;
                        $totalenrolled  = $enrolledinclass;
						$remainingslot  = $v->slots - $totalenrolled;
					}	
				}

                $classes_array[$key]['totalassessed']  = $totalassessed;
                $classes_array[$key]['totalvalidated'] = $totalvalidated;
                $classes_array[$key]['totalenrolled']  = $totalenrolled;
                $classes_array[$key]['remainingslot']  = $remainingslot;
            }
        }

        if ($display == 'open') {
            $filteredData = array_filter($classes_array, function ($item) {
                return $item['slots'] > $item['totalenrolled'];
            });
        } else if ($display == 'closed') {
            $filteredData = array_filter($classes_array, function ($item) {
                return $item['slots'] <= $item['totalenrolled'];
            });
        } else if ($display == 'merged') {
            $filteredData = array_filter($classes_array, function ($item) {
                return $item['merge'] != NULL;
            });
        } else {
            $filteredData = $classes_array;
        }
        
        return $filteredData;
    }

    public function filtergeneralschedule(Request $request)
    {
        $generalschedules = $this->generalschedules($request->period_id, $request->educational_level, $request->college, $request->display);
        $gensched_classes = $this->getGeneralSchedules($generalschedules, $request->display);

        return view('generalschedule.return_generalschedule', compact('gensched_classes'));
    }
}
