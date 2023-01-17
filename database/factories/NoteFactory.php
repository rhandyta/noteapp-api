<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => fake()->randomElement([1,2]),'title' => fake()->sentence(6, true),'body' => fake()->text(150),'visible' => fake()->randomElement([1,0]),'archive' => fake()->randomElement([1,0]),
        ];
    }
}
