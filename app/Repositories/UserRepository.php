<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAllUsers()
    {
        return $this->model->allusers();
    }

    public function createUserwithduplicheck(array $arr, array $UserDetails)
    {
        return User::firstOrCreate($arr, $UserDetails);
    }

    public function createUser(array $UserDetails)
    {
        return User::create($UserDetails);
    }

    public function getUserById($UserId)
    {
        return User::findOrFail($UserId);
    }

    public function getUSerinfo($UserId)
    {
        return User::find($UserId)->info;
    }

    public function getUseraccess($UserId)
    {
        return User::find($UserId)->access;
    }

    public function getUserByIdno($idno)
    {
        return User::where('idno', $idno)->firstOrFail();
    }

    // public function checkDuplicateOnUpdate($array)
    // {
    //     return User::where($array)->first();
    // }

    // public function updateUser($UserId, array $newDetails)
    // {
    //     return User::whereId($UserId)->update($newDetails);
    // }

    // public function deleteUser($UserId)
    // {
    //     User::destroy($UserId);
    // }
}
