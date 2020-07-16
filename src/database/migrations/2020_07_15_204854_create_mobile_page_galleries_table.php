<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilePageGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_page_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mobile_page_id')->default(0);
            $table->integer('user_id');
            $table->string('image_name',255);
            $table->boolean('active')->default(1);
            $table->integer('column_order')->default(999);
            $table->string('name',255);
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('mobile_page_galleries');
    }
}
