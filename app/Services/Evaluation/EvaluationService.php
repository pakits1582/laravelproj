<?php

namespace App\Services\Evaluation;

use App\Libs\Helpers;
use App\Models\User;
use App\Models\Instructor;
use App\Models\TaggedGrades;
use App\Services\ProgramService;
use App\Services\StudentService;
use App\Services\CurriculumService;
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
            }else{
                if($v['completion_grade'] > $max_cg)
                {
                    $max_cg = $v['completion_grade'];
                    $cggrade_arr = $v;
                }
            }
        }

        if($grade_arr && $cggrade_arr){
            if($cggrade_arr['completion_grade'] >= $grade_arr['grade'])
            {
               return $cggrade_arr;
            }
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

        $all_tagged_grades = TaggedGrades::where('student_id', $request->student_id)->get();
        $allgrades = $this->getAllGradesInternalAndExternal($request->student_id);

        if($request->filled('cboxtag'))
        {
            $total_unit_of_selected_grades = 0;
			$total_units_remaining = 0;
			$taggedto = '<table id="taggedsubs">';
			$selected_grades = [];

            foreach ($request->cboxtag as $key => $selected_grade) {
                $a = 'origin_'.$selected_grade;
                $origin = $request->$a;

                $allgrade_key = array_filter($allgrades, fn($data) => $data['origin'] == $origin && $data['id'] == $selected_grade);

                $selected_grades[] = ['origin' => $origin, 'id' => $selected_grade, 'key' => $allgrade_key, 'allgrades' => $allgrades];
                
            }

            return $selected_grades;
        }

        
    }
}