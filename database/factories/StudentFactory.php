<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'name' => $this->faker->name($gender),
            'dob' => Carbon::createFromDate($this->faker->dateTimeBetween('-22 years', '-16 years'))->toDateString(),
            'enter_year' => random_int(now()->year - 6, now()->year),
            'phone' => $this->faker->e164PhoneNumber(),
            'student_id' => random_int(11111111, 99999999),
            'birth_address' => $this->faker->address(),
            'parent_phone' => $this->faker->e164PhoneNumber(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
