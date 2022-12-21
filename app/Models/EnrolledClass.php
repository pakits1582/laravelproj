<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledClass extends Model
{
    use HasFactory;

    protected $table = 'enrolled_classes';
    protected $fillable = ['enrollment_id', 'class_id', 'user_id'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function addedby()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
