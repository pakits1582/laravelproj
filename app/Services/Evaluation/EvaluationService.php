<?php

namespace App\Services\Evaluation;

use App\Models\User;
use App\Models\Instructor;
use App\Services\ProgramService;
use App\Services\StudentService;


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


}