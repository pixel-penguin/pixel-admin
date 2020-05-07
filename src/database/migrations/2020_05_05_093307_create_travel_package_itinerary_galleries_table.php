<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPackageItineraryGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_package_itinerary_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('travel_package_itinerary_id')->default(999);			
			$table->integer('column_order')->default(999);			
			
			$table->longText('description')->nullable();	
			
			$table->string('name', 255)->nullable();
			$table->string('image_name', 255);
			
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
        Schema::dropIfExists('travel_package_itinerary_galleries');
    }
}
