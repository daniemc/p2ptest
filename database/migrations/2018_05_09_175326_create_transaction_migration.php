<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transactionID_local');
            $table->integer('transactionID')->unique();
            $table->string('sessionID', 32);
            $table->string('returnCode', 30);
            $table->string('trazabilityCode', 40);
            $table->integer('transactionCycle');
            $table->string('bankCurrency', 3);
            $table->float('bankFactor');
            $table->string('bankURL', 255);
            $table->integer('responseCode');
            $table->string('responseReasonCode', 3);
            $table->string('responseReasonText', 255);
            $table->string('callback_validation', 1)->default('0');
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
        Schema::dropIfExists('transactions');
    }
}
