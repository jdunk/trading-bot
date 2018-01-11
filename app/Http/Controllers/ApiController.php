<?php

namespace App\Http\Controllers;

use App\Blls\ExchangeInfoBll;
use App\Blls\CandlesticksBll;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $exchangeInfoBll;
    protected $candlesticksBll;

    public function __construct(
        ExchangeInfoBll $exchangeInfoBll,
        CandlesticksBll $candlesticksBll
    )
    {
        $this->exchangeInfoBll = $exchangeInfoBll;
        $this->candlesticksBll = $candlesticksBll;
    }

    public function showExchangeInfo()
    {
        $data = $this->exchangeInfoBll->fetchExchangeInfo();
        return response()->json(['data' => $data]);
    }

    public function refreshExchangeInfo()
    {
        $data = $this->exchangeInfoBll->fetchRawExchangeInfo();
        $numSaved = $this->exchangeInfoBll->storeExchangeInfo($data);

        return response()->json([
            'num records saved' => $numSaved,
            'refreshed exchange info' => $data
        ]);
    }

    public function updateCandlesticks()
    {
        $symbol = 'ETHUSDT';
        $interval = '1m';
        $data = $this->candlesticksBll->fetchApiData($symbol, $interval);
        $numSaved = $this->candlesticksBll->storeCandlesticks($symbol, $interval, $data);

        return response()->json([
            //'num records saved' => $numSaved,
            'updated candlestick info' => $data
        ]);
    }
}
