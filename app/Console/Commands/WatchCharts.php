<?php

namespace App\Console\Commands;

use App\Blls\ExchangeInfoBll;
use Illuminate\Console\Command;

class WatchCharts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch-charts
                            {symbol : e.g. ETHUSDT}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch price updates';

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
        $this->exchangeInfoBll->watchCharts($symbol);
        $this->info('Done.');
    }
}
