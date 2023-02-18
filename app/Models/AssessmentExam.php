<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentExam extends Model
{
    use HasFactory;

    protected $table = 'assessment_exams';

    protected $fillable = ['assessment_id', 'amount', 'downpayment', 'exam1', 'exam2', 'exam3', 'exam4', 'exam5', 'exam6', 'exam7', 'exam8', 'exam9', 'exam10'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'id');

    }
}
