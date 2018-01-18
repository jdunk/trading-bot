<?php

namespace App\Console\Commands;

use App\Blls\CandlesticksBll;
use Exception;
use Illuminate\Console\Command;

class DataUpdateCandlesticks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:update-candlesticks
                            {symbol : e.g. "ETHUSDT" (Or "ETHUSDT,BTCUSDT" or "ALL")}
                            {interval=1m : e.g. 1m|30m|1h}
                            {--from= : unix timestamp or whatever}
                            {--to= : unix timestamp or whatever}
                            {--chunk= : which chunk # to process}
                            {--now : Process now instead of enqueueing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store candlestick data';

    protected $candlesticksBll;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CandlesticksBll $candlesticksBll)
    {
        parent::__construct();

        $this->candlesticksBll = $candlesticksBll;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $symbol = $this->argument('symbol');
        $interval = $this->argument('interval');
        $doNow = $this->option('now');

        $symbol = explode(',', $symbol);

        if (! $doNow)
        {
            $this->info("UpdateCandlesticks job enqueued");
        }

        $numSaved = $this->candlesticksBll->fetchAndStoreCandlesticks(
            $symbol,
            $interval,
            $this->option('from'),
            $this->option('to'),
            $this->option('chunk')
        );

        $this->info("$numSaved candlesticks fetched & saved");
    }
}
