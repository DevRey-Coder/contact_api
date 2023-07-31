<?php

namespace Database\Factories;

use Faker\Provider\mn_MN\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'country_code' => "95",
            'phone_number' => fake()->PhoneNumber,
            'email' => fake()->email()
        ];
    }
}
