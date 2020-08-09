<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name', 255);
			$table->integer('country_id');			
			$table->string('flips_code', 100);	
			$table->string('iso2', 100);	
            $table->timestamps();
			$table->string('flag', 100);			
			$table->string('wikiDataId', 100);	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
