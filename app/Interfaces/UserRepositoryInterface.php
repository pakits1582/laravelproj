<?php

namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function getAllUsers();
    public function createUser(array $UserDetails);
    public function getUserById($userId);
    public function getUserinfo($userId);
    public function getUseraccess($userId);
    
    // public function checkDuplicateOnUpdate(array $array);
    // public function updateUser($UserId, array $newDetails);
    // public function deleteUser($UserId);
}
