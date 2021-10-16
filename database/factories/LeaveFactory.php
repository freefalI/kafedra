<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeaveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model =Leave::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dateFrom = now()->subDays(rand(1, 10));
        $dateTo = $dateFrom->clone()->addDays(rand(1, 7));
        return [
            'title' => $this->faker->sentence(),
            'type' => Leave::getTypes()[\array_rand(Leave::getTypes())],
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'reason' => $this->faker->sentence(),
            'is_approved' => $this->faker->boolean(),
            'employee_id' => Employee::inRandomOrder()->first()->id
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
