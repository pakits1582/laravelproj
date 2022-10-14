<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassesSchedule extends Model
{
    protected $fillable = ['class_id', 'from_time', 'to_time', 'day', 'room_id', 'schedule_id'];

    use HasFactory;

    public function roominfo()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function classinfo()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }


}
