<?php

namespace App\Services;

use App\Models\Subject;

class SubjectService
{
    //

    public function returnSubjects($request, $all = false)
    {
        $query = Subject::with(['collegeinfo', 'educlevel'])->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', $request->keyword.'%')
                ->orWhere('name', 'like', $request->keyword.'%');
            });
        }
        if($request->has('educational_level') && !empty($request->educational_level)) {
            $query->where('educational_level_id', $request->educational_level);
        }
        if($request->has('college') && !empty($request->college)) {
            $query->where('college_id', $request->college);
        }
        if($request->has('type') && !empty($request->type)) {
            if($request->type === 'professional'){
                $query->where('professional', 1);
            }
            if($request->type === 'laboratory'){
                $query->where('laboratory', 1);
            }
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
        
    }

    public function dropdownSelectSearch($request)
    {
        if($request->searchTerm)
        {
            $query = Subject::orderBy('code');

            if($request->has('searchTerm') && !empty($request->searchTerm)) 
            {
                $query->where(function($query) use($request){
                    $query->where('code', 'like', '%'.$request->searchTerm.'%')
                        ->orWhere('name', 'like', '%'.$request->searchTerm.'%');   
                });
            }

            $subjects = $query->limit(20)->get();

            $data = [];
            if(!$subjects->isEmpty())
            {
                foreach ($subjects as $key => $subject) {
                    $data[] = ['id' => $subject->id, 'text' => '('.$subject->units.') - ['.$subject->code.'] '.$subject->name];
                }
            } 

            return $data;
        }
    }
}
