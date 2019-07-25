<?php

namespace App\Http\Controllers;

use App\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getExchangeRate(Request $request)
    {
        $exchangeRate = ExchangeRate::orderBy('created_at', 'DESC')->first();
        if ($exchangeRate === null) {
            $exchangeRate = json_encode((object)[]);
        } else {
            $exchangeRate = $exchangeRate->toJson();
        }
        return $exchangeRate;
    }
}
