<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\HistoryTransactionToken;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class HistoryTransactionTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 1; $i < 100; $i++)
        {
            $authorId = Author::where('is_admin', Author::MEMBER)->get()->random()->id;
            HistoryTransactionToken::create([
               'user_receive_id' => $authorId,
                'user_send_id' => Author::where('is_admin', Author::MEMBER)->where('id', '<>', $authorId)->get()->random()->id,
                'type' => HistoryTransactionToken::TYPE_SEND,
                'note' => $faker->sentence(),
                'token' => $faker->numberBetween(5, 20),
                'created_at' => Carbon::now()->subDays($faker->numberBetween(5, 50)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
