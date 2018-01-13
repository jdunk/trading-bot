<?php

namespace App\Console\Commands;

use App\Blls\ExchangeBll;
use Illuminate\Console\Command;

class DataUpdatePriceCalcs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:update-prices-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the price_ticker table "metadata" (calculated fields)';

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
        $numStored = $this->exchangeBll->updatePriceMetaData();
        $this->info("$numStored records saved.");
    }
}
