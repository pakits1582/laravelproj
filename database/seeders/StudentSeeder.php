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
                'user_id' => User::factory()->create()->id,
                'last_name' => fake()->lastName(),
                'first_name' => fake()->firstName(),
                'middle_name' => fake()->lastName(),
                'sex' => fake()->numberBetween(1, 2), 
                'year_level' => fake()->numberBetween(1, 4), 
                'academic_status' => fake()->numberBetween(1, 4), 
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            Student::insert($chunk);
        }
        // User::factory(50000)->create(['utype' => 2])->each(function($user) {
        //     Student::factory()->create([
        //         'user_id' => $user->id,
        //     ]);
        // });
    }
}
