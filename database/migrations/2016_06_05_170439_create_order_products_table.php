<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('order_id');
			$table->bigInteger('product_id');
			$table->bigInteger('product_option_id');
			$table->bigInteger('quantity');
			$table->bigInteger('option_value_id');
            $table->decimal('purchased_for', 9, 2);
            $table->bigInteger('affiliate_id');
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
