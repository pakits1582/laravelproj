<?php

namespace App\Services;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Subject;
use App\Models\Curriculum;
use App\Models\Instructor;
use App\Services\ProgramService;
use App\Models\CurriculumSubjects;
use App\Models\Prerequisite;
use App\Models\Corequisite;
use App\Models\Equivalent;

use Illuminate\Database\Eloquent\Builder;

class CurriculumService
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
            $programs = (new ProgramService())->returnPrograms($request,false,true);
        }

        return $programs;
    }

    public function storeCurriculumSubejects($request)
    {
        $curriculum = Curriculum::with('subjects')->find($request->curriculum_id);

        foreach ($request->subjects as $key => $subject) {
            $subjects[] = new CurriculumSubjects([
                'subject_id' => $subject,
                'term_id'    => $request->term_id,
                'year_level' => $request->year_level
            ]);
        }

        return $curriculum->subjects()->saveMany($subjects);
    }

    public function searchSubjects($request)
    {
        $query = Subject::orderBy('code');

        if(!empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', $request->keyword.'%')
                ->orWhere('name', 'like', $request->keyword.'%');
            });
        }

       return $query->get();
    }

    public function viewCurriculum($program, $curriculum)
    {
        $curriculum_subjects = CurriculumSubjects::with([
            'subjectinfo', 
            'terminfo', 
            'prerequisites', 'prerequisites.curriculumsubject', 'prerequisites.curriculumsubject.subjectinfo',
            'equivalents', 'equivalents.subjectinfo',
        ])->where('curriculum_id', $curriculum->id)->get();
        $grouped = $curriculum_subjects->groupBy(['year_level', 'terminfo.term']);
        
        return ['curriculum_subjects' => $grouped, 'program' => $program, 'curriculum' => $curriculum];
    }

    public function searchCurriculumSubjects($request)
    {
        if($request->saveto === 'equivalents'){
            $subjects =  $this->searchSubjects($request);
        }else{
            $query = CurriculumSubjects::with(['subjectinfo'])->where("curriculum_id", $request->curriculum_id);
            if($request->has('keyword') && !empty($request->keyword)) {
                $query->WhereHas('subjectinfo', function (Builder $query) use($request) {
                    $query->where('code', 'like', $request->keyword.'%');
                    $query->orwhere('name', 'like', $request->keyword.'%');
                });
            }
            $subjects = $query->get();
        }

        return $subjects;
    }

    public function returnCurriculumSubject($curriculum_subject_id)
    {
        return CurriculumSubjects::with(['subjectinfo', 'prerequisites', 'corequisites', 'equivalents'])->where("id", $curriculum_subject_id)->firstOrFail();
    }

    public function returnCurriculumSubjectInfo($curriculum_subject_id)
    {
        return CurriculumSubjects::with(['subjectinfo', 'equivalents'])->where("id", $curriculum_subject_id)->firstOrFail();
    }

    public function storeManageCurriculumSubject($request)
    {
        $curriculum_subject = $this->returnCurriculumSubject($request->curriculum_subject);

        switch ($request->saveto) {
            case 'prerequisites':
                $model = Prerequisite::class;
                $saveto = 'prerequisite';
                break;
            case 'corequisites':
                $model = Corequisite::class;
                $saveto = 'corequisite';
                break;
            case 'equivalents':
                $model = Equivalent::class;
                $saveto = 'equivalent';
                break;
        }

        foreach ($request->subjects as $key => $subject) {
            $subjects[] = new $model([
                $saveto => $subject,
            ]);
        }

        return $curriculum_subject->{$request->saveto}()->saveMany($subjects);

    }

    public function deleteItem($id, $table)
    {
        switch ($table) {
            case 'prerequisites':
                $item = Prerequisite::findorfail($id);
                //return $item->id.'-'.$item->curriculum_subject_id.'-'.$item->prerequisite;
                $item->delete();
                break;
            case 'corequisites':
                $item = Corequisite::findorfail($id);
                $item->delete();
                break;
            case 'equivalents':
                $item = Equivalent::findorfail($id);
                $item->delete();
                break;
            case 'curriculum_subject':
                $item = CurriculumSubjects::findorfail($id);
                // return $item->id.'-'.$item->curriculum_id.'-'.$item->subject_id;
                $item->delete();
                break;
        }
    }

    public function curriculumSubjects($curriculum_id, $term = '', $year_level = '')
    {
        $query = CurriculumSubjects::with(['subjectinfo', 'prerequisites', 'corequisites', 'equivalents'])->where("curriculum_id", $curriculum_id);
        if($term !== '') {
            $query->where('term_id', $term);
        }

        if($year_level !== '') {
            $query->where('year_level', $year_level);
        }

        return $query->get();
    }

}
