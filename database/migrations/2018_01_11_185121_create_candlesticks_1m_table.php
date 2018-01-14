<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandlesticks1mTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candlesticks_1m', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->dateTime('datetime_');
            $table->float('open', 15, 8)->unsigned(); // max: 9.999999 Million
            $table->float('high', 15, 8)->unsigned();
            $table->float('low', 15, 8)->unsigned();
            $table->float('close', 15, 8)->unsigned();
            $table->float('volume', 20, 8)->unsigned(); // max: 999.999999 Billion

            // ** Calculated fields **

            // Simple averages
            $table->float('avg_ohlc', 15, 8)->nullable();
            $table->float('avg_oc', 15, 8)->nullable();
            $table->float('avg_hl', 15, 8)->nullable();

            // % change vs. N time periods ago
            $table->float('perc_change_vs_1', 7, 4)->nullable();
            $table->float('perc_change_vs_2', 7, 4)->nullable();
            $table->float('perc_change_vs_3', 7, 4)->nullable();
            $table->float('perc_change_vs_4', 7, 4)->nullable();

            // Moving Averages (irrelevant of volume) -- all are OHLC
            $table->float('ma4', 15, 8)->unsigned()->nullable();
            $table->float('ma9', 15, 8)->unsigned()->nullable();
            $table->float('ma20', 15, 8)->unsigned()->nullable();
            $table->float('ema4', 15, 8)->unsigned()->nullable();
            $table->float('ema5', 15, 8)->unsigned()->nullable();
            $table->float('ema9', 15, 8)->unsigned()->nullable();

            // Weighted Moving Averages (by volume)
            $table->float('wma4', 15, 8)->unsigned()->nullable();
            $table->float('wma9', 15, 8)->unsigned()->nullable();
            $table->float('wma20', 15, 8)->unsigned()->nullable();
            $table->float('wema4', 15, 8)->unsigned()->nullable();
            $table->float('wema5', 15, 8)->unsigned()->nullable();
            $table->float('wema9', 15, 8)->unsigned()->nullable();

            // Moving Average Percent Change
            $table->float('mapc3', 7, 4)->nullable();
            $table->float('mapc4', 7, 4)->nullable();
            $table->float('mapc5', 7, 4)->nullable();
            $table->float('mapc6', 7, 4)->nullable();

            $table->float('emapc3', 7, 4)->nullable();
            $table->float('emapc4', 7, 4)->nullable();
            $table->float('emapc5', 7, 4)->nullable();
            $table->float('emapc6', 7, 4)->nullable();

            $table->timestamps();

            $table->unique(['symbol', 'datetime_']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candlesticks_1m');
    }
}
