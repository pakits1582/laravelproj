<?php

namespace App\Services;

use App\Models\Configuration;
use App\Models\ConfigurationSchedule;

class ConfigurationService
{
    //
    public function updateConfiguration($request, $configuration)
    {
        $signatures = [];
        if ($request->hasFile('pres_sig')) {
            $pres_signame = $request->file('pres_sig')->getClientOriginalName();
            $signatures['pres_sig'] = $pres_signame;
            $request->file('pres_sig')->move(public_path('images'), $pres_signame);
        }

        if ($request->hasFile('reg_sig')) {
            $reg_signame = $request->file('reg_sig')->getClientOriginalName();
            $signatures['reg_sig'] = $reg_signame;
            $request->file('reg_sig')->move(public_path('images'), $reg_signame);
        }

        if ($request->hasFile('tres_sig')) {
            $tres_signame = $request->file('tres_sig')->getClientOriginalName();
            $signatures['tres_sig'] = $tres_signame;
            $request->file('tres_sig')->move(public_path('images'), $tres_signame);
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->getClientOriginalName();
            $signatures['logo'] = $logo;
            $request->file('logo')->move(public_path('images'), $logo);
        }

        if (!$configuration) {
            Configuration::create($request->safe()->except(['pres_sig', 'reg_sig', 'tres_sig', 'logo']) + $signatures);
        }
        
        Configuration::where('id', $configuration)->update($request->safe()->except(['pres_sig', 'reg_sig', 'tres_sig', 'logo']) + $signatures);
    }

    public function recordAuditLog()
    {
        
    }

    public function configurationSchedule($period, $type)
    {
        $query = ConfigurationSchedule::query();
        $query->where('period_id', $period);
        $query->where('type', $type);

        return $query->get();
    }
}
