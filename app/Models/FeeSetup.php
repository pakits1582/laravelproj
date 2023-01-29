<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeSetup extends Model
{
    use HasFactory;

    protected $table = 'fees_setups';
    protected $fillable = ['period_id', 'educational_level_id', 'college_id', 'program_id', 'year_level', 'subject_id', 'new', 'old', 'transferee', 'cross_enrollee', 'foreigner', 'returnee', 'professional', 'fee_id', 'rate', 'payment_scheme', 'user_id'];

}
