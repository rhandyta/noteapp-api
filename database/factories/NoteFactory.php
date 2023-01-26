<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

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
        $title = fake()->sentence(6, true);
        return [
            'user_id' => fake()->randomElement([1, 2]),
            'title' => $title,
            'body' => fake()->text(300),
            'visible' => fake()->randomElement([1, 0]),
            'archive' => fake()->randomElement([1, 0]),
            'key' => Uuid::uuid4(),
            'slug' => \Str::slug($title)
        ];
    }
}
