<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAcademicInformationModel extends Model
{
    use HasFactory;
    protected $table = 'student_academic_informations';
    protected $fillable = ['student_id', 'is_alsfinisher', 'elem_school', 'elem_address', 'elem_period', 'jhs_school', 'jhs_address', 'jhs_period', 'shs_school', 'shs_address', 'shs_period', 'shs_strand', 'shs_techvoc_specify', 'college_school', 'college_program', 'college_address', 'college_period', 'graduate_school', 'graduate_program', 'graduate_address', 'graduate_period'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
