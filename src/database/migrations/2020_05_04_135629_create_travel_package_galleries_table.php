<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPackageGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_package_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('user_id');			
			$table->integer('travel_package_id');
			$table->integer('column_order')->default(999);
			
			
			$table->string('name', 255)->nullable();
			$table->string('image_name', 255);
			
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
        Schema::dropIfExists('travel_package_galleries');
    }
}
