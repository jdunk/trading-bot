<?php

namespace App\Blls;

use App\Blls\ExchangeInfoBll;
use App\Models\PriceTicker;
use Binance\API as BinanceApi;
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
        }
    }

    protected function addPriceTickerCalculatedFields(& $data)
    {
        $count = count($data);

        foreach ($data as $i => $row)
        {
            if (! $i) continue;

            $diff1 = $row->price;
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

    public function storePriceTicker($data)
    {
        // Set to nearest past 10-second interval
        $datetime_ = Carbon::createFromTimestampUTC(floor(now()->timestamp/10)*10)->toDateTimeString();

        $bulkInsert = [];

        foreach ($data as $tick)
        {
            if (in_array($tick->symbol, self::EXCLUDED_SYMBOLS)) { continue; }

            $bulkInsert[] = [
                'datetime_' => $datetime_,
                'symbol' => $tick->symbol,
                'price' => $tick->price,
            ];
        }

        DB::table('price_ticker')->insert($bulkInsert);

        return count($bulkInsert);
    }

    public function getAndStorePriceTicker()
    {
        $data = $this->getPriceTicker();

        return $this->storePriceTicker($data);
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
}