<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserMarkArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_mark_article')->truncate();

        DB::table('user_mark_article')->insert([
            [
                'user_id' => Author::where('is_admin', '<>', User::IS_ADMIN)->get()->random()->id,
                'article_id' => Article::where('status', Article::APPROVE)->get()->random()->id,
                'is_trust' => Article::IS_TRUST,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
             ],

            [
                'user_id' => Author::where('is_admin', '<>', User::IS_ADMIN)->get()->random()->id,
                'article_id' => Article::where('status', Article::APPROVE)->get()->random()->id,
                'is_trust' => Article::IS_FAKE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'user_id' => Author::where('is_admin', '<>', User::IS_ADMIN)->get()->random()->id,
                'article_id' => Article::where('status', Article::APPROVE)->get()->random()->id,
                'is_trust' => Article::IS_TRUST,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
