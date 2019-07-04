<?php

namespace App\Http\Controllers;

use App\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function getExchangeRate(Request $request)
    {
        $exchangeRateDatum = ExchangeRate::orderBy('created_at', 'DESC')->first()->toJson();
        return $exchangeRateDatum;
    }
}
