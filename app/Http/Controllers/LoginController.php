<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    protected function redirectTo()
    {
        return redirect('/home');
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        return view('auth.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'idno' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('idno', 'password');
        if (Auth::attempt($credentials + ['is_active' => 1])) {
            //SET SESSIONS HERE
            if (is_null(Auth::user()->setup->period)) {
                session()->put('current_period', Auth::user()->setup->id);
                session()->put('periodname', Auth::user()->setup->name);
                session()->put('periodterm', Auth::user()->setup->term);
            } else {
                session()->put('current_period', Auth::user()->setup->period->id);
                session()->put('periodname', Auth::user()->setup->period->name);
                session()->put('periodterm', Auth::user()->setup->period->term_id);
            }

            return redirect()->intended('home');
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Sorry we didn\'t recognized your login details. Please check idno and password and try again!'])->withInput();
    }

    public function home()
    {
        if (Auth::check()) {
            return view('home');
        }

        return redirect('/');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('/');
    }
}
