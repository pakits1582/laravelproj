<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPersonalInformationModel extends Model
{
    use HasFactory;

    protected $table = 'student_personal_informations';
    protected $fillable = ['student_id', 'civil_status', 'birth_date', 'birth_place', 'nationality', 'religion', 'religion_specify', 'baptism', 'communion', 'confirmation', 'father_alive', 'father_name', 'father_address', 'father_contactno', 'father_occupation', 'father_employer', 'father_employer_address', 'mother_alive', 'mother_name', 'mother_address', 'mother_contactno', 'mother_occupation', 'mother_employer', 'mother_employer_address', 'guardian_alive', 'guardian_name', 'guardian_relationship', 'guardian_address', 'guardian_contactno', 'guardian_occupation', 'guardian_employer', 'guardian_employer_address', 'occupation', 'occupation_years', 'employer', 'employer_address', 'employer_contact'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
