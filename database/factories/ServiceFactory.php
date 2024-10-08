<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(20),
            'eligibility' => fake()->sentence(15),
            'requirements' => fake()->paragraph(3),
            'numberOfRequirements' => fake()->numberBetween(4, 6),
            'status' => fake()->randomElement(Service::$serviceStatus)
        ];
    }
}
