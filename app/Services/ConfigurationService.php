<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Configuration;

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

        if(!$configuration){
            Configuration::create($request->safe()->except(['pres_sig', 'reg_sig', 'tres_sig'])+$signatures);
        }
        Configuration::where("id", $configuration)->update($request->safe()->except(['pres_sig', 'reg_sig', 'tres_sig'])+$signatures);
    }
}
