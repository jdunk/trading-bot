<?php

namespace App\Blls;

use App\Models\PriceTicker;
use Binance\API as BinanceApi;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;

class ExchangeBll
{
    protected $binanceApi;

    const EXCLUDED_SYMBOLS = [
        '123456',
    ];

    public function __construct(
        BinanceApi $binanceApi
    )
    {
        $this->binanceApi = $binanceApi;
    }

    public function updatePriceMetaData()
    {
        $priceRows = DB::table('price_ticker')
            ->whereNull('ma4')
            ->orderBy('symbol')
            ->orderBy('datetime_')
            ->select('symbol', 'datetime_', 'price');

        dd($priceRows);
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