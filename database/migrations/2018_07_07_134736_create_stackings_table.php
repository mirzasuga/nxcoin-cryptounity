<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stackings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('amount');
            $table->string('status');
            $table->string('type');
            $table->float('termin_fee_percent',8,2);
            $table->double('termin_fee_amount', 30, 8);
            $table->dateTimeTz('stop_at');
            $table->dateTimeTz('terminate_at')->nullable();
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
        Schema::dropIfExists('stackings');
    }
}
