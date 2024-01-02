<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class mySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //users seeders
        $users=[
            [

                    'username' => 'John Doe',
                    'mobile' => '+963911191112',
                    'password' => bcrypt('password'),

            ],
            [
                'username' => 'Mr Ben',
                'mobile' => '+963911191115',
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'Lady Gaga',
                'mobile' => '+963911191199',
                'password' => bcrypt('password'),
            ]
        ];
        DB::table('users')->insert($users);


        //categories seeders
        $categories=[
            [
                'title' => 'skin',
                'img_url'=>''
            ],
            [
                'title' => 'optical',
                'img_url'=>''
            ],
            [
                'title' => 'digestive',
                'img_url'=>''
            ],
            [
                'title' => 'heart',
                'img_url'=>''
            ],
            [
                'title' => 'nero',
                'img_url'=>''
            ]
        ];
        DB::table('categories')->insert($categories);

        //medication
        $medications=[
            [

                'scientific_name'=>'gesal',
                'commercial_name'=>'newgesal',
                'manufacture'=>'altramedica',
                'price'=>1000,
                'expiry_date'=>Carbon::create('2025', '01', '01'),
                'quantity'=>80,
                'category_id'=>1,
                'img_url'=>''

            ],
            [
                'scientific_name'=>'glyseren',
                'commercial_name'=>'tearmond',
                'manufacture'=>'phamia',
                'price'=>1500,
                'expiry_date'=>Carbon::create('2025', '01', '01'),
                'quantity'=>55,
                'category_id'=>2,
                'img_url'=>''
            ],
            [
                'scientific_name'=>'motive',
                'commercial_name'=>'newmotive',
                'manufacture'=>'opari',
                'price'=>4400,
                'expiry_date'=>Carbon::create('2025', '01', '01'),
                'quantity'=>5230,
                'category_id'=>3,
                'img_url'=>''
            ],
            [

                'scientific_name'=>'aspirn',
                'commercial_name'=>'aspirn81',
                'manufacture'=>'asia',
                'price'=>1242,
                'expiry_date'=>Carbon::create('2025', '01', '01'),
                'quantity'=>4446,
                'category_id'=>4,
                'img_url'=>''

            ],
            [

                'scientific_name'=>'setamol',
                'commercial_name'=>'parasetamol',
                'manufacture'=>'tameco',
                'price'=>4300,
                'expiry_date'=>Carbon::create('2025', '01', '01'),
                'quantity'=>23,
                'category_id'=>5,
                'img_url'=>''

            ],
            [

                'scientific_name'=>'gesal.V2',
                'commercial_name'=>'newgesalNew2',
                'manufacture'=>'altramedica',
                'price'=>45555,
                'expiry_date'=>Carbon::create('2025', '01', '01'),
                'quantity'=>80,
                'category_id'=>1,
                'img_url'=>''

            ],

        ];
        DB::table('medications')->insert($medications);

        $orders=[
            [
                'payment'=>0,
                'status'=>'Preparing',
                'user_id'=>1,
                'medication_id'=>1,

            ],
            [
                'payment'=>0,
                'status'=>'Sent',
                'user_id'=>2,
                'medication_id'=>2,
            ],
            [
                'payment'=>1,
                'status'=>'Received',
                'user_id'=>3,
                'medication_id'=>3,
            ],
        ];
        DB::table('orders')->insert($orders);
    }
}
