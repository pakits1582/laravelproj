<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentDetail extends Model
{
    use HasFactory;
    
    protected $table = 'assessment_details';

    protected $fillable = ['assessment_id', 'fee_id', 'amount'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'id');

    }

}
