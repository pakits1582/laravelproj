<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    //
    public function LoginUser($request)
    {
        $userInfo = User::where('idno', '=', $request->idno)->first();

        if (! $userInfo) {
            return back()->with(['alert-class' => 'alert-danger', 'message' => 'Sorry we do not recognized ID number!'])->withInput();
        } else {
            //check password
            if (Hash::check($request->password, $userInfo->password)) {
                $request->session()->put('LoggedUser', $userInfo->id);

                return redirect('home');
            } else {
                return back()->with(['alert-class' => 'alert-danger', 'message' => 'Incorrect password, please try again!'])->withInput();
            }
        }
    }

    public function LogoutUser()
    {
        if (session()->has('LoggedUser')) {
            session()->pull('LoggedUser');

            return redirect('/auth/login');
        }
    }
}
