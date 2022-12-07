<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledClassSchedule extends Model
{
    use HasFactory;

    protected $table = 'enrolled_class_schedules';
    protected $fillable = ['enrollment_id', 'class_id', 'day', 'room'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
