<?php

namespace App\Services;

use App\Models\Program;
use Illuminate\Database\Eloquent\Builder;

class CurriculumService
{
    public function getPrograms($user)
    {
        // $userType = $user->utype;

        // $programs = Program::when($userType === 0, function (Builder $query) use ($user) {
        //     $userInfoId = $user->info->id;
        //     $userDesignation = $user->info->designation;

        //     return $query->when($userDesignation === 4, function (Builder $query) use ($userInfoId) {
        //         return $query->withWhereHas('collegeinfo', fn(Builder $query) => $query->where('dean', $userInfoId));
        //     })->when($userDesignation === 2, function (Builder $query) use ($userInfoId) {
        //         return $query->where('head', $userInfoId);
        //     });
            
        // })->get();

        if($user->utype === 0)
        {
            $programs = Program::with(['level', 'collegeinfo', 'headinfo'])->orderBy('code')->get();
        }

        //CHECK INSTRUCTOR DESIGNATION
        if($user->info->designation === 4)
        { //DEAN
            $programs = Program::withWhereHas('collegeinfo', fn($query) =>
                                    $query->where('dean', $user->info->id)
                                )->get();
        }else if($user->info->designation === 2)
        {//PROGRAM HEAD
            $programs = Program::where('head', $user->info->id)->get();
        }

        return $programs;

    }
}
