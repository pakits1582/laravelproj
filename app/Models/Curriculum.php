<?php

namespace App\Models;

use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Curriculum extends Model
{
    use HasFactory;

    public function getPrograms($user)
    {
        $query = Program::query();

        if($user->Admin()) {
            $query->with(['level', 'collegeinfo', 'headinfo'])->orderBy('code');
        }

        if($user->info->designation === Instructor::TYPE_DEAN) {
            $query->withWhereHas('collegeinfo', fn($query) =>
                $query->where('dean', $user->info->id)
            );
        }

        if($user->info->designation === Instructor::TYPE_PROGRAM_HEAD) {
            $query->where('head', $user->info->id);
        }

        $programs = $query->where('source', 1)->where('active', 1)->get();

        return $programs;
    }

    public function program()
    {
        return $this->hasOne(Program::class, 'program_id', 'id');
    }
}
