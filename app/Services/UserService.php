<?php

namespace App\Services;

use App\Models\User;

//use App\Http\Requests\UserFormRequest;
use App\Libs\Helpers;
use App\Models\Userinfo;
use App\Models\Useraccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserFormRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    //
    
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user; 
    } 

    public function allUsers()
    {
        return $this->user->getAllUsers();
    }

    public function saveUser($request)
    {
        $validated = array_merge($request->safe()->only(['idno']), ['password' => Hash::make('password')]);
        //INSERT NEW RECORD IN USERS TABLE
        $insert = $this->user->createUser($validated);
        //IF SUCCESSFUL INSERT, ADD USER IN USERINFO AND USER ACCESS TABLE
        if($insert->wasRecentlyCreated){
            $userid = $insert->id;
            //INSERT NAME IN USERINFO TABLE
            Userinfo::create(['user_id' => $userid, 'name' => $request->name]);
            //LOOP THROUGH ALL USERACCESS SELECTED AND ADD TO USERS_ACCESS TABLE
            $accessArray = [];
            foreach ($request->access as $key => $access) {
                //GET USER ACCESS FROM HELPERS CLASS
                $a = Helpers::searchUSerAccess(Helpers::userAccessArray(), $access, 'link');
                //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
                $accessArray[] = array('user_id' => $userid, 'access' => $a['link'], 'title' => $a['header'], 'category' => $a['title']);
            }
            //INSERT INTO USER ACCESS TABLE
            if($accessArray){
                Useraccess::insert($accessArray);
            }

            return back()->with(['alert-class' => 'alert-success', 'message' => 'User sucessfully added!']);
        }else{
            return back()->with(['alert-class' => 'alert-danger', 'message' => 'Something went wrong unable to save data!']) ->withInput();
        }
    }

    public function editUser($userId)
    {
        try{
            //METHOD 1
            //GET USER INFO
            $userdetails = $this->user->getUserById($userId);
            //GET USER INFO
            $userinfo    = $this->user->getUserinfo($userId);
            //GET USER ACCESS
            $useraccess  = $this->user->getUseraccess($userId);
            
            return view('user.edit', [
                    'userinfo' => $userinfo, 
                    'userdetails' => $userdetails, 
                    'useraccess' => $useraccess]
                );

            //METHOD 2
            //GET USER INFO
            $userdetails = $this->user->getUserById($userId);
            $userdetails->load('info','access');
            return view('user.edit', ['userdetails' => $userdetails]);

            //METHOD 3
            $userdetails = User::with('info','access')->find($userId);
            return view('user.edit', ['userdetails' => $userdetails]);
            
        }
        catch(ModelNotFoundException $e)
        {
            return redirect()->route('userindex');
        }
    }

    public function updateUser($request, $user)
    {
        dd($user);
        //     $sch = $this->User->checkDuplicateOnUpdate(
        //     [
        //         ['code', '=', $request->code],
        //         ['name', '=', $request->name],
        //         ['id', '<>', $UserId]
        //     ]);

        // if($sch){
        //     return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, User already exists!']);
        // }else{
        //     $this->User->updateUser(['id' => $UserId], $request->validated());
        //     return back()->with(['alert-class' => 'alert-success', 'message' => 'User sucessfully updated!']);
        // }
    }


   
}
