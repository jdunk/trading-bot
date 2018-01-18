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
                            {alert-price? : e.g. 14.52}';

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

        $this->exchangeInfoBll->watchTrades($symbol, $alertPrice);
    }
}
