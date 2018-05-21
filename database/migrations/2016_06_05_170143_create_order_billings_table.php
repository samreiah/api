<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_billings', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('order_id');
			$table->bigInteger('customer_id');
			$table->bigInteger('payment_address_id');
			$table->bigInteger('shipping_address_id');
			$table->string('name');
			$table->bigInteger('mobile');
			$table->decimal('tax', 9,2);
			$table->decimal('subtotal', 9,2);
			$table->decimal('wallet_discounts', 9,2);
			$table->decimal('shipping_charge', 9,2);
			$table->decimal('total', 9,2);
			$table->bigInteger('is_paid');
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
