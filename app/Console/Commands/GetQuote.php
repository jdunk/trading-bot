<?php

namespace App\Console\Commands;

use App\Blls\ExchangeInfoBll;
use Illuminate\Console\Command;

class GetQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-quote
                            {symbol? : e.g. ETHUSDT}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current price(s) data';

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
        $symbol = explode(',', $this->argument('symbol'));

        if (count($symbol) > 1)
        {
            $ret = $this->exchangeInfoBll->getQuote();
            $prices = $ret['priceData'];
            $prices = array_filter($prices, function($quote) use ($symbol) {
                return in_array($quote->symbol, $symbol);
            });
        }
        else
        {
            $ret = $this->exchangeInfoBll->getQuote($symbol[0]);
            $prices = [ $ret['priceData'] ];
        }

        $tableData = array_map(function($item) {
            return [ $item->symbol, $item->price ];
        }, $prices);

        $this->table(['Symbol', 'Price'],
            $tableData
        );
        $this->info('Done.');
    }
}
