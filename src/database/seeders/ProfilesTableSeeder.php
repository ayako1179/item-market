<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            [
                'user_id' => 1,
                'postal_code' => '160-0025',
                'address' => '東京都新宿区新宿4-5-6',
                'building' => 'サンライズマンション206',
                'profile_image' => 'profile_images/default.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市北区梅田1-1-1',
                'building' => '梅田ビル10F',
                'profile_image' => 'profile_images/default.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'postal_code' => '555-0000',
                'address' => '福岡県福岡市中央区天神7-8-9',
                'building' => '天神タワー206',
                'profile_image' => 'profile_images/default.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
