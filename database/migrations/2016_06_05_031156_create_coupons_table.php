<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->longText('coupon_code');
			$table->decimal('points', 9, 2);
			$table->date('valid_from');
			$table->date('valid_till');
            $table->bigInteger('user_group')->default(0);
            $table->bigInteger('user_id')->default(0);
			$table->bigInteger('valid_for')->default(0);
			$table->bigInteger('is_redeemed')->default(0);
			$table->bigInteger('redeemed_for')->default(0);
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
