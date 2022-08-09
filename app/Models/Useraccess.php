<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Useraccess extends Model
{
    use HasFactory;

    protected $table = 'user_access';

    protected $fillable = [
        'user_id',
        'access',
        'title',
        'category',
        'read_only',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
