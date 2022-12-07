<?php

namespace App\Services\Evaluation;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Instructor;
use App\Models\TaggedGrades;
use App\Services\ProgramService;
use App\Services\StudentService;
use App\Services\CurriculumService;
use Illuminate\Support\Facades\Auth;
use App\Services\Grade\ExternalGradeService;
use App\Services\Grade\InternalGradeService;
use App\Services\TaggedGradeService;

class EvaluationService
{
    public function handleUser($user, $request)
    {
        if($user->utype === User::TYPE_INSTRUCTOR)
        {
            $user->load('instructorinfo');

            if($user->instructorinfo->designation === Instructor::TYPE_PROGRAM_HEAD)
            {
                $programs = (new ProgramService())->programHeadship($user);
            }

            if($user->instructorinfo->designation === Instructor::TYPE_DEAN)
            {
                $programs = (new ProgramService())->programDeanship($user);
            }
        }else{
            $programs = (new ProgramService())->returnPrograms($request,true,false);
        }

        if($user->accessibleprograms->count())
        {
            $programs = $user->accessibleprograms->load('program');

            return $programs;
        }

        return $programs;
    }

    public function evaluateStudent($student, $request)
    {
        $user_programs = $this->handleUser(Auth::user(), $request);

        if(!is_null($student->program_id) && !is_null($student->curriculum_id))
        {
            if($user_programs->contains('id', $student->program_id) || $user_programs->contains('program.id', $student->program_id))
            {
                $internal_grades = (new InternalGradeService())->getAllStudentPassedInternalGrades($student->id);
                $tagged_grades   = (new TaggedGradeService)->getAllTaggedGrades($student->id);
                $blank_grades    = (new InternalGradeService())->getAllBlankInternalGrades($student->id);
                $curriculuminfo  = (new CurriculumService())->viewCurriculum($student->program, $student->curriculum);
                // echo '<pre>';
                // print_r($blank_grades->toArray());
                $evaluation = [];
                if($curriculuminfo['program'])
                {
                    for($x=1; $x <= $curriculuminfo['program']->years; $x++)
                    {
                        foreach ($curriculuminfo['curriculum_subjects'] as $yearlevel => $curriculum_subject)
                        {
                            if ($yearlevel === $x)
                            {
                                //echo $yearlevel.'<br>';
                                foreach ($curriculum_subject as $term => $subjects)
                                {
                                    foreach ($subjects->toArray() as $subject)
                                    {
                                        $finalgrade = '';
                                        $cggrade    = '';
                                        $gradeid    = '';
                                        $units      = '';
                                        $origin     = '';
                                        $ispassed   = 0;
                                        $manage     = true;

                                        $grades = $internal_grades->where('subject_id', $subject['subject_id'])->toArray();

                                        if($grades)
                                        {
                                            $grade_info = $this->getMaxValueOfGrades($grades);
                                            if($grade_info)
                                            {
                                                $finalgrade = $grade_info['grade'];
                                                $cggrade    = $grade_info['completion_grade'];
                                                $gradeid    = $grade_info['id'];
                                                $units      = $grade_info['units'];
                                                $origin     = 'internal';
                                                $ispassed   = 1;
                                                $manage     = ($subject['subjectinfo']['units'] !== $grade_info['units']) ? true : false;
                                            }
                                        }else{
                                            //CHECK EQUIVALENTS SUBJECTS IF PASSED
                                            if($subject['equivalents'])
                                            {
                                                $equivalent_subjects_internal_grades = [];
                                                $equivalent_subjects_external_grades = [];

                                                foreach ($subject['equivalents'] as $key => $equivalent_subject)
                                                {
                                                    //GET ALL INTERNAL GRADES OF EQUIVALENT SUBJECTS
                                                    $equivalent_subjects_internal_grades += $internal_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                                                    //GET ALL EXTERNAL GRADES OF EQUIVALENT SUBJECTS
                                                    $equivalent_subjects_external_grades += $tagged_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                                                }

                                                if($equivalent_subjects_internal_grades)
                                                {
                                                    $grade_info = $this->getMaxValueOfGrades($equivalent_subjects_internal_grades);
                                                    $grade_info['source'] = 'internal';
                                                }
                                                
                                                if($ispassed === 0)
                                                {
                                                    if($equivalent_subjects_external_grades)
                                                    {
                                                        $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($equivalent_subjects_external_grades);
                                                    }
                                                }

                                                if($grade_info)
                                                {
                                                    $finalgrade = $grade_info['grade'];
                                                    $cggrade    = $grade_info['completion_grade'];
                                                    $gradeid    = $grade_info['id'];
                                                    $units      = $grade_info['units'];
                                                    $origin     = $grade_info['source'];
                                                    $ispassed   = 1;
                                                    $manage     = true;
                                                }
                                            }
                                        }////end of grade is passed internal

                                        //CHECK FROM TAGGED GRADES
                                        if($ispassed === 0)
                                        {
                                            $curriculum_subject_tagged_grades = $tagged_grades->where('subject_id', $subject['subject_id'])->toArray();

                                            if($curriculum_subject_tagged_grades)
                                            {
                                                $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($curriculum_subject_tagged_grades);

                                                if($grade_info)
                                                {
                                                    $finalgrade = $grade_info['grade'];
                                                    $cggrade    = $grade_info['completion_grade'];
                                                    $gradeid    = $grade_info['id'];
                                                    $units      = $grade_info['units'];
                                                    $origin     = $grade_info['source'];
                                                    $ispassed   = 1;
                                                    $manage     = true;
                                                }
                                            }//end of if has tagged subject
                                        }
                                        
                                        $subject['grade_info'] = [
                                            'finalgrade' => $finalgrade,
                                            'completion_grade' => $cggrade,
                                            'grade_id' => $gradeid,
                                            'units' => $units,
                                            'origin' => $origin,
                                            'ispassed' => $ispassed,
                                            'manage' => $manage,
                                            'inprogress' => (!$blank_grades->isEmpty()) ? ((Helpers::is_column_in_array($subject['subject_id'], 'subject_id', $blank_grades->toArray()) === false) ? 0 : 1) : 0
                                        ];

                                        $evaluation[] = $subject;
                                    }//end of foreach subjects
                                }
                            }
                        }
                    }
                }

                return $curriculuminfo + ['evaluation' => $evaluation, 'student' => $student];
            }

            return [
                'student' => $student,
                'evaluation' => false,
                'message' => 'Sorry, you are not allowed to evaluate the student. Your account does not have enough access to student\'s current program. You may contact the system administrators to access evalaution.'
            ];
        }

        return [
            'student' => $student,
            'evaluation' => false,
            'message' => 'Sorry, you can not evaluate student. Student has no program or curriculum selected.'
        ];
    }

    public function getMaxValueOfGrades($grades)
    {
        $max_grade = -9999999; //will hold max val
        $max_cg = -9999999;
        $grade_arr = null; //will hold item with max val;
        $cggrade_arr = null;

        
        foreach($grades as $k => $v)
        {
            if(is_numeric($v['grade'])){
                if($v['grade'] > $max_grade)
                {
                    $max_grade = $v['grade'];
                    $grade_arr = $v;
                }
            }else if(!is_numeric($v['grade']) && $v['completion_grade'] !== ''){
                if($v['completion_grade'] > $max_cg)
                {
                    $max_cg = $v['completion_grade'];
                    $cggrade_arr = $v;
                }
            }else{
                if($v['grade'] > $max_grade)
                {
                    $max_grade = $v['grade'];
                    $grade_arr = $v;
                }
            }
        }

        if(!is_null($grade_arr) && !is_null($cggrade_arr))
        {
            if($cggrade_arr['completion_grade'] > $grade_arr['grade'])
            {
                return $cggrade_arr;
            }
            return $grade_arr;
        }

        if(is_null($grade_arr) && !is_null($cggrade_arr))
        {
            return $cggrade_arr;
        }

        return $grade_arr;
    }

    public function getAllGradesInternalAndExternal($student_id)
    {
        $internal_grades = (new InternalGradeService)->getAllStudentPassedInternalGrades($student_id);
        $external_grades = (new ExternalGradeService)->getAllStudentPassedExternalGrades($student_id);

        $allgrades = [];
        if(!$internal_grades->isEmpty())
        {
            foreach ($internal_grades as $key => $i) {
                $allgrades[] = [
                    'id'      => $i->id,
                    'grade_id' => $i->grade_id,
                    'subject_code' => $i->code,
                    'subject_name' => $i->name,
                    'grade'   => $i->grade,
                    'completion_grade' => $i->completion_grade,
                    'units'   => $i->units,
                    'origin'  => "internal"
                ];
            }
        }

        if(!$external_grades->isEmpty())
        {
            foreach ($external_grades as $key => $e) {
                $allgrades[] = [
                    'id'      => $e->id,
                    'grade_id' => $e->grade_id,
                    'subject_code' => $e->subject_code,
                    'subject_name' => $e->subject_description,
                    'grade'   => $e->grade,
                    'completion_grade' => $e->completion_grade,
                    'units'   => $e->units,
                    'origin'  => "external"
                ];
            }
        }

        //SORT ARRAY
		$sortArray = []; 
		foreach($allgrades as $subject){ 
			foreach($subject as $key=>$value){ 
				if(!isset($sortArray[$key])){ 
					$sortArray[$key] = []; 
				} 
				$sortArray[$key][] = $value; 
			} 
		} 

		$orderby = "subject_code"; //change this to whatever key you want from the array
		if(!empty($allgrades)){
			array_multisort($sortArray[$orderby], SORT_ASC, $allgrades); 
		} 
		
        return $allgrades;
    }

}