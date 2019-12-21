<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->integer('page_id');
			$table->integer('page_content_type_id');
			$table->longText('detail')->nullable();
			$table->string('image_name', 100)->nullable();			
			$table->string('video_name', 100)->nullable();			
			$table->integer('height')->nullable();
			$table->integer('width')->nullable();		
			$table->integer('column_order')->default(0);
			
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
        Schema::dropIfExists('page_contents');
    }
}
