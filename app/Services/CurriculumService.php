<?php

namespace App\Services;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Subject;
use App\Models\Curriculum;
use App\Models\Instructor;
use App\Services\ProgramService;
use App\Models\CurriculumSubjects;
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

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', $request->keyword.'%')
                ->orWhere('name', 'like', $request->keyword.'%');
            });
        }

       return $query->get();
    }

    public function viewCurriculum($program, $curriculum)
    {
        $curriculum_subjects = CurriculumSubjects::with(['subjectinfo', 'terminfo'])->where('curriculum_id', $curriculum->id)->get();
        $grouped = $curriculum_subjects->groupBy(['year_level', 'terminfo.term']);
        
        return ['curriculum_subjects' => $grouped, 'program' => $program, 'curriculum' => $curriculum];
    }
}
