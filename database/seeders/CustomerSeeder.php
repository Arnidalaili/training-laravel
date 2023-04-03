<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        for ($i = 1; $i <= 100; $i++) {
            DB::table('customers')->insert([
                'invoice' => $this->generateInvoiceNumber($i),
                'nama' => $faker->name(),
                'tanggal' => Carbon::create('2000', '01', '01')->addDays($i),
                'jeniskelamin' =>$faker->randomElement(['LAKI-LAKI', 'PEREMPUAN']),
                'saldo' => rand(0, 1000000),
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }

    private function generateInvoiceNumber($number)
    {
        $prefix = 'INV';
        $padding = 3; // Set the number of digits for the invoice number
        
        // Generate the invoice number with the desired format
        $invoiceNumber = $prefix . str_pad($number, $padding, '0', STR_PAD_LEFT);
        
        return $invoiceNumber;
    }
}
