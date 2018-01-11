<?php

namespace App\Blls;

use App\Models\Candlesticks1m;
use Binance\API as BinanceApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CandlesticksBll
{
    protected $binanceApi;

    public function __construct(
        BinanceApi $binanceApi
    )
    {
        $this->binanceApi = $binanceApi;
    }

    public function fetchApiData($symbol, $interval, $limit=null, $startTime=null, $endTime=null)
    {
        $filename = 'candlesticks-' . $symbol . '-' . $interval . '.json';

        if (! Storage::disk('local')->exists($filename))
        {
            // Fetch from API
            $data = $this->binanceApi->candlesticks($symbol, $interval);

            Storage::put($filename, json_encode($data));
        }

        return json_decode(Storage::get($filename));
    }

    public function storeCandlesticks($symbol, $interval, $data)
    {
        $numSaved = 0;

        foreach ($data as $rowData)
        {
            $unixTimestamp = substr($rowData->openTime, 0, -3);
            $dateTime = Carbon::createFromTimestamp($unixTimestamp)->toDateTimeString();

            $cstick = Candlesticks1m::firstOrNew([
                'symbol' => $symbol,
                'datetime' => $dateTime,
            ]);

            $cstick->open = $rowData->open;
            $cstick->high = $rowData->high;
            $cstick->low = $rowData->low;
            $cstick->close = $rowData->close;
            $cstick->volume = $rowData->volume;

            $cstick->save();
            $numSaved++;
        }

        return $numSaved;
    }
}