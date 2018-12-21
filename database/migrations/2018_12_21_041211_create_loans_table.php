<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->decimal('amount', 8, 2);
            $table->decimal('total', 8, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->unsignedTinyInteger('duration'); // months
            $table->unsignedTinyInteger('repayment_frequency')->default(1); // monthly
            $table->decimal('arrangement_fee', 8, 2)->nullable();
            $table->string('currency', 5)->default('USD');
            $table->timestamps();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
