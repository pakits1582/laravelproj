<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    protected function redirectTo()
    {
        return redirect("/home");
    }

    public function index()
    {
        if(Auth::check()){
            return redirect("/home");
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
        if (Auth::attempt($credentials+['is_active' => 1])) {
            // The user is active, not suspended, and exists.
            return redirect()->intended('home');
        }
  
        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Sorry we didn\'t recognized your login details. Please check idno and password and try again!'])->withInput();
    }
      
    public function home()
    {
        if(Auth::check()){
            return view('home');
        }
  
        return redirect("/");
    }
    
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return redirect("/");
    }


}