<?php

namespace App\Blls;

use App\Blls\ExchangeInfoBll;
use App\Models\PriceTicker;
use Binance\API as BinanceApi;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;

class ExchangeBll
{
    protected $binanceApi;
    protected $exchangeInfoBll;

    const EXCLUDED_SYMBOLS = [
        '123456',
    ];

    const CANDLESTICK_INTERVALS = [
        '1m',
        '3m',
        '5m',
        '15m',
        '30m',
        '1h',
        '2h',
        '4h',
        '6h',
        '8h',
        '12h',
        '1d',
        '3d',
        '1w',
        '1M',
    ];

    const MAX_LOOKBACK_PRICE_TICKS = 20;
    const PRICE_TICKS_INTERVAL_SECONDS = 10;

    public function __construct(
        BinanceApi $binanceApi,
        ExchangeInfoBll $exchangeInfoBll
    )
    {
        $this->binanceApi = $binanceApi;
        $this->exchangeInfoBll = $exchangeInfoBll;
    }

    public function updatePriceMetaData($symbols = null)
    {
        if (blank($symbols))
        {
            return $this->updatePriceMetaData('ETHUSDT');
            #return $this->updatePriceMetaData($this->exchangeInfoBll->allSymbols());
        }

        $symbols = (array) $symbols;

        foreach ($symbols as $symbol)
        {
            $priceRows = DB::table('price_ticker')
                ->whereNull('ma4')
                ->where('symbol', '=', $symbol)
                ->orderBy('datetime_')
                ->select('datetime_', 'price')
                ->limit(30)
                ->get();

            $this->addPriceTickerCalculatedFields($priceRows);

            dd(array_except((array) $row, ['symbol', 'id', 'datetime_', 'price']));
        }
    }

    protected function addPriceTickerCalculatedFields(& $data)
    {
        $count = count($data);

        foreach ($data as $i => $row)
        {
            if (! $i) continue;

            foreach (range(1,4) as $n)
            {
                if ($n > $i) continue;

                $compPrice = $data[$i - $n]->price;
                $diff = $row->price - $compPrice;
                $row->{"perc_change_vs_$n"} = $diff / $compPrice;
            }

        }
    }

    public function getPriceTicker()
    {
        $filename = 'priceTicker.json';
        $priceData = $this->binanceApi->priceTicker();
        #Storage::put('price-ticker.json', json_encode($priceData));

        return $priceData;
    }

    public function getBookTicker()
    {
        $bookData = $this->binanceApi->bookTicker();
        #Storage::put('book-ticker.json', json_encode($bookData));

        return $bookData;
    }

    public function getRecentPrices()
    {
        return cache('recent-prices', []);
    }

    public function updatePrices()
    {
        $recentPrices = $this->getRecentPrices();
        $currentPrices = $this->getPriceTicker();

        $bulkInsertData = $this->doMetaCalcsAndMergeIntoRecentPrices($currentPrices, $recentPrices);

        // Update cache
        Cache::forever('recent-prices', $recentPrices);

        // Update db
        $this->storePriceTicker($bulkInsertData);
    }
    
    public function doMetaCalcsAndMergeIntoRecentPrices(& $currentPrices, & $recentPrices)
    {
        // Set to nearest past PRICE_TICKS_INTERVAL_SECONDS time
        $currentTime = Carbon::createFromTimestampUTC(floor(now()->timestamp / self::PRICE_TICKS_INTERVAL_SECONDS) * self::PRICE_TICKS_INTERVAL_SECONDS);

        $currentTimeStr = $currentTime->toDateTimeString();
        $currentTimestamp = $currentTime->timestamp;

        $bulkInsert = [];

        foreach ($currentPrices as $tick)
        {
            if (in_array($tick->symbol, self::EXCLUDED_SYMBOLS)) { continue; }

            if (empty($recentPrices[$tick->symbol]))
            {
                // Initialize structure
                $recentPrices[$tick->symbol] = (object) [
                    'prices' => [],
                    'meta' => [],
                ];
            }

            $rPrices = & $recentPrices[$tick->symbol]->prices;
            $rMeta = & $recentPrices[$tick->symbol]->meta;

            $numOtherPrices = count($rPrices);

            $currMeta = [];

            foreach (range(1,4) as $n)
            {
                if ($n > $numOtherPrices) continue;

                $compTimestamp = $this->getCompTimestamp($currentTimestamp, $n);

                if (empty($rPrices[$compTimestamp])) continue;

                $compPrice = $rPrices[$compTimestamp];
                $diff = $tick->price - $compPrice;

                $currMeta["perc_change_vs_$n"] = $diff / $compPrice;
            }

            // Merge in current price
            $rPrices[$currentTimestamp] = $tick->price;

            // Merge in current meta
            $rMeta[$currentTimestamp] = $currMeta;

            $bulkInsert[] = array_merge([
                'datetime_' => $currentTimeStr,
                'symbol' => $tick->symbol,
                'price' => $tick->price,
            ], $currMeta);
        }

// Unset all $currentPrices...<beyond-max-threshold>... elems
        return $bulkInsert;
    }

    public function storePriceTicker($bulkInsertData)
    {
        DB::table('price_ticker')->insert($bulkInsertData);
    }

    public function storeBookTicker($data)
    {
        // Set to nearest past 10-second interval
        $datetime_ = Carbon::createFromTimestampUTC(floor(now()->timestamp/10)*10)->toDateTimeString();

        $bulkInsert = [];

        foreach ($data as $tick)
        {
            $bulkInsert[] = [
                'datetime_' => $datetime_,
                'symbol' => $tick->symbol,
                'bid_price' => $tick->bidPrice,
                'ask_price' => $tick->askPrice,
            ];
        }

        DB::table('book_ticker')->insert($bulkInsert);

        return count($bulkInsert);
    }

    public function getAndStoreBookTicker()
    {
        $data = $this->getBookTicker();

        return $this->storeBookTicker($data);
    }

    private function getCompTimestamp($currTimestamp, $n)
    {
        return $currTimestamp - ($n * self::PRICE_TICKS_INTERVAL_SECONDS);
    }
}