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

    protected $symbols;
    protected $interval;
    protected $startTime;
    protected $endTime;
    protected $chunk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($symbols, $interval, $startTime, $endTime)
    {
        $this->symbols = $symbols;
        $this->interval = $interval;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CandlesticksBll $candlesticksBll)
    {
        $numSaved = $candlesticksBll->fetchAndStoreCandlesticksFinish(
            $this->symbols,
            $this->interval,
            $this->startTime,
            $this->endTime
        );

        logger(self::class . " finished. $numSaved candlesticks_" . $this->interval . " saved for " . $this->symbols->implode(','));
    }
}
