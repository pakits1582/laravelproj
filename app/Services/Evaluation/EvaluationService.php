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

    public function processGrades($grades)
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

    public function checkTaggedGradeInfo($tagged_grades)
    {
        $tagged_external_grade_info = [];
        $tagged_internal_grade_info = [];


        foreach ($tagged_grades as $key => $cst_grade) {
            if($cst_grade['origin'] === 1)
            {
                $tagged_external_grade_info[] = (new ExternalGradeService())->getExternalGradeInfo($cst_grade['grade_id'])->toArray();
            }else{
                $tagged_internal_grade_info[] = (new InternalGradeService())->getInternalGradeInfo($cst_grade['grade_id'])->toArray();
            }
        }

        // print_r($tagged_external_grade_info);
        // print_r($tagged_internal_grade_info);

        $grade_info_external = $this->processGrades($tagged_external_grade_info);
        $grade_info_internal = $this->processGrades($tagged_internal_grade_info);

        // print_r($grade_info_external);
        // print_r($grade_info_internal);

        if($grade_info_external || $grade_info_internal)
        {
            //IF BOTH EXTERNAL AND INTERNAL NOT EMPTY CHECK WHICH GRADE IS HIGHER
            if($grade_info_external && $grade_info_internal)
            {
                $external_grade = ($grade_info_external) ? (($grade_info_external['grade'] === 'INC') ? $grade_info_external['completion_grade'] : $grade_info_external['grade']) : '';
                $internal_grade = ($grade_info_internal) ? (($grade_info_internal['grade'] === 'INC') ? $grade_info_internal['completion_grade'] : $grade_info_internal['grade']) : '';
                
                $grade_info = ($internal_grade >= $external_grade) ? $grade_info_internal+['source' => 'internal'] : $grade_info_external+['source' => 'external'];

                return $grade_info;
            }

            $grade_info = ($grade_info_internal) ? $grade_info_internal+['source' => 'internal'] : $grade_info_external+['source' => 'external'];
                
            return $grade_info;
            
        }
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

    public function storeTaggedGrades($request)
    {
        $student = (new StudentService)->studentInformation($request->student_id);
        $curriculum_subject = (new CurriculumService)->returnCurriculumSubject($request->curriculum_subject_id);
        $allgrades = $this->getAllGradesInternalAndExternal($request->student_id);

        $user_permissions = Auth::user()->permissions;

        if($request->filled('cboxtag'))
        {
            $total_unit_of_selected_grades = 0;
			$total_units_remaining = 0;
			$selected_grades = [];
            $multi_tagged = 0;


            foreach ($request->cboxtag as $key => $selected_grade) {
                $a = 'origin_'.$selected_grade;
                $b = 'istagged_'.$selected_grade;
                $origin = $request->$a;
                $istagged = $request->$b;
                $multi_tagged += $istagged;

                $allgrade_key = array_keys(array_filter($allgrades, fn($data) => $data['origin'] == $origin && $data['id'] == $selected_grade));
                $total_unit_of_selected_grades += ($allgrades[$allgrade_key[0]]['units']) ? : 0;

                if($istagged == 1)
                {
                    $total_units_of_tagged_grades = 0;

                    if($origin === 'internal')
                    {
                        $already_tagged_grades = $this->getAllTaggedGradesInternal($request->student_id, $selected_grade, $request->curriculum_subject_id);
                    }else{
                        $already_tagged_grades = $this->getAllTaggedGradesExternal($request->student_id, $selected_grade, $request->curriculum_subject_id);
                    }

                    if(!$already_tagged_grades->isEmpty())
                    {
                        $total_units_of_tagged_grades += $already_tagged_grades->sum('units');

                        $remainingunits = (float)$curriculum_subject->subjectinfo->units - (float)$total_units_of_tagged_grades;
						$total_units_remaining += $remainingunits;
                    }
                }else{
                    $total_units_remaining += $curriculum_subject->subjectinfo->units;
                }

                $selected_grades[] = [
                    'student_id' => $request->student_id,
                    'curriculum_subject_id' => $request->curriculum_subject_id,
                    'grade_id' => $selected_grade,
                    'origin' => ($origin === 'internal') ? 0 : 1
                ];
            }

            if($multi_tagged > 0)
            {
                if($user_permissions->count())
                {
                    if(!Helpers::is_column_in_array('can_evaluatetwice', 'permission', $user_permissions->toArray())){
                        return [
                            'success' => false,
                            'message' => 'ACCESS DENIED! Your account does not have permission to tag subject/grade twice!',
                            'alert' => 'alert-danger'
                        ];
                    }
                }
            }

            if($total_unit_of_selected_grades < $curriculum_subject->subjectinfo->units)
            {
                return [
                    'success' => false,
                    'message' => 'Total units of selected subjects/grades not equal to the units of grade/subject to be tagged!',
                    'alert' => 'alert-danger'
                ];
            }

            if($total_units_remaining < $curriculum_subject->subjectinfo->units)
            {
                return [
                    'success' => false,
                    'message' => 'The remaining total units of selected subjects/grade not equal to the units of grade/subject to be tagged!',
                    'alert' => 'alert-danger'
                ];
            }
            
            TaggedGrades::where('student_id', $student->id)->where('curriculum_subject_id', $request->curriculum_subject_id)->delete();
            TaggedGrades::insert($selected_grades);
            
            return [
                'success' => true,
                'message' => 'Changes sucessfully saved!',
                'alert' => 'alert-success'
            ];
        }

        TaggedGrades::where('student_id', $student->id)->where('curriculum_subject_id', $request->curriculum_subject_id)->delete();

        return [
            'success' => true,
            'message' => 'Changes sucessfully saved!',
            'alert' => 'alert-success'
        ];
        
    }

    public function getAllTaggedGradesInternal($student_id, $grade_id, $curriculum_subject_id = NULL)
    {
        $query = TaggedGrades::query();
        $query->select(
            'internal_grades.id',
            'subjects.code',
            'subjects.name',
            'grading_systems.value AS grade', 
            'cggs.value AS completion_grade',
            'subjects.units'
        );
        $query->leftJoin('internal_grades', 'tagged_grades.grade_id', 'internal_grades.id');
        $query->leftJoin('classes', 'internal_grades.class_id', 'classes.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'classes.curriculum_subject_id');
        $query->leftJoin('grading_systems', 'internal_grades.grading_system_id', 'grading_systems.id');
        $query->leftJoin('remarks', 'grading_systems.remark_id', 'remarks.id');
        $query->leftJoin('grading_systems AS cggs', 'internal_grades.completion_grade', 'cggs.id');
        $query->leftJoin('remarks AS cgr', 'cggs.remark_id', 'cgr.id');
        $query->where('tagged_grades.origin',  0)->where('tagged_grades.grade_id', $grade_id)->where('student_id', $student_id);
        if($curriculum_subject_id !== NULL)
        {
            $query->where('curriculum_subject_id', '!=', $curriculum_subject_id);
        }
        
        return $query->get();
    }

    public function getAllTaggedGradesExternal($student_id, $grade_id, $curriculum_subject_id = NULL)
    {
        $query = TaggedGrades::query();
        $query->select(
            'external_grades.id',
            'external_grades.subject_code AS code',
            'external_grades.subject_description AS name',
            'external_grades.grade',
            'external_grades.completion_grade',
            'external_grades.units',
        );
        $query->leftJoin('external_grades', 'tagged_grades.grade_id', 'external_grades.id');
        $query->where('tagged_grades.origin',  1)->where('tagged_grades.grade_id', $grade_id)->where('student_id', $student_id);
        if($curriculum_subject_id !== NULL)
        {
            $query->where('curriculum_subject_id', '!=', $curriculum_subject_id);
        }
        
        return $query->get();
    }

    public function studentsAllTaggedGrades($student_id)
    {
        return TaggedGrades::where('student_id', $student_id)->get();
    }
}