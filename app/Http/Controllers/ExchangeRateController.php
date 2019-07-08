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
        return ExchangeRate::orderBy('created_at', 'DESC')->first()->toJson();
    }
}
