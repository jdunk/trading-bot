<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceTickerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_ticker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->dateTime('datetime_');
            $table->float('price', 15, 8)->unsigned(); // max: < 10M

            // Calculated fields

            // % change vs. N time periods ago
            $table->float('perc_change_vs_1', 7, 4)->nullable();
            $table->float('perc_change_vs_2', 7, 4)->nullable();
            $table->float('perc_change_vs_3', 7, 4)->nullable();
            $table->float('perc_change_vs_4', 7, 4)->nullable();

            // Moving Averages
            $table->float('ma4', 15, 8)->unsigned()->nullable();
            $table->float('ma9', 15, 8)->unsigned()->nullable();
            $table->float('ma20', 15, 8)->unsigned()->nullable();
            $table->float('ema4', 15, 8)->unsigned()->nullable();
            $table->float('ema5', 15, 8)->unsigned()->nullable();
            $table->float('ema9', 15, 8)->unsigned()->nullable();

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
        Schema::dropIfExists('price_ticker');
    }
}
