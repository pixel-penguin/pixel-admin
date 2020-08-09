<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
           
			$table->string('name', 255);
			$table->string('iso3', 100);
			$table->string('iso2', 100);
			$table->string('phonecode', 100);
			$table->string('capital', 100);
			$table->string('currency', 100);
			$table->string('native', 100);
			$table->string('emoji', 100);
			$table->string('emojiU', 100);	
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
        Schema::dropIfExists('countries');
    }
}
