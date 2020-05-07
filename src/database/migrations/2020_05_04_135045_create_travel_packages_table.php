<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('user_id');			
			$table->integer('travel_package_type_id');
			
			$table->string('name', 255);
			$table->string('banner_image_name', 255)->nullable();
			
			$table->longText('description')->nullable();			
			$table->longText('notes')->nullable();
			
			$table->dateTime('expire_date', 0)->nullable();
			
			$table->boolean('is_featured')->default(0);
			
			
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
        Schema::dropIfExists('travel_packages');
    }
}
