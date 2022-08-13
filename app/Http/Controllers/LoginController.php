<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Services\LoginService;

class LoginController extends Controller
{
    protected $log;

    public function __construct(LoginService $login)
    {
        $this->log = $login;
    }

    public function login(LoginFormRequest $request)
    {
        return $this->log->LoginUser($request);
    }

    public function logout()
    {
        return $this->log->LogoutUser();
    }

    public function changepassword()
    {
        return view('auth.changepassword');
    }

    public function savechangepassword()
    {
    }
}
