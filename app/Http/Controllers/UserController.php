<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Userinfo;
use App\Models\Useraccess;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService; 

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        Helpers::setLoad(['jquery_user.js']);	
    } 

    public function index()
    {
        //$users =  $this->userService->allUsers();
        $users = User::all();
        $users->load('info', 'access');

        return view('user.index', compact('users'));
        
    }

    public function create()
    {
        return view('user.create');

    }

    public function store(UserFormRequest $request)
    {
        $alertCLass = 'alert-success';
        $alertMessage = 'User sucessfully added!';
        //try {
            DB::beginTransaction();

            $user = new User([
                'idno' => $request->idno,
                'password' => Hash::make('password')
            ]);

            $info = new Userinfo(['name' => $request->name]);
            $user->info()->save($info);

            //LOOP THROUGH ALL USERACCESS SELECTED AND ADD TO USERS_ACCESS TABLE
            foreach ($request->access as $key => $access) {
                //GET USER ACCESS FROM HELPERS CLASS
                $a = Helpers::searchUSerAccess(Helpers::userAccessArray(), $access, 'link');
                //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
                $accesses[] = new Useraccess([
                    'access'   => $a['link'], 
                    'title'    => $a['header'], 
                    'category' => $a['title']
                ]);
            }
            //INSERT INTO USER ACCESS TABLE
            $user->access()->saveMany($accesses);

            DB::commit();
        // } catch (\Exception $e) {
        //     Log::error(get_called_class(), [
        //         //'createdBy' => $user->userLoggedinName(),
        //         'body'  => $request->all(),
        //         'error' => $e->getMessage(),
        //         'line'  => $e->getLine()
        //     ]);
        // }

        return back()->with(['alert-class' => $alertCLass, 'message' => $alertMessage]);
        // $validated = array_merge($request->safe()->only(['idno']), ['password' => Hash::make('password')]);
        // //INSERT NEW RECORD IN USERS TABLE
        // $insert= User::create($validated);
        // $userid = $insert->id;
        // //IF SUCCESSFUL INSERT, ADD USER IN USERINFO AND USER ACCESS TABLE
        // if($userid){
        //     //INSERT NAME IN USERINFO TABLE
        //     Userinfo::create(['user_id' => $userid, 'name' => $request->name]);
        //     //LOOP THROUGH ALL USERACCESS SELECTED AND ADD TO USERS_ACCESS TABLE
        //     $accessArray = [];
        //     foreach ($request->access as $key => $access) {
        //         //GET USER ACCESS FROM HELPERS CLASS
        //         $a = Helpers::searchUSerAccess(Helpers::userAccessArray(), $access, 'link');
        //         //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
        //         $accessArray[] = array('user_id' => $userid, 'access' => $a['link'], 'title' => $a['header'], 'category' => $a['title']);
        //     }
        //     //INSERT INTO USER ACCESS TABLE
        //     if($accessArray){
        //         Useraccess::insert($accessArray);
        //     }

        //     return back()->with(['alert-class' => 'alert-success', 'message' => 'User sucessfully added!']['alert-class' => 'alert-success', 'message' => 'User sucessfully added!']);
        // }else{
        //     return back()->with(['alert-class' => 'alert-danger', 'message' => 'Something went wrong unable to save data!']) ->withInput();
        // }
       // return  $this->userService->saveUser($request);
        
    }

    public function edit(User $user)
    {
        try{
            $user->load('info','access');
            return view('user.edit', ['userdetails' => $user]);
            
        }
        catch(ModelNotFoundException $e)
        {
            return redirect()->route('userindex');
        }
        //return  $this->userService->editUser($userId);
    
    }

    public function update(UserFormRequest $request, User $user)
    {
        $user->load('info','access');
        dd($user);
        //return  $this->UserService->updateUser($request, $user);

    }

    // public function show(User $User)
    // {
    //     //
    // }
   
    // public function destroy(User $User){

    //     $User->delete();
        
    //     return redirect()->route('Userindex')->with(['alert-class' => 'alert-success', 'message' => 'User sucessfully deleted!']);
    // }
}
