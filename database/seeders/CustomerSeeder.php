<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Provider\Base;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $now = date('Y-m-d H:i:s');

        for ($i = 1; $i <= 200; $i++) {
            DB::table('customers')->insert([
                'invoice' => $faker->unique()->numberBetween(1,500),
                'nama' => $faker->name(),
                'tanggal' => Carbon::create('2000', '01', '01')->addDays($i),
                'jeniskelamin' =>$faker->randomElement(['LAKI-LAKI', 'PEREMPUAN']),
                'saldo' => rand(0, 1000000),
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
