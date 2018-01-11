<?php

namespace App\Console\Commands;

use App\Blls\CandlesticksBll;
use Carbon\Carbon;
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
                            {symbol : e.g. ETHUSDT}
                            {interval=1m : e.g. 1m|30m|1h}
                            {--from= : unix timestamp or whatever}
                            {--thru= : unix timestamp or whatever}';

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

        $from = $this->parseDate('from');
        $thru = $this->parseDate('thru');

        dd(['from' => $from, 'thru' => $thru]);

        $this->info('fetching api data...');
        $data = $this->candlesticksBll->fetchApiData($symbol, $interval, $from, $thru);
        $this->info('storing...');
        $numSaved = $this->candlesticksBll->storeCandlesticks($symbol, $interval, $data);

        $data = (array)$data;
        var_dump(array_slice($data, 0, 2));
        $this->line('...');
        var_dump(array_slice($data, -2, 2));

        $this->info("$numSaved candlesticks fetched & saved");
    }

    public function parseDate($optionName)
    {
        $value = $this->option($optionName);
        $valueLen = strlen($value);

        // For some reason, Carbon can't parse unix timestamps, so do it manually
        // Also, account/allow for the timestamp being in microseconds (i.e. x1000)
        // as the BN API gives it.

        if (preg_match('@^\d+$@', $value) && in_array($valueLen, [10, 13])) {
            if ($valueLen == 13) {
                $value = substr($value, 0, -3);
            }

            $this->mustBeWithinThePastYear($value, $optionName);
        }
        else {
            try {
                $value = Carbon::parse($value, 'UTC')->timestamp;
                $this->mustBeWithinThePastYear($value, $optionName);
            }
            catch (Exception $e) {
                $this->error('Invalid --' . $optionName . ' date/time specified');
                exit;
            }
        }

        return $value . '000';
    }

    public function mustBeWithinThePastYear($value, $optionName)
    {
        if ($value < Carbon::parse('1 year ago')->timestamp ||
            $value > Carbon::now()->timestamp)
        {
            $this->error('Invalid --' . $optionName . ' date/time specified');
            exit;
        }
    }
}
