<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleShippingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_shipping_details', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('sale_id')->default(0);
			
			$table->string('contact_person', 250);
			$table->string('physical_address', 250);
			$table->string('postal_address', 250);
            $table->integer('country_id')->default(0);
            $table->integer('city_id')->default(0);
			
			$table->string('postal_code', 100);
			$table->string('contact_number', 100);
			
			$table->softDeletes('deleted_at', 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_shipping_details');
    }
}
