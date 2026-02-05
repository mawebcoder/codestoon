<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->title,
            'description' => $this->faker->text,
            'enabled' => $this->faker->boolean,
            'slug' => fn ($attributes) => Str::slug($attributes['name']),
        ];
    }
}
