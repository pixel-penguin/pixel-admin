<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPackageTravelDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_package_travel_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('user_id');
			$table->integer('travel_package_id');
			
			$table->dateTime('start_date', 0);
			$table->dateTime('end_date', 0);
            
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
        Schema::dropIfExists('travel_package_travel_dates');
    }
}
