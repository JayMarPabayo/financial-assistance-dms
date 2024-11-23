<?php

namespace Database\Factories;

use App\Models\Request;
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
            'name_extension' => fake()->randomElement(array_merge([''], Request::$nameExtensions)),
            'deceased_person' => '',
            'birthdate' => fake()->date('Y-m-d', '-18 years'),
            'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
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
