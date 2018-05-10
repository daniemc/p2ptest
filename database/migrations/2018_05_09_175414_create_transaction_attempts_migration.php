<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionAttemptsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_attempts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transactionID');
            $table->string('sessionID', 32);
            $table->string('reference', 32);
            $table->string('requestDate');
            $table->string('bankProcessDate');
            $table->boolean('onTest');
            $table->string('returnCode', 30);
            $table->string('trazabilityCode', 40);
            $table->integer('transactionCycle');
            $table->string('transactionState', 20);
            $table->integer('responseCode');
            $table->string('responseReasonCode', 3);
            $table->string('responseReasonText', 255);
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
        Schema::dropIfExists('transaction_attempts');
    }
}
