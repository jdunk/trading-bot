<?php

namespace App\Blls;

use App\Blls\ExchangeInfoBll;
use App\Models\Candlesticks1m;
use Binance\API as BinanceApi;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class CandlesticksBll
{
    protected $binanceApi;
    protected $exchangeInfoBll;

    public function __construct(
        BinanceApi $binanceApi
    )
    {
        $this->binanceApi = $binanceApi;
    }

    public function exchangeInfoBll()
    {
        if ($this->exchangeInfoBll)
        {
            return $this->exchangeInfoBll;
        }

        return $this->exchangeInfoBll = resolve(ExchangeInfoBll::class);
    }

    public function fetchApiData($symbol, $interval, $startTime = null, $endTime = null, $skipDateTimeParsing = false)
    {
        if (! $skipDateTimeParsing)
        {
            $startTime = $this->parseDate($startTime, 'from');
            $endTime = $this->parseDate($endTime, 'to');
        }

        $filename = implode('-', ['candlesticks', $symbol, $interval, 's-' . $startTime, 'e-' . $endTime]) . '.json';

        // Fetch from API
        logger("Fetching $symbol $interval candlesticks $startTime..$endTime");
        $data = $this->binanceApi->candlesticks($symbol, $interval, $startTime, $endTime);
        #logger(count(array_keys($data)) . " candlesticks fetched.");

        $enc = json_encode($data);
        #Storage::put($filename, $enc);
        #logger("Saved to $filename");

        return json_decode($enc);
    }

    public function storeCandlesticks($symbol, $interval, $data)
    {
        $numSaved = 0;

        foreach ($data as $rowData)
        {
            $unixTimestamp = substr($rowData->openTime, 0, -3);

            // Discard anything for the current minute (because the data isn't complete yet)
            if ((int) $unixTimestamp === (int) floor(now()->timestamp/60)*60)
            {
                continue;
            }

            $datetime_ = Carbon::createFromTimestampUTC($unixTimestamp)->toDateTimeString();

            $cstick = Candlesticks1m::firstOrNew([
                'symbol' => $symbol,
                'datetime_' => $datetime_,
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

    public function fetchAndStoreCandlesticks($symbols, $interval, $startTime = null, $endTime = null, $chunk = 1)
    {
        $symbols = (array) $symbols;

        if (strtoupper($symbols[0]) === 'ALL')
        {
            $symbols = $this->exchangeInfoBll()->allSymbols();
        }

        if (blank($chunk))
        {
            $chunk = 1;
        }

        $symbols = collect($symbols)->chunk(65);

        throw_if($chunk < 1 || $chunk > count($symbols), 'Invalid chunk number');

        $symbols = $symbols[$chunk-1];

        $startTime = $this->parseDate($startTime, 'from');
        $endTime = $this->parseDate($endTime, 'to');

        $totalNumStored = 0;

        foreach ($symbols as $symbol)
        {
            $data = $this->fetchApiData($symbol, $interval, $startTime, $endTime, true);
            $numStored = $this->storeCandlesticks($symbol, $interval, $data);
            #logger("Stored $numStored candlesticks_$interval: $symbol");
            $totalNumStored += $numStored;
        }

        logger("Stored $totalNumStored candlesticks_$interval");

        return $totalNumStored;
    }

    public function parseDate($value, $optionName)
    {
        $valueLen = strlen($value);

        // For some reason, Carbon can't ::parse() unix timestamps, so do it manually
        // Also, account/allow for the timestamp being in microseconds (i.e. x1000)
        // as the BN API gives it (and requires it).

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
                throw new Exception('Invalid ' . ($optionName ? "'$optionName' " : '') . 'date/time specified');
            }
        }

        return $value . '000';
    }

    public function mustBeWithinThePastYear($value, $optionName)
    {
        throw_if(
            $value <= Carbon::parse('1 year ago')->timestamp || $value > Carbon::now()->timestamp,
            Exception::class,
            ('Invalid ' . ($optionName ? "'$optionName' " : '') . 'date/time specified')
        );
    }
}