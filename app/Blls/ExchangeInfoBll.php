<?php

namespace App\Blls;

use App\Blls\ExchangeBll;
use App\Models\ExchangeInfo;
use Binance\API as BinanceApi;
use Illuminate\Support\Facades\Storage;

class ExchangeInfoBll
{
    protected $binanceApi;

    public function __construct(
        BinanceApi $binanceApi
    )
    {
        $this->binanceApi = $binanceApi;
    }

    public function fetchExchangeInfo()
    {
        return ExchangeInfo::all();
    }

    public function getQuote($symbol = null)
    {
        $filename = 'priceTicker.json';
        $priceData = $this->binanceApi->priceTicker($symbol);
        Storage::put($filename, json_encode($priceData));

        $filename = 'bookTicker.json';
        $bookData = $this->binanceApi->bookTicker($symbol);
        Storage::put($filename, json_encode($bookData));

        return compact('priceData', 'bookData');
    }

    public function watchCharts($symbols)
    {
        $this->binanceApi->chart(['BNBUSDT', 'ETHUSDT'], '1m', function($api, $symbol, $chart) {
            echo "{$symbol} chart update\n";
            print_r($chart);
        });
    }

    public function fetchRawExchangeInfo()
    {
        $filename = 'exchangeInfo.json';

        // Fetch from API
        $data = $this->binanceApi->exchangeInfo();

        $enc = json_encode($data);
        #Storage::put($filename, $enc);

        return json_decode($enc);
    }

    public function storeExchangeInfo($data)
    {
        $numSaved = 0;

        foreach ($data->symbols as $s)
        {
            if (in_array($s->symbol, ExchangeBll::EXCLUDED_SYMBOLS)) {
                continue;
            }

            $ei = ExchangeInfo::firstOrNew([
                'symbol' => $s->symbol
            ]);

            $ei->symbol = $s->symbol;
            $ei->base_asset = $s->baseAsset;
            $ei->quote_asset = $s->quoteAsset;
            $ei->min_price = $s->filters[0]->minPrice;
            $ei->tick_size = $s->filters[0]->tickSize;
            $ei->min_qty = $s->filters[1]->minQty;
            $ei->step_size = $s->filters[1]->stepSize;
            $ei->min_notional = $s->filters[2]->minNotional;

            $ei->save();
            $numSaved++;
        }

        return $numSaved;
    }

    public function storeCandlesticks($symbol, $interval, $data)
    {
        $numSaved = 0;

        foreach ($data->symbols as $s)
        {
            if (in_array($s->symbol, self::$excludedExchanges)) {
                continue;
            }

            $ei = ExchangeInfo::firstOrNew([
                'symbol' => $s->symbol
            ]);

            $ei->symbol = $s->symbol;
            $ei->base_asset = $s->baseAsset;
            $ei->quote_asset = $s->quoteAsset;
            $ei->min_price = $s->filters[0]->minPrice;
            $ei->tick_size = $s->filters[0]->tickSize;
            $ei->min_qty = $s->filters[1]->minQty;
            $ei->step_size = $s->filters[1]->stepSize;
            $ei->min_notional = $s->filters[2]->minNotional;

            $ei->save();
            $numSaved++;
        }

        return $numSaved;
    }

    public function fetchRawCandlestickInfo($symbol, $interval, $limit=null, $startTime=null, $endTime=null)
    {
        $filename = 'candlesticks.json';

        if (! Storage::disk('local')->exists($filename))
        {
            // Fetch from API
            $data = $this->binanceApi->candlesticks($symbol, $interval);

            Storage::put($filename, json_encode($data));
        }

        return array_reverse([json_decode(Storage::get($filename))]);
    }
    
    public function allSymbols()
    {
        return array_pluck(ExchangeInfo::all(['symbol']), 'symbol');
    }
}