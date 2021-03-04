<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTransactionTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_transaction_token', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_receive_id');
            $table->foreign('user_receive_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_send_id');
            $table->foreign('user_send_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('type');
            $table->integer('token');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_transaction_token');
    }
}
