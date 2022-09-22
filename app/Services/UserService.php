<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Permission;
use App\Models\User;
use App\Models\Useraccess;

class UserService
{
    //

    protected $user;

    public function returnUserAccesses($request)
    {
        // foreach ($request->access as $key => $value) {
        //     $write = $request->write;
        //     $read = $request->read;
            
        //     echo $value.'-'.$read[$key].'-'.$write[$key].'<br>';
        // }

        foreach ($request->access as $key => $access) {
            //GET USER ACCESS FROM HELPERS CLASS
            $a = Helpers::searchUSerAccess(Helpers::userAccessArray(), $access, 'link');
            $write = $request->write;
            $read = $request->read;
            //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
            $accesses[] = new Useraccess([
                'access' => $a['link'],
                'title' => $a['header'],
                'category' => $a['title'],
                'read_only' => $read[$key],
                'write_only' => $write[$key]
            ]);
        }

        return $accesses;
    }

    public function userPermissions($request)
    {
        foreach ($request->permissions as $key => $permission) {
            //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
            $permissions[] = new Permission([
                'permission' => $permission
            ]);
        }

        return $permissions;
    }
}
