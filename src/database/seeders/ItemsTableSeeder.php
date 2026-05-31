<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
        
            'user_id' => 1,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'image_path' =>'storage/market_image/Armani+Mens+Clock.jpg' ,
            'condition' => '良好',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => 'HDD',
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'image_path' =>'storage/market_image/HDD+Hard+Disk.jpg' ,
            'condition' => '目立った傷や汚れなし',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => 300,
            'image_path' =>'storage/market_image/iLoveIMG+d.jpg' ,
            'condition' => 'やや傷や汚れあり',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => '革靴',
            'description' => 'クラシックなデザインの革靴',
            'price' => 4000,
            'image_path' =>'storage/market_image/Leather+Shoes+Product+Photo.jpg' ,
            'condition' => '状態が悪い',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => 'ノートPC',
            'description' => '高性能なノートパソコン',
            'price' => 45000,
            'image_path' =>'storage/market_image/Living+Room+Laptop.jpg' ,
            'condition' => '良好',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => 'マイク',
            'description' => '高音質のレコーディング用マイク',
            'price' => 8000,
            'image_path' =>'storage/market_image/Music+Mic+4632231.jpg' ,
            'condition' => '目立った傷や汚れなし',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => 3500,
            'image_path' =>'storage/market_image/Purse+fashion+pocket.jpg' ,
            'condition' => 'やや傷や汚れあり',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => 'タンブラー',
            'description' => '使いやすいタンブラー',
            'price' => 500,
            'image_path' =>'storage/market_image/Tumbler+souvenir.jpg' ,
            'condition' => '状態が悪い',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'brand_name' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => 4000,
            'image_path' =>'storage/market_image/Waitress+with+Coffee+Grinder.jpg',
            'condition' => '良好',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        $param =[
            'user_id' => 1,
            'name' => 'メイクセット',
            'description' => '便利なメイクアップセット',
            'price' => 2500,
            'image_path' =>'storage/market_image/外出メイクアップセット.jpg' ,
            'condition' => '目立った傷や汚れなし',
            'is_sold' => false
        ];

        DB::table('items')->insert($param);

        
    }
}
