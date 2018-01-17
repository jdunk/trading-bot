<?php

namespace App\Console\Commands;

use App\Blls\ExchangeBll;
use Illuminate\Console\Command;

class GetBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display current balances';

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
        $balances = $this->exchangeBll->getBalances();

        array_walk($balances, function(& $data, $symbol) { $data = [$symbol, $data['available'], $data['onOrder']]; });

        $this->info('');
        $this->table(['Wallet', 'Balance available', 'Locked amount'], $balances);
        $this->info('');
    }
}
