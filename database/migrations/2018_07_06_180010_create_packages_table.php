<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->double('price',30,8);
            $table->float('profit_percent',8,2); //percentage
            $table->double('min_deposit',30,8);
            $table->double('max_deposit',30,8)->nullable();
            $table->float('termination_fee',8,2); //percentage
            $table->tinyInteger('multiple_account');
            $table->tinyInteger('access_all_feature');
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
        Schema::dropIfExists('packages');
    }
}
