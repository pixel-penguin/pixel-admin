<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToPageGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_galleries', function (Blueprint $table) {
            $table->string('name', 255)->nullable();
			$table->text('description')->nullable();
			$table->string('button_text', 255)->nullable();
			$table->string('button_link', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_galleries', function (Blueprint $table) {
            //
        });
    }
}
