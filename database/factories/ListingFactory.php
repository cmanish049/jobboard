<?php

namespace Database\Factories;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        $datetime = $this->faker->dateTimeBetween('-1 month', 'now');
        $content = '';
        for ($i= 0; $i < 5; $i++) {
            $content .= '<p class="mb-4">'. $this->faker->sentences(random_int(5, 10), true);
        }
        return [
            'title' => $title = $this->faker->sentence(random_int(5, 7)),
            'slug' => Str::slug($title). '-'. random_int(1111, 9999),
            'company' => $this->faker->company,
            'location' => $this->faker->country,
            'logo' => basename($this->faker->image(storage_path('app/public'))),
            'is_highlighted' => (random_int(1, 9) > 7),
            'is_active' => true,
            'content' => $content,
            'apply_link' => $this->faker->url,
            'created_at' => $datetime,
            'updated_at' => $datetime
        ];
    }
}
