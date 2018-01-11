<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol')->unique();
            $table->string('base_asset');
            $table->string('quote_asset');
            $table->float('min_price', 10, 8);
            $table->float('tick_size', 10, 8);
            $table->float('min_qty', 10, 8);
            $table->float('step_size', 10, 8);
            $table->float('min_notional', 10, 8);
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
        Schema::dropIfExists('exchange_info');
    }
}
