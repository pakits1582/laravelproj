<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postcharge extends Model
{
    use HasFactory;
    protected $table = 'postcharges';
    protected $fillable = ['enrollment_id', 'fee_id'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);

    }

}
