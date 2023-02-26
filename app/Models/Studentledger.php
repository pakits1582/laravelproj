<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studentledger extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id', 'source_id', 'type', 'amount'];
    
}
