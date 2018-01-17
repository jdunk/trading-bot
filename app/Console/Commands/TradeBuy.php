<?php

namespace App\Console\Commands;

use App\Blls\ExchangeBll;
use Illuminate\Console\Command;

class TradeBuy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trade:buy
                            {symbol : e.g. "ETHUSDT"}
                            {quantity? : e.g. "3.14"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Place a buy market order';

    protected $exchangeBll;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ExchangeBll $exchangeBll)
    {
        parent::__construct();

        $this->exchangeBll = $exchangeBll;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ret = $this->exchangeBll->marketBuy(
            $this->argument('symbol'),
            $this->argument('quantity')
        );

        dd(compact('ret'));
    }
}
