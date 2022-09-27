<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserFormRequest;
use App\Http\Requests\UserUpdateFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        Helpers::setLoad(['jquery_user.js']);
    }

    public function index(Request $request)
    {
        $users = $this->userService->returnUsers($request);
        
        if($request->ajax()){
            return view('user.return_users', compact('users'));
        }
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(UserFormRequest $request)
    {
        try {
            
            $this->userService->createAdminUser($request);
           
        } catch (\Exception $e) {
            Log::error(get_called_class(), [
                //'createdBy' => $user->userLoggedinName(),
                'body' => $request->all(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User sucessfully added!',
            'alert' => 'alert-success'
        ], 200);
    }

    public function edit(User $user)
    {
        try {

            $user->load('info', 'access', 'permissions');
            return view('user.edit', ['userdetails' => $user]);

        } catch (ModelNotFoundException $e) {
            return redirect()->route('userindex');
        }
    }

    public function update(UserUpdateFormRequest $request, User $user)
    {      
        try {
        
            $this->userService->updateUser($user, $request);
            
        } catch (\Exception $e) {
            Log::error(get_called_class(), [
                //'createdBy' => $user->userLoggedinName(),
                'body' => $request->all(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User sucessfully updated!',
            'alert' => 'alert-success'
        ], 200);
    }

    // public function show(User $User)
    // {
    //     //
    // }

    // public function destroy(User $User){

    //     $User->delete();

    //     return redirect()->route('Userindex')->with(['alert-class' => 'alert-success', 'message' => 'User sucessfully deleted!']);
    // }

    public function useraction(User $user, $action)
    {
        $this->userService->userAction($user, $action);

        return response()->json(['success' => true, 'alert' => 'alert-success', 'message' => 'Selected action successfully excuted!']);
    }
}
