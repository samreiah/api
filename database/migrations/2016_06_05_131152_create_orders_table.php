<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->longText('invoice_prefix');
			$table->bigInteger('customer_id');
			$table->bigInteger('shipping_method_id');
			$table->bigInteger('payment_method_id');
			$table->bigInteger('order_status_id');
			$table->longText('ip');
			$table->longText('forwarded_ip');
			$table->longText('user_agent');
			$table->dateTime('order_created');
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
