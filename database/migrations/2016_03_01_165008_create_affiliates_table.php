<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('user_id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->bigInteger('mobile')->default(0);
            $table->bigInteger('telephone')->default(0);
            $table->string('company_name', 100)->nullable();
			$table->string('image', 255)->nullable();
            $table->text('website')->nullable();
            $table->text('addr_line1')->nullable();
            $table->text('addr_line2')->nullable();
            $table->string('city')->nullable();
            $table->integer('postcode')->default(0);
            $table->bigInteger('country_id')->nullable();
            $table->decimal('comission', 9, 2)->default(0.00);
            $table->decimal('tax', 9, 2)->default(0.00);
            $table->string('paypal')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch_name')->nullable();
            $table->string('bank_branch_number')->nullable();
            $table->string('bank_branch_swift_code')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->decimal('total_balance', 9, 2)->default(0.00);
            $table->string('ip', 40)->nullable();
            $table->tinyInteger('affiliate_state')->default(0);
            $table->tinyInteger('approved')->default(0);
            $table->tinyInteger('contact_visible')->default(0);
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
        Schema::drop('affiliates');
    }
}
