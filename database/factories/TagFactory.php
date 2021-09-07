<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;


    public function definition(): array
    {
        return [
            'name' => $name = ucwords($this->faker->word),
            'slug' => Str::slug($name)
        ];
    }
}
