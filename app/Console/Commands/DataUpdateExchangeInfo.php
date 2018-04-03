<?php

namespace App\Console\Commands;

use App\Blls\ExchangeInfoBll;
use Illuminate\Console\Command;

class DataUpdateExchangeInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:update-exchange-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store exchange info';

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
        $data = $this->exchangeInfoBll->fetchRawExchangeInfo();
        $numSaved = $this->exchangeInfoBll->storeExchangeInfo($data);

        dump(compact('numSaved'));
    }
}
