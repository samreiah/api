<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('user_id');
			$table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->bigInteger('mobile')->default(0);
            $table->bigInteger('telephone')->default(0);
            $table->string('image', 255)->nullable();
            $table->text('cart')->nullable();
            $table->text('wish_list')->nullable();
            $table->string('ip', 40)->nullable();
            $table->string('customer_object', 255)->nullable();
            $table->decimal('total_credits', 9, 2)->default(0.00);
            $table->tinyInteger('newsletter')->default(0);
            $table->tinyInteger('active')->default(0);
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
        Schema::drop('customers');
    }
}
