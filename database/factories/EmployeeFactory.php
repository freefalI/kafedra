<?php

namespace Database\Factories;

use App\Models\Employee;
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
            // 'position_id' =>NULL,
            // 'hire_date'=>$faker->date($format = 'Y-m-d',$max='2010-01-01'),
            // 'salary'=>$faker->randomFloat($nbMaxDecimals = NULL,$min = 1000, $max = 10000),
            // 'parent_id' => NULL,
            // 'parent_name' => $this->faker->surname(),
            // 'email' => $this->faker->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            // 'remember_token' => Str::random(10),
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
