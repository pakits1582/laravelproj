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

    const TYPE_ADMIN = 0;
    const TYPE_INSTRUCTOR = 1;
    const TYPE_STUDENT = 2;

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

     /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function userLoggedinName($query)
    {
        return $this->name;
    }

    public function info()
    {
        return $this->hasOne(Userinfo::class, 'user_id', 'id');
    }

    public function instructorinfo()
    {
        return $this->hasOne(Instructor::class, 'user_id', 'id');
    }

    public function studentinfo()
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    public function scopeAdmin($query)
    {
        return $query->where('utype', self::TYPE_ADMIN);
    }

    // public function scopeDean($query)
    // {  
    //     return $query->info->where('designation', Instructor::TYPE_DEAN);
    // }

    // public function scopeProgramHead($query)
    // {
    //     return $query->info->where('designation', Instructor::TYPE_PROGRAM_HEAD);
    // }


    public function access()
    {
        return $this->hasMany(Useraccess::class, 'user_id', 'id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'user_id', 'id');
    }

    public function setup()
    {
        $config = Configuration::take(1)->first();

        return $this->hasOne(SetupPeriod::class, 'user_id', 'id')->withDefault(['id' => $config->currentperiod->id ?? '', 'name' => $config->currentperiod->name ?? '']);
    }
}
