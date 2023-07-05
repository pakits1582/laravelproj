<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\EnrolledClass;
use App\Models\EnrolledClassSchedule;
use App\Models\InternalGrade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClassListService
{
   
    public function classlist($period_id, $criteria = '', $keyword = '', $limit = 0)
    {
        $query = Classes::query();
        $query->select(
            'classes.*',
            'subjects.code as subject_code',
            'subjects.name as subject_name',
            'instructors.last_name',
            'instructors.first_name',
            'instructors.middle_name',
            'instructors.name_suffix',
            'schedules.schedule',
            'sections.code as section_code',
        );

        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id'); 
        $query->where('classes.period_id', $period_id);

        $query->when(isset($criteria) && !empty($criteria) && !empty($keyword) , function ($query) use ($criteria, $keyword) 
        {
            switch ($criteria) 
            {
                case 'code':
                    $query->where('classes.code', $keyword);
                    break;
                case 'instructor':
                    $query->where('instructors.last_name', 'like', $keyword.'%')->orWhere('instructors.first_name', 'like', $keyword.'%');
                    break;
                case 'subject':
                    $query->where('subjects.code', 'like', $keyword.'%');
                    break;
            }
        });

        $query->orderBy('subjects.code')
            ->orderBy('classes.id');

        $query->when(isset($limit) && $limit != 0 , function ($query) use ($limit) 
        {
            $query->limit($limit);
        });
    
        $classes = $query->get();

        return $classes;
    }

    public function transferstudents($validatedData)
    {
        try {
            DB::beginTransaction();
            
            $transferto_class_id = $validatedData['transferto_class_id'];
            $transferfrom_class_id = $validatedData['transferfrom_class_id'];

            $transferto_class = Classes::with('schedule:id,schedule')->findOrFail($transferto_class_id);
            $transferfrom_class = Classes::with('schedule:id,schedule')->findOrFail($transferfrom_class_id);

            $enrollments = Enrollment::with('grade')->whereIn("id", $validatedData['enrollment_ids'])->get();
            $grade_ids = $enrollments->pluck('grade.id')->filter(function ($gradeId) {
                return $gradeId !== null;
            })->toArray();
            

            //DELETE FROM CLASS
            EnrolledClass::whereIn('class_id', $validatedData['class_ids'])->whereIn('enrollment_id', $validatedData['enrollment_ids'])->delete();
            //DELETE FROM GRADE INTERNAL
            InternalGrade::where('class_id', $transferfrom_class_id)->whereIn('grade_id', $grade_ids)->delete();
            
            $enroll_classes = [];
            $enroll_class_schedules = [];
            $internal_grades = [];

            foreach ($validatedData['enrollment_ids'] as $key => $enrollment_id)
            {
                $enroll_classes[] = [
                    'enrollment_id' => $enrollment_id,
                    'class_id'      => $transferto_class->id,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now()
                ];

                if(!is_null($transferto_class->schedule->schedule))
                {
                    $class_schedules = (new ClassesService())->processSchedule($transferto_class->schedule->schedule);

                    foreach ($class_schedules as $key => $class_schedule) 
                    {
                        foreach ($class_schedule['days'] as $key => $day) 
                        {
                            $enroll_class_schedules[] = [
                                'enrollment_id' => $enrollment_id,
                                'class_id'      => $transferto_class->id,
                                'from_time'     => $class_schedule['timefrom'],
                                'to_time'       => $class_schedule['timeto'],
                                'day'           => $day,
                                'room'          => $class_schedule['room'],
                                'created_at'    => now(),
                                'updated_at'    => now()
                            ];
                        }
                    }
                }
            }

            foreach ($grade_ids as $key => $grade_id) 
            {
                $internal_grades[] = [
                    'grade_id' => $grade_id,
                    'class_id' => $transferto_class->id,
                    'units' => $transferto_class->units,
                    'grading_system_id' => NULL,
                    'completion_grade' => NULL,
                    'final' => 0,
                    'user_id' =>  Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            EnrolledClass::insert($enroll_classes);
            EnrolledClassSchedule::insert($enroll_class_schedules);
            InternalGrade::insert($internal_grades);

            //REASSESSMENTS OF ENROLLMENT IDS

            DB::commit();

            return [
                'success' => true,
                'message' => 'Selected students successfully transfered!',
                'alert' => 'alert-success',
                'status' => 200
            ];
        
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