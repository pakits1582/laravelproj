<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\AccessibleProgram;
use App\Models\User;
use App\Models\Userinfo;
use App\Models\Permission;
use App\Models\Useraccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    //

    protected $user;

    public function createAdminUser($request)
    {
        DB::beginTransaction();

        $user = $this->createUser($request, User::TYPE_ADMIN);
                $this->createUserinfo($user, $request);
                $this->adminAccesses($user, $request);
                $this->userPermissions($user, $request);
                $this->userAccessiblePrograms($user, $request);

        DB::commit();
    }

    public function updateUser($user, $request)
    {
        DB::beginTransaction();

        if($user->utype === User::TYPE_ADMIN)
        {
            $user->info->name = $request->name;
            $user->info->save();
        }

        Useraccess::where('user_id', $user->id)->delete();
        Permission::where('user_id', $user->id)->delete();
        AccessibleProgram::where('user_id', $user->id)->delete();

        $this->adminAccesses($user, $request);
        $this->userPermissions($user, $request);
        $this->userAccessiblePrograms($user, $request);

        if($user->utype === User::TYPE_INSTRUCTOR)
        {
            $this->instructorAccesses($user);
        }

        DB::commit();
    }

    public function createUser($request, $usertype)
    {
        $user = User::create([
            'idno' => $request->idno,
            'password' => Hash::make('password'),
            'utype' => $usertype,
        ]);

        return $user;
    }

    public function createUserinfo($user, $request)
    {
        $info = new Userinfo(['name' => $request->name]);
        $user->info()->save($info);
    }

    public function adminAccesses($user, $request)
    {
        $accesses = [];

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

        return $user->access()->saveMany($accesses);
    }

    public function instructorAccesses($user)
    {
        foreach (Helpers::instructorDefaultAccesses() as $key => $access) {
            $accesses[] = new Useraccess([
                'access' => $access['access'],
                'title' => $access['title'],
                'category' => $access['category'],
                'read_only' => 1,
                'write_only' => 1
            ]);
        }

        return $user->access()->saveMany($accesses);
    }

    public function studentAccesses()
    {
        foreach (Helpers::studentDefaultAccesses() as $key => $access) {
            $accesses[] = new Useraccess([
                'access' => $access['access'],
                'title' => $access['title'],
                'category' => $access['category'],
            ]);
        }

        return $accesses;
    }

    public function userPermissions($user, $request)
    {
        if($request->permissions)
        {
            foreach ($request->permissions as $key => $permission) {
                //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
                $permissions[] = new Permission([
                    'permission' => $permission
                ]);
            }

            return $user->permissions()->saveMany($permissions);
        }

       return [];
    }

    public function userAccessiblePrograms($user, $request)
    {
        if($request->accessible_programs)
        {
            foreach ($request->accessible_programs as $key => $accessible_program) {
                //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
                $accessible_programs[] = new AccessibleProgram([
                    'program_id' => $accessible_program
                ]);
            }

            return $user->accessibleprograms()->saveMany($accessible_programs);
        }

       return [];
    }

    public function returnUsers($request, $all = false)
    {
        $query = User::query();
        switch ($request->type) {
            case 1:
                $query->select('users.idno',
                                'users.id AS userid',
                                'users.is_active',
                                'users.utype',
                                DB::raw("CONCAT(instructors.last_name,', ',instructors.first_name,' ',instructors.middle_name) as name"))
                    ->join('instructors', 'users.id', 'instructors.user_id')
                    ->orderBy('instructors.last_name');
                if($request->has('keyword') && !empty($request->keyword))
                {
                    $query->where(function($query) use($request){
                        $query->where('instructors.last_name', 'like', '%'.$request->keyword.'%');
                        $query->orwhere('instructors.middle_name', 'like', '%'.$request->keyword.'%');
                        $query->orwhere('instructors.first_name', 'like', '%'.$request->keyword.'%');
                        $query->orwhere('users.idno', 'like', '%'.$request->keyword.'%');
                    });
                }
                break;
            case 2:
                $query->select('users.idno',
                                'users.id AS userid',
                                'users.is_active',
                                'users.utype',
                                DB::raw("CONCAT(students.last_name,', ',students.first_name,' ',students.middle_name) as name"))
                    ->join('students', 'users.id', 'students.user_id')
                    ->orderBy('students.last_name');
                if($request->has('keyword') && !empty($request->keyword))
                {
                    $query->where(function($query) use($request){
                        $query->where('students.last_name', 'like', '%'.$request->keyword.'%');
                        $query->orwhere('students.middle_name', 'like', '%'.$request->keyword.'%');
                        $query->orwhere('students.first_name', 'like', '%'.$request->keyword.'%');
                        $query->orwhere('users.idno', 'like', '%'.$request->keyword.'%');
                    });
                }
                break;
            default:
                $query->select('users.idno',
                                'users.id AS userid',
                                'users.is_active',
                                'users.utype',
                                'userinfo.*')
                    ->join('userinfo', 'users.id', 'userinfo.user_id')
                    ->orderBy('userinfo.name');
                if($request->has('keyword') && !empty($request->keyword))
                {
                    $query->where(function($query) use($request){
                        $query->where('userinfo.name', 'like', '%'.$request->keyword.'%');
                        $query->orwhere('users.idno', 'like', '%'.$request->keyword.'%');
                    });
                }
                break;
        }

        if($request->has('status') && ($request->status == '0' || $request->status == '1')) {
            $query->where('is_active', $request->status);
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
    }

    public function userAction($user, $action)
    {
        switch ($action) {
            case 'activate':
                $arr = ['is_active' => 1];
                break;
            case 'deactivate':
                $arr = ['is_active' => 0];
                break;
            case 'reset':
                $arr = ['password' => Hash::make('password')];
                break;
        }

        return $user->update($arr);
    }
}
