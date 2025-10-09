<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'user_id' => '1',
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'price' => '15000',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'products/Watch.jpeg',
                'condition_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '2',
                'name' => 'HDD',
                'brand_name' => '西芝',
                'price' => '5000',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'products/HDD.jpeg',
                'condition_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '3',
                'name' => '玉ねぎ3束',
                'brand_name' => 'なし',
                'price' => '300',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'products/Onion.jpeg',
                'condition_id' => '3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '1',
                'name' => '革靴',
                'brand_name' => null,
                'price' => '4000',
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'products/Shoes.jpeg',
                'condition_id' => '4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '2',
                'name' => 'ノートPC',
                'brand_name' => null,
                'price' => '45000',
                'description' => '高性能なノートパソコン',
                'image_path' => 'products/PC.jpeg',
                'condition_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '2',
                'name' => 'マイク',
                'brand_name' => 'なし',
                'price' => '8000',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'products/Mic.jpeg',
                'condition_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '4',
                'name' => 'ショルダーバッグ',
                'brand_name' => null,
                'price' => '3500',
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'products/Bag.jpeg',
                'condition_id' => '3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '3',
                'name' => 'タンブラー',
                'brand_name' => 'なし',
                'price' => '500',
                'description' => '使いやすいタンブラー',
                'image_path' => 'products/Tumbler.jpeg',
                'condition_id' => '4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '3',
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'price' => '4000',
                'description' => '手動のコーヒーミル',
                'image_path' => 'products/Mill.jpeg',
                'condition_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => '4',
                'name' => 'メイクセット',
                'brand_name' => null,
                'price' => '2500',
                'description' => '便利なメイクアップセット',
                'image_path' => 'products/Makeup.jpeg',
                'condition_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
