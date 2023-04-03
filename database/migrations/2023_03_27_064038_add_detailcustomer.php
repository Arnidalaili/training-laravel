<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailcustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice');
            $table->string('namabarang');
            $table->string('qty');
            $table->double('harga');
            $table->timestamps(); 

            $table->foreign('invoice', 'customers_detail_customers_invoice_foreign')->references('invoice')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
