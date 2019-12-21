<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_navigations', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->integer('parent_id');
			$table->string('title', 100)->nullable();
			$table->string('name', 100);
			$table->string('url', 100);
			$table->string('icon_name', 100);
			$table->string('detail', 100)->nullable();
			$table->string('video_url', 100)->nullable();
			$table->enum('active', ['Y', 'N'])->default('N');
			$table->enum('hidden', ['Y', 'N'])->default('N');
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
        Schema::dropIfExists('admin_navigations');
    }
}
