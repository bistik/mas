<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('loan_id');
            $table->decimal('payment', 10, 2);
            $table->decimal('interest', 10, 2);
            $table->decimal('principal', 10, 2);
            $table->decimal('start_balance', 10, 2);
            $table->decimal('end_balance', 10, 2);
            $table->timestamps();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');

            $table->foreign('loan_id')
                    ->references('id')
                    ->on('loans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repayments');
    }
}
