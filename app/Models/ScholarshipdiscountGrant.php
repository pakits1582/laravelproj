<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipdiscountGrant extends Model
{
    use HasFactory;

    protected $table = 'scholarshipdiscount_grants';
    protected $fillable = ['enrollment_id', 'scholarshipdiscount_id', 'tuition', 'miscellaneous', 'othermisc', 'laboratory', 'totalassessment', 'totaldeduction', 'user_id'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function scholarshipdiscount()
    {
        return $this->belongsTo(Scholarshipdiscount::class);
    }
}
