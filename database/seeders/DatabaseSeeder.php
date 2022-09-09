<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Useraccess;
use App\Models\Userinfo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        //\App\Models\School::factory(10)->create();

        User::create([
            'idno' => '19640486',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'utype' => 0,
            'is_active' => 1,
        ]);

        Userinfo::create([
            'user_id' => 1,
            'name' => 'RAYMOND CHIRSTIAN A. DUCUSIN',
        ]);

        Useraccess::create([
            'user_id' => 1,
            'access' => 'users',
            'title' => 'User Accounts',
            'category' => 'General',
        ]);
    }
}
