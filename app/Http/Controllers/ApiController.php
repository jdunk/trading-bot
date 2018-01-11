<?php

namespace App\Http\Controllers;

use App\Blls\ExchangeInfoBll;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $exchangeInfoBll;

    public function __construct(
        ExchangeInfoBll $exchangeInfoBll
    )
    {
        $this->exchangeInfoBll = $exchangeInfoBll;
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
}
