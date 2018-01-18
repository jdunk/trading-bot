<?php

namespace App\Jobs;

use App\Blls\CandlesticksBll;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateCandlesticksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $candlesticksBll;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CandlesticksBll $candlesticksBll)
    {
        $this->candlesticksBll = $candlesticksBll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $numSaved = $this->candlesticksBll->fetchAndStoreCandlesticks(
            $symbol,
            $interval,
            $this->option('from'),
            $this->option('to'),
            $this->option('chunk')
        );

        logger(self::class . " finished. $numSaved candlesticks_$interval saved");
    }
}
