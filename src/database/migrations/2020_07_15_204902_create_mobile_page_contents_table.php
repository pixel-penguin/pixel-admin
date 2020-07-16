<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilePageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_page_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('page_id');
            $table->integer('page_content_type_id');
			
			
            $table->string('image_name',255)->nullable();
            $table->string('video_name',255)->nullable();
            $table->longText('description')->nullable();
			
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->integer('column_order')->default(999);
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
        Schema::dropIfExists('mobile_page_contents');
    }
}
