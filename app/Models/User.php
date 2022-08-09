<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idno',
        'password',
        'utype',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function allusers()
    {
        return $this->select('users.id', 'idno', 'is_active', 'utype', 'userinfo.id AS userinfoid','userinfo.name')
        ->leftJoin('userinfo', 'userinfo.user_id', '=', 'users.id')
        ->get();
    }

    public function userLoggedinName()
    {
        return $this->name;
    }
    
    public function info()
    {
        return $this->hasOne(Userinfo::class);
    }

    public function access()
    {
        return $this->hasMany(Useraccess::class);
    }
}
