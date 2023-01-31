<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeSetup extends Model
{
    use HasFactory;

    protected $table = 'fees_setups';
    protected $fillable = ['period_id', 'educational_level_id', 'college_id', 'program_id', 'year_level', 'subject_id', 'new', 'old', 'transferee', 'cross_enrollee', 'foreigner', 'returnee', 'professional', 'fee_id', 'rate', 'payment_scheme', 'user_id'];

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id', 'id');
    }

    public function educlevel()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level_id', 'id')->withDefault(['code' => '']);
    }

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id', 'id')->withDefault(['code' => '']);
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id')->withDefault(['code' => '']);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id')->withDefault(['code' => '']);
    }
}
