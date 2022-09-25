<?php

namespace App\Services;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Permission;
use App\Models\Useraccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    //

    protected $user;

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

        $accesses = $this->returnUserAccesses($request, $user->utype);
        $user->access()->saveMany($accesses);

        $permissions = $this->userPermissions($request);
        $user->permissions()->saveMany($permissions);

        return DB::commit();

    }

    public function returnUserAccesses($request, $utype = User::TYPE_ADMIN)
    {
        // foreach ($request->access as $key => $value) {
        //     $write = $request->write;
        //     $read = $request->read;
            
        //     echo $value.'-'.$read[$key].'-'.$write[$key].'<br>';
        // }
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

        if($utype === User::TYPE_INSTRUCTOR)
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
        }

        return $accesses;
    }

    public function userPermissions($request)
    {
        if($request->permissions)
        {
            foreach ($request->permissions as $key => $permission) {
                //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
                $permissions[] = new Permission([
                    'permission' => $permission
                ]);
            }

            return $permissions;
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
                    $query->where('instructors.last_name', 'like', '%'.$request->keyword.'%');
                    $query->orwhere('instructors.middle_name', 'like', '%'.$request->keyword.'%');
                    $query->orwhere('instructors.first_name', 'like', '%'.$request->keyword.'%');
                    $query->orwhere('users.idno', 'like', '%'.$request->keyword.'%');
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
                    $query->where('students.last_name', 'like', '%'.$request->keyword.'%');
                    $query->orwhere('students.middle_name', 'like', '%'.$request->keyword.'%');
                    $query->orwhere('students.first_name', 'like', '%'.$request->keyword.'%');
                    $query->orwhere('users.idno', 'like', '%'.$request->keyword.'%');
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
                    $query->where('userinfo.name', 'like', '%'.$request->keyword.'%');
                    $query->orwhere('users.idno', 'like', '%'.$request->keyword.'%');
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
