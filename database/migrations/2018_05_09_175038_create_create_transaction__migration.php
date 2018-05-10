<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateTransactionMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('create_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bankcode', 4);
            $table->string('bankInterface', 1);
            $table->string('returnURL', 255);
            $table->string('reference', 32);
            $table->string('description', 255);
            $table->string('language', 2);
            $table->string('currency', 3);
            $table->double('totalAmount');
            $table->double('taxAmount');
            $table->double('devolutionBase');
            $table->double('tipAmount');
            $table->string('payer', 2000);
            $table->string('buyer', 2000);
            $table->string('shipping', 2000);
            $table->string('ipAddress', 15);
            $table->string('userAgent', 255);
            $table->string('additionalData', 2000);
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
        Schema::dropIfExists('create_transactions');
    }
}
