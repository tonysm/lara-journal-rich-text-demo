<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->withPersonalTeam()
            ->has(Post::factory()->times(5))
            ->create([
                'name' => 'Tony Messias',
                'email' => 'tonysm@hey.com',
            ]);

        User::factory(10)
            ->withPersonalTeam()
            ->has(Post::factory()->times(5))
            ->create();
    }
}
