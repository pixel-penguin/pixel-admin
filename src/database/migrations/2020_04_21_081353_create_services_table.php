<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('user_id');
			$table->string('image_name', 250)->nullable();	
			$table->string('name', 250)->nullable();		
			$table->longText('detail')->nullable();
			$table->boolean('active')->default(false);			
			$table->softDeletes('deleted_at', 0);
			
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
        Schema::dropIfExists('services');
    }
}
