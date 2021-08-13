<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid', 36)->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamp('device_timestamp');
            $table->integer('total_amount');
            $table->integer('paid_amount');
            $table->integer('change_amount');
            $table->enum('payment_method', ['cash', 'card']);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
