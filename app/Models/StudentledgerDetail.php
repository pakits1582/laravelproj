<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentledgerDetail extends Model
{
    use HasFactory;

    protected $table = 'studentledger_details';
    protected $fillable = ['studentledger_id', 'fee_id', 'amount'];

    public function studentledger()
    {
        return $this->belongsTo(Studentledger::class);
    }
}
