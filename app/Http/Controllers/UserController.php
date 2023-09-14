<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Services\ProgramService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        Helpers::setLoad(['jquery_user.js', 'select2.full.min.js']);
    }

    public function index(Request $request)
    {
        $users = $this->userService->returnUsers($request);
        
        if($request->ajax()){
            return view('user.return_users', compact('users'));
        }
        return view('user.index', compact('users'));
    }

    public function create(Request $request)
    {
        $programs = (new ProgramService())->returnPrograms($request, true, true);

        return view('user.create', compact('programs'));
    }

    public function store(StoreUserRequest $request)
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

    public function edit(User $user, Request $request)
    {
        try {
            $programs = (new ProgramService())->returnPrograms($request, true, true);

            $user->load('info', 'access', 'permissions', 'accessibleprograms');
            return view('user.edit', ['userdetails' => $user, 'programs' => $programs]);

        } catch (ModelNotFoundException $e) {
            return redirect()->route('userindex');
        }
    }

    public function update(UpdateUserRequest $request, User $user)
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
