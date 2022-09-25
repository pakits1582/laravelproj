<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Program;
use Illuminate\Database\Eloquent\Builder;

class ProgramService
{
    //

    public function returnPrograms($request, $all = false, $isactiveonly = false)
    {
        $query = Program::with(['level', 'collegeinfo', 'headinfo'])->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where('code', 'like', '%'.$request->keyword.'%')->orWhere('name', 'like', '%'.$request->keyword.'%');
        }
        if($request->has('educational_level') && !empty($request->educational_level)) {
            $query->where('educational_level_id', $request->educational_level);
        }
        if($request->has('college') && !empty($request->college)) {
            $query->where('college_id', $request->college);
        }
        if($request->has('status') && ($request->status == '0' || $request->status == '1')) {
            $query->where('active', $request->status);
        }

        if($isactiveonly)
        {
            $query->where('active', 1);
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
    }

    public function programHeadship($user, $paginate = false)
    {
        $query = Program::query();
        $query->where('head', $user->instructorinfo->id)->where('source', 1)->where('active', 1);

        if($paginate)
        {
            return $query->paginate(10);
        }

        return $query->get();
    }

    public function programDeanship($user, $paginate = false)
    {
        $query = Program::query();
        $query->WhereHas('collegeinfo', function (Builder $query) use($user) {
            $query->where('dean', $user->instructorinfo->id);
        })->orderBy('code')->where('source', 1)->where('active', 1);

        if($paginate)
        {
            return $query->paginate(10);
        }

        return $query->get();
    }
}
