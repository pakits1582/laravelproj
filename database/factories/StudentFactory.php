<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $datetime = $this->faker->dateTimeBetween('-1 month', 'now');

        return [
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'sex' => $this->faker->numberBetween(1, 2), 
            'year_level' => $this->faker->numberBetween(1, 4), 
            'academic_status' => $this->faker->numberBetween(1, 4), 
            'created_at' => $datetime,
            'updated_at' => $datetime
        ];
    }
}
