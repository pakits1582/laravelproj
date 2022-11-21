<?php

namespace App\Services\Evaluation;

use App\Models\User;
use App\Models\Instructor;
use App\Services\ProgramService;
use App\Services\StudentService;
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



}