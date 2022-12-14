<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    const SOURCE_INTERNAL = 1;
    const SOURCE_EXTERNAL = 2;
    
    protected $fillable = ['term', 'type', 'source'];
}
