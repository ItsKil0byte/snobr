<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()->count(10)->create();
        $categories = Category::factory()->count(5)->create();

        $posts = Post::factory()->count(25)->recycle([$users, $categories])->create();

        Comment::factory()->count(10)->recycle([$users, $posts])->create();
    }
}
