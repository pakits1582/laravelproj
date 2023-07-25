<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserContactInformationModel extends Model
{
    use HasFactory;
    protected $table = 'user_contact_informations';
    protected $fillable = ['user_id', 'current_region', 'current_province', 'current_municipality', 'current_barangay', 'current_address', 'current_zipcode', 'permanent_region', 'permanent_province', 'permanent_municipality', 'permanent_barangay', 'permanent_address', 'permanent_zipcode', 'email', 'mobileno', 'telno', 'notify_person', 'notify_contactno', 'notify_address'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
