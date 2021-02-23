<?php

namespace Database\Seeders;

use App\Models\Author;
use Faker\Factory;
use Illuminate\Database\Seeder;

class AuthorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 1; $i < 30; $i++)
        {
            Author::create([
                'name' => $faker->name,
                'address' => $faker->address,
                'password' => bcrypt('123456'),
                'avatar' => 'storage/users/default.png',
                'is_admin' => Author::MEMBER,
                'birthday' => $faker->date(),
                'token' => $faker->numberBetween(10, 500),
                'introduction' => $faker->sentence(),
                'phone' => $faker->phoneNumber,
                'email' => $faker->unique()->freeEmail
            ]);
        }
    }
}
