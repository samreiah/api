<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_id')->nullable();
            $table->string('ip', 40)->nullable();
            $table->text('data')->nullable();
            $table->text('url')->nullable();
            $table->string('activity')->nullable();
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
        Schema::drop('affiliate_activities');
    }
}
