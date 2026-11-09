<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\User;
use App\Support\Constants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by_id' => User::factory(),
            'name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->randomElement(Constants::usStateCodes()),
            'zip' => fake()->postcode(),
            'logo_url' => null,
            'website_url' => null,
            'status' => 'P',
            'approved_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (): array => [
            'status' => 'A',
            'approved_at' => now(),
        ]);
    }
}
