<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTickerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_ticker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->dateTime('datetime_');
            $table->float('bid_price', 15, 8)->unsigned(); // max: < 10M
            $table->float('ask_price', 15, 8)->unsigned();

            // Calculated fields

            $table->float('gap_perc', 6, 4)->nullable();
            $table->float('gap_perc_avg_4', 6, 4)->nullable();
            $table->float('gap_perc_avg_9', 6, 4)->nullable();
            $table->float('gap_perc_ewa_4', 6, 4)->nullable();
            $table->float('gap_perc_ewa_9', 6, 4)->nullable();

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
        Schema::dropIfExists('book_ticker');
    }
}
