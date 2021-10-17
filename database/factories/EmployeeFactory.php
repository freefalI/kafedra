<?php

namespace Database\Factories;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $faker = FakerFactory::create('uk_UA');
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'name' => $this->faker->firstName($gender),
            'surname' => $this->faker->lastName($gender),
            'parent_name'=>$this->faker->middleName($gender),
            'hire_date'=>$this->faker->date($format = 'Y-m-d',$max=now()),
            'email' => $this->faker->unique()->safeEmail(),
            'dob' => Carbon::createFromDate($this->faker->dateTimeBetween('-60 years', '-30 years'))->toDateString(),
            'phone' => $this->faker->e164PhoneNumber(),
            'employment_id' => random_int(11111111, 99999999),
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
