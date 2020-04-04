<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
			
			$table->integer('user_id');
			$table->integer('product_category_id');
			
			$table->string('name', 250);
			$table->string('link_name', 250);
			$table->string('brand', 250)->nullable();
			
			
			$table->string('pdf_name', 250)->nullable();
			$table->string('video_name', 250)->nullable();
			
			$table->longText('tags');
			$table->longText('detail');
			
			$table->boolean('featured')->default(false);
			$table->boolean('colors')->default(false);
			$table->boolean('active')->default(false);
			
			$table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
