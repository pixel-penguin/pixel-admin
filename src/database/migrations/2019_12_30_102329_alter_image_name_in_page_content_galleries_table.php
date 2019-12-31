<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//use DB;

class AlterImageNameInPageContentGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
		
    public function up()
    {
		DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
		
        Schema::table('page_content_galleries', function (Blueprint $table) {
			$table->string('image_name', 250)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_content_galleries', function (Blueprint $table) {
            //
        });
    }
}
