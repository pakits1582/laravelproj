<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equivalent extends Model
{
    protected $fillable = ['curriculum_subject_id', 'equivalent'];
    use HasFactory;

    public function subjectinfo()
    {
        return $this->belongsTo(Subject::class, 'equivalent', 'id');
    }
}
