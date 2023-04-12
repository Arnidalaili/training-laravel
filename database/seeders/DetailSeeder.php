<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DetailSeeder extends Seeder
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

        $customerInv = Customer::pluck('invoice');
        foreach ($customerInv as $inv) {
            for ($i = 1; $i <= 2; $i++) 
            {
                DB::table('detail_customers')->insert([
                    'invoice' => $inv,
                    'namabarang' =>$faker->word(),
                    'qty' => $faker->numberBetween(1, 50),
                    'harga' => rand(0, 10000000),
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }
    }
}
