<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPackageTravelDatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_package_travel_date_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('user_id');
			$table->integer('travel_package_travel_date_id');			
			
			$table->string('name', 255);
			$table->string('type', 255);
			
			$table->integer('price');
			
			
            $table->timestamps();
			$table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_package_travel_date_prices');
    }
}
