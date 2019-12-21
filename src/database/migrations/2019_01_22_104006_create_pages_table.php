<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('parent_id')->default(0);
			$table->integer('user_id');
			$table->integer('page_type_id')->nullable();
			$table->integer('price')->default(0);
			$table->string('name', 100);
			$table->string('website_link', 100)->nullable();
			$table->string('link_name', 100)->nullable();
			$table->string('image_name', 100)->nullable();
			$table->string('title', 100)->nullable();
			$table->string('meta_description', 1000)->nullable();
			
			$table->longText('detail')->nullable();
			$table->longText('detail_summary')->nullable();
			
			$table->boolean('no_link')->default(false);
			$table->boolean('active')->default(false);
			$table->boolean('hidden')->default(false);
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
        Schema::dropIfExists('pages');
    }
}
