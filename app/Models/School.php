<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'address', 'added_by'];

    public function allschools()
    {
        return $this->select('schools.name', 'code', 'address','schools.id','userinfo.name AS addedby')
        ->leftJoin('users', 'users.id', '=', 'schools.added_by')
        ->leftJoin('userinfo', 'userinfo.user_id', '=', 'users.id')
        ->get();
    }

    // public function insertWithcheckdupli($array, $formfields)
    // {
    //     return $this->firstOrCreate($array, $formfields);
    // }

    // public function checkDuplicateOnUpdate($array)
    // {
    //     return $this->where($array)->first();
    // }

    // public function updateSchool($fields, $where)
    // {
    //     return $this->where($where)->update($fields);
    // }

    // public function findSchool($id)
    // {
    //     return $this->find($id);
    // }


}
