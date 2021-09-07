<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $tags = Tag::factory(10)->create();

        User::factory(20)->create()->each(function (User $user) use($tags) {
            Listing::factory(random_int(1, 4))->create(['user_id' => $user->id])->each(function(Listing $listing) use($tags) {
                $listing->tags()->attach($tags->random(2));
            });
        });
    }
}
