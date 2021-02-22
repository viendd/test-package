<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('languages')->truncate();
        Schema::enableForeignKeyConstraints();
        $data = [
            ['short_name' => 'EN', 'name' => 'English'],
            ['short_name' => 'VN', 'name' => 'Tiếng Việt'],
        ];
        DB::table('languages')->insert($data);
    }
}
