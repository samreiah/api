<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('customer_id')->nullable();
            $table->string('firstname')->nullable();
            $table->string('addr_line1')->nullable();
            $table->string('addr_line2')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->bigInteger('telephone')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->string('postcode', 40)->nullable();
            $table->tinyInteger('default')->default(0);;
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->bigInteger('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_addresses');
    }
}
