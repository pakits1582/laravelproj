<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idno',
        'password',
        'utype',
        'is_active',
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

    public function userLoggedinName()
    {
        return $this->name;
    }

    public function info()
    {
        switch(Auth::user()->utype){
            case 0:
                return $this->hasOne(Userinfo::class, 'user_id', 'id');
            break;
            case 1:
                return $this->hasOne(Instructor::class, 'user_id', 'id');
            break;
            case 2:
                return $this->hasOne(Student::class, 'user_id', 'id');
            break;	
        }
    }

    

    public function access()
    {
        return $this->hasMany(Useraccess::class, 'user_id', 'id');
    } 
    
    public function setup()
    {
        $config = Configuration::take(1)->first();
        
        return $this->hasOne(SetupPeriod::class, 'user_id', 'id')->withDefault(['id' => $config->currentperiod->id, 'name' => $config->currentperiod->name]);
    }
}
