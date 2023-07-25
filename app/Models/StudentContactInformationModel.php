<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentContactInformationModel extends Model
{
    use HasFactory;
    
    protected $table = 'student_contact_informations';
    protected $fillable = ['student_id', 'current_region', 'current_province', 'current_municipality', 'current_barangay', 'current_address', 'current_zipcode', 'permanent_region', 'permanent_province', 'permanent_municipality', 'permanent_barangay', 'permanent_address', 'permanent_zipcode', 'email', 'mobileno', 'telno', 'notify_person', 'notify_contactno', 'notify_address'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
