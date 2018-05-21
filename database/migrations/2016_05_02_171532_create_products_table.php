<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
			$table->string('name');
            $table->longText('description')->nullable();
			$table->longText('tag')->nullable();
            $table->string('meta_title')->nullable(0);
            $table->string('meta_description')->nullable(0);
            $table->string('meta_keyword')->nullable();
			$table->string('model')->nullable();
			$table->string('sku')->nullable();
			$table->string('upc')->nullable();
			$table->string('ean')->nullable();
			$table->string('jan')->nullable();
			$table->string('isbn')->nullable();
			$table->string('spn')->nullable();
			$table->string('location')->nullable();
			$table->bigInteger('quantity')->default(1);
			$table->bigInteger('rating')->default(0);
			$table->string('stock_status')->nullable();
			$table->bigInteger('manufacturer_id');
			$table->bigInteger('affiliate_id');
			$table->decimal('price', 9, 2);
			$table->decimal('tax', 9, 2);
			$table->date('date_available')->nullable();
			$table->tinyInteger('approved')->default(0);
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
