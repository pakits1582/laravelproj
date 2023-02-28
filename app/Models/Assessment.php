<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id', 'period_id', 'amount', 'assesed', 'user_id'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');

    }

    public function breakdowns()
    {
        return $this->hasMany(AssessmentBreakdown::class, 'assessment_id', 'id');

    }
    
    public function details()
    {
        return $this->hasMany(AssessmentDetail::class, 'assessment_id', 'id');
    }

    public function exam()
    {
        return $this->hasOne(AssessmentExam::class, 'assessment_id', 'id');

    }

}
