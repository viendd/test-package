<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Language;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 1; $i < 100; $i++){
            Article::create([
                'language_id' => Language::all()->random()->id,
                'category_id' => Category::all()->random()->id,
                'user_id' => Author::where('is_admin', Author::MEMBER)->get()->random()->id,
                'title' => $faker->title,
                'slug' => Str::slug($faker->title),
                'content' => 'Description',
                'image' => 'storage/users/default.png',
                'is_post_admin' => Article::IS_POST_AUTHOR,
                'status' => Article::STATUS_PENDING,
                'created_date' => Carbon::now(),
            ]);
        }
    }
}
