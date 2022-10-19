<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i=0; $i < 50000; $i++) { 
           $data[] = [
                'idno' => fake()->randomNumber(8, true),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'utype' => 2
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            User::insert($chunk)->each(function($user) {
                Student::factory()->create([
                    'user_id' => $user->id,
                ]);
            });
        }
        // User::factory(50000)->create(['utype' => 2])->each(function($user) {
        //     Student::factory()->create([
        //         'user_id' => $user->id,
        //     ]);
        // });
    }
}
