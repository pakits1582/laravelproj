<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassesSchedule extends Model
{
    use HasFactory;

    public function roominfo()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function classinfo()
    {
        return $this->belongsTo(Classes::class, 'classes_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }


}
