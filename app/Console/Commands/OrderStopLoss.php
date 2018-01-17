<?php

namespace App\Console\Commands;

use App\Blls\ExchangeBll;
use Illuminate\Console\Command;

class OrderStopLoss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trade:stop-loss
                            {symbol : e.g. "ETHUSDT"}
                            {quantity : e.g. "3.14"}
                            {price : The sales price}
                            {stop-price : The price at which the limit order will be created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Place a stop loss (sell limit) order';

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
        $ret = $this->exchangeBll->stopLoss(
            $this->argument('symbol'),
            $this->argument('quantity'),
            $this->argument('price'),
            $this->argument('stop-price')
        );

        dd(['stopLoss ret' => $ret]);
    }
}
