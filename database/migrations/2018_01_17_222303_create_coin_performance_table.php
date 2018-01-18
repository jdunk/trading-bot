<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinPerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_performance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol')->unique();
            $table->float('perc_change_5m', 7, 4)->nullable();
            $table->float('perc_change_15m', 7, 4)->nullable();
            $table->float('perc_change_30m', 8, 4)->nullable();
            $table->float('perc_change_2h', 8, 4)->nullable();
            $table->float('perc_change_6h', 8, 4)->nullable();
            $table->float('perc_change_12h', 8, 4)->nullable();
            $table->float('volume_5m', 12, 4)->unsigned()->nullable(); // in USD
            $table->float('volume_15m', 12, 4)->unsigned()->nullable();
            $table->float('volume_30m', 12, 4)->unsigned()->nullable();
            $table->float('volume_2h', 12, 4)->unsigned()->nullable();
            $table->float('volume_6h', 12, 4)->unsigned()->nullable();
            $table->float('volume_12h', 12, 4)->unsigned()->nullable();
            $table->float('hot_score1', 9, 2)->nullable();
            $table->float('hot_score2', 9, 2)->nullable();
            $table->float('hot_score3', 9, 2)->nullable();
            $table->float('hot_score4', 9, 2)->nullable();

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
        Schema::dropIfExists('coin_performance');
    }
}
