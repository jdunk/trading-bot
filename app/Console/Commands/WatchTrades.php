<?php

namespace App\Console\Commands;

use App\Blls\ExchangeInfoBll;
use Illuminate\Console\Command;

class WatchTrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch-trades
                            {symbol : e.g. ETHUSDT}
                            {alert-price? : e.g. 14.52}
                            {--buy= : Quantity to buy (market) when alert-price is reached}
                            {--sell= : Quantity to sell (market) when alert-price is reached}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch price updates. Optionally alert at a certain price.';

    protected $exchangeInfoBll;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ExchangeInfoBll $exchangeInfoBll)
    {
        parent::__construct();

        $this->exchangeInfoBll = $exchangeInfoBll;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $symbol = $this->argument('symbol');
        $alertPrice = $this->argument('alert-price');

        $buyQty = $this->option('buy');
        $sellQty = $this->option('sell');

        if ($buyQty && $sellQty)
        {
            $this->error("You can't both buy and sell. Choose one or the other.");
            return false;
        }

        if (($buyQty || $sellQty) && ! $alertPrice)
        {
            $this->error("alert-price is required when --buy or --sell are specified");
            return false;
        }

        $this->exchangeInfoBll->watchTrades($symbol, $alertPrice, $buyQty, $sellQty);
    }
}
