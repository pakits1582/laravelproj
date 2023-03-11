<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studentledger extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id', 'source_id', 'type', 'amount', 'user_id'];
    

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);   
    }

    public function details()
    {
        return $this->hasMany(StudentledgerDetail::class, 'studentledger_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
}
