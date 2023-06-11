<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAssignment extends Model
{
    use HasFactory;

    protected $table = 'other_assignments';
    protected $fillable = ['period_id', 'instructor_id', 'assignment', 'units', 'user_id'];

    
}
