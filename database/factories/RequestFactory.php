<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceId = Service::inRandomOrder()->first()->id;


        return [
            'firstname' => fake()->firstName(),
            'middlename' => fake()->lastName(),
            'lastname' => fake()->lastName(),
            'address' => fake()->address(),
            'contact' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'status' => 'For review',
            'message' => '',
            'service_id' => $serviceId,
            'user_id' => null,
        ];
    }
}
