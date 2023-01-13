<?php

namespace App\Services;

use App\Libs\Helpers;
use Illuminate\Support\Arr;
use App\Models\TaggedGrades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Grade\ExternalGradeService;
use App\Services\Grade\InternalGradeService;
use App\Services\Evaluation\EvaluationService;

class TaggedGradeService
{
    public function getAllTaggedGrades($student_id)
    {
        $query = TaggedGrades::query();
        $query->select(
            'tagged_grades.*', 
            'curriculum_subjects.subject_id'
        );
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'tagged_grades.curriculum_subject_id');
        $query->where('tagged_grades.student_id', $student_id);

        return $query->get();
    }  
    
    public function getAllTaggedGradesInternal($student_id, $grade_id, $curriculum_subject_id = NULL)
    {
        $query = TaggedGrades::query();
        $query->select(
            'internal_grades.id',
            'internal_grades.units',
            'classes.units AS class_units',
            'grading_systems.value AS grade', 
            'cggs.value AS completion_grade',
            'subjects.units AS curriculum_subject_units',
            'subjects.code AS curriculum_subject_code',
            'subjects.name AS curriculum_subject_name'
        );
        $query->leftJoin('internal_grades', 'tagged_grades.grade_id', 'internal_grades.id');
        $query->leftJoin('classes', 'internal_grades.class_id', 'classes.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'tagged_grades.curriculum_subject_id');
        $query->leftJoin('subjects', 'curriculum_subjects.subject_id', 'subjects.id');
        $query->leftJoin('grading_systems', 'internal_grades.grading_system_id', 'grading_systems.id');
        $query->leftJoin('remarks', 'grading_systems.remark_id', 'remarks.id');
        $query->leftJoin('grading_systems AS cggs', 'internal_grades.completion_grade', 'cggs.id');
        $query->leftJoin('remarks AS cgr', 'cggs.remark_id', 'cgr.id');
        $query->where('tagged_grades.origin',  0)->where('tagged_grades.grade_id', $grade_id)->where('student_id', $student_id);
        if($curriculum_subject_id !== NULL)
        {
            $query->where('tagged_grades.curriculum_subject_id', '!=', $curriculum_subject_id);
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
            'subjects.units AS curriculum_subject_units',
            'subjects.code AS curriculum_subject_code',
            'subjects.name AS curriculum_subject_name'
        );
        $query->leftJoin('external_grades', 'tagged_grades.grade_id', 'external_grades.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'tagged_grades.curriculum_subject_id');
        $query->leftJoin('subjects', 'curriculum_subjects.subject_id', 'subjects.id');
        $query->where('tagged_grades.origin',  1)->where('tagged_grades.grade_id', $grade_id)->where('student_id', $student_id);
        if($curriculum_subject_id !== NULL)
        {
            $query->where('curriculum_subject_id', '!=', $curriculum_subject_id);
        }
        
        return $query->get();
    }

    public function storeTaggedGrades($request)
    {
        $student = (new StudentService)->studentInformation($request->student_id);
        $curriculum_subject = (new CurriculumService)->returnCurriculumSubject($request->curriculum_subject_id);
        $allgrades = (new EvaluationService)->getAllGradesInternalAndExternal($request->student_id);

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
                $total_unit_of_selected_grades += (is_numeric(($allgrades[$allgrade_key[0]]['units']))) ? (($allgrades[$allgrade_key[0]]['units']) ? : 0) : $curriculum_subject->subjectinfo->units;

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
                        $total_units_of_tagged_grades += $already_tagged_grades->sum('curriculum_subject_units');
                        $remainingunits = (float)$total_unit_of_selected_grades - (float)$total_units_of_tagged_grades;
						
                        $total_units_remaining += $remainingunits;
                    }
                }else{
                    $total_units_remaining += $total_unit_of_selected_grades;
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

    public function checkTaggedGradeInfo($tagged_grades, $internal_grades, $external_grades)
    {        

        $tagged_external_grade_info = [];
        $tagged_internal_grade_info = [];

        foreach ($tagged_grades as $key => $cst_grade) {
            if($cst_grade['origin'] === 1)
            {
                //$tagged_external_grade_info[] = (new ExternalGradeService())->getExternalGradeInfo($cst_grade['grade_id'])->toArray();
                $tagged_external_grade_info[] = $external_grades->where('id', $cst_grade['grade_id'])->values()->toArray();
            }else{
                $tagged_internal_grade_info[] = $internal_grades->where('id', $cst_grade['grade_id'])->values()->toArray();
                //$tagged_internal_grade_info[] = (new InternalGradeService())->getInternalGradeInfo($cst_grade['grade_id'])->toArray();
            }
        }
        //return Arr::collapse($tagged_external_grade_info);

        $grade_info_external = (new EvaluationService())->getMaxValueOfGrades(Arr::collapse($tagged_external_grade_info));
        $grade_info_internal = (new EvaluationService())->getMaxValueOfGrades(Arr::collapse($tagged_internal_grade_info));

        //return $grade_info_external;
        
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
