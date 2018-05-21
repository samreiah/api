<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
			$table->string('slug')->nullable();
            $table->string('meta_title')->nullable(0);
            $table->string('meta_description')->nullable(0);
            $table->string('meta_keyword')->nullable();
            $table->bigInteger('parent_id')->default(0);
            $table->bigInteger('top')->default(0);
			$table->tinyInteger('status')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->bigInteger('updated_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
