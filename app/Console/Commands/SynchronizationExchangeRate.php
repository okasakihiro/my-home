<?php

namespace App\Console\Commands;

use App\ExchangeRate;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SynchronizationExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helper:get-exchange-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current time exchange rate from CurrencyLayer';

    const CNY_TO_JPY_PARAM = 'CNY/JPY';
    const USD_TO_JPY_PARAM = 'USD/JPY';
    const EXCHANGE_API_URL = 'https://rest.coinapi.io/v1/exchangerate/';

    private $client;
    private $usdToJpy = null;
    private $cnyToJpy = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    private function getExchangeRateData($currency) :void
    {
        $response = $this->client->request('get', self::EXCHANGE_API_URL . $currency, [
            'headers' => [
                'X-CoinAPI-Key' => config('services.coin_api_key.access_key')
            ],
        ]);
        $codeStatus = $response->getStatusCode();
        if ($codeStatus === 200) {
            $body = json_decode($response->getBody());
            $rate = $body->rate;
            switch ($currency) {
                case self::USD_TO_JPY_PARAM:
                    $this->usdToJpy = round($rate, 2);
                    break;
                case self::CNY_TO_JPY_PARAM:
                    $this->cnyToJpy = round($rate, 2);
                    break;
                default:
                    echo 'Currency type Error' . PHP_EOL;
                    die();
            }
        } else {
            //请求错误，需要检查
            echo 'Request Error' . PHP_EOL;
            //中断脚本
            die;
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(30);
        //获取当天时间
        $time = '[' . date('Y-m-d H:i:s') . ']';
        //初始化数据
        $usdToJpy = null;
        $cynToJpy = null;
        //初始化重试计数器
        $retryCount = 0;
        while (($this->usdToJpy === null || $this->cnyToJpy === null) && $retryCount < 3) {
            if ($this->usdToJpy === null) {
                $this->getExchangeRateData(self::USD_TO_JPY_PARAM);
                echo 1;
            }
            if ($this->cnyToJpy === null) {
                $this->getExchangeRateData(self::CNY_TO_JPY_PARAM);
                echo 2;
            }
        }

        if ($this->usdToJpy !== null && $this->cnyToJpy !== null) {
            $exchangeRateModel = new ExchangeRate();
            $exchangeRateModel->usd_to_jpy = $this->usdToJpy;
            $exchangeRateModel->cny_to_jpy = $this->cnyToJpy;
            if (!$exchangeRateModel->save()) {
                echo $time . 'Exchange rate data save fail' . PHP_EOL;
            }
        } else {
            echo 'Can\'t get exchange rate data' . PHP_EOL;
        }
    }


}
