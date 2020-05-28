<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_details', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->string('name', 255)->nullable();
			$table->string('slogan', 255)->nullable();
			$table->string('logo_1_image_name', 255)->nullable();
			$table->string('logo_2_image_name', 255)->nullable();
			
			$table->string('color_1', 100)->nullable();
			$table->string('color_1_inverted', 100)->nullable();
			$table->string('color_2', 100)->nullable();
			$table->string('color_2_inverted', 100)->nullable();
			$table->string('color_3', 100)->nullable();
			$table->string('color_3_inverted', 100)->nullable();
			$table->string('color_4', 100)->nullable();
			$table->string('color_4_inverted', 100)->nullable();
			
			$table->string('physical_address', 255)->nullable();
			$table->string('contact_number', 100)->nullable();
			$table->string('email', 255)->nullable();
			$table->string('working_hours', 255)->nullable();
			
			$table->string('facebook_url', 255)->nullable();
			$table->string('instagram_url', 255)->nullable();
			$table->string('linkedin_url', 255)->nullable();
			
			
			
			
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
        Schema::dropIfExists('website_details');
    }
}
