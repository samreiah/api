<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
             'user_roles',
             function (Blueprint $table) {
				$table->bigIncrements('id');
                $table->integer('user_id');
                $table->integer('role_id');
				$table->timestamp('created_at');
				$table->timestamp('updated_at');
				$table->bigInteger('updated_by');
             }
         );
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
