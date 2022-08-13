<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\User;
use App\Models\Useraccess;

class UserService
{
    //

    protected $user;

    public function returnUserAccesses($userAccesses)
    {
        // //LOOP THROUGH ALL USERACCESS SELECTED AND ADD TO USERS_ACCESS TABLE
        foreach ($userAccesses as $key => $access) {
            //GET USER ACCESS FROM HELPERS CLASS
            $a = Helpers::searchUSerAccess(Helpers::userAccessArray(), $access, 'link');
            //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
            $accesses[] = new Useraccess([
                'access' => $a['link'],
                'title' => $a['header'],
                'category' => $a['title'],
            ]);
        }

        return $accesses;
    }
}
