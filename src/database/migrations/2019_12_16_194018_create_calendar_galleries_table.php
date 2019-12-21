<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('calendar_id');
			$table->string('image_name', 100);				
			$table->string('title', 200)->nullable();	
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
        Schema::dropIfExists('calendar_galleries');
    }
}
