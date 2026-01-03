<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'postal_code' => '155-0031',
                'address' => '東京都世田谷区北沢2-12-8',
                'building' => 'ハイツ下北沢 203',
                'profile_image' => 'profile_images/default.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'postal_code' => '564-0051',
                'address' => '大阪府吹田市豊津町10-15',
                'building' => 'グリーンハイツ江坂 402',
                'profile_image' => 'profile_images/default.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
