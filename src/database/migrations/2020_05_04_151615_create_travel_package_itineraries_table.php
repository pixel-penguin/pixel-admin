<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPackageItinerariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_package_itineraries', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('user_id');			
			$table->integer('travel_package_id');
			$table->integer('column_order')->default(999);
			
			$table->string('name', 255)->nullable();
			$table->string('day', 255)->nullable();
			$table->string('location', 255)->nullable();
						
			$table->string('latitude', 255)->nullable();
			$table->string('longitude', 255)->nullable();
			
			$table->boolean('has_map')->default(0);
			
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
        Schema::dropIfExists('travel_package_itineraries');
    }
}
