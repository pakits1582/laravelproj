<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentBreakdown extends Model
{
    use HasFactory;

    protected $table = 'assessment_breakdowns';

    protected $fillable = ['assessment_id', 'fee_type_id', 'amount'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'id');

    }

    public function fee_type()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id', 'id');

    }
}
