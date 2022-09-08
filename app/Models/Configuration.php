<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'contactno', 'address', 'accronym', 'president', 'pres_sig', 'pres_initials', 'registrar', 'reg_sig', 'reg_initials', 'treasurer', 'tres_sig', 'tres_initials', 'balanceallowed', 'due', 'note', 'current_period', 'application_period', 'datefrom', 'dateto', 'status', 'announcement'];

    public function currentperiod()
    {
        return $this->belongsTo(Period::class, 'current_period', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function applicationperiod()
    {
        return $this->belongsTo(Period::class, 'application_period', 'id')->withDefault(['code' => '', 'name' => '']);
    }
}
