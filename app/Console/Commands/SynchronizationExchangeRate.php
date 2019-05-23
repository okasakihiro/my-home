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

    const EXCHANGE_API_URL = 'http://apilayer.net/api/live';

    private $client;
    private $usdToJpy = null;

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

    private function getExchangeRateData() :void
    {
        $response = $this->client->request('get', self::EXCHANGE_API_URL, [
            'query' => [
                'access_key' => config('services.coin_api_key.access_key'),
                'currencies' => 'JPY',
                'source' => 'USD',
                'format' => 1
            ],
        ]);
        $codeStatus = $response->getStatusCode();
        if ($codeStatus === 200) {
            $body = json_decode($response->getBody());
            $this->usdToJpy = round($body->quotes->USDJPY, 2);
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
        while ($this->usdToJpy === null && $retryCount < 3) {
            $this->getExchangeRateData();
            echo $this->usdToJpy;
        }

        if ($this->usdToJpy !== null) {
            $exchangeRateModel = new ExchangeRate();
            $exchangeRateModel->usd_to_jpy = $this->usdToJpy;
            if (!$exchangeRateModel->save()) {
                echo $time . 'Exchange rate data save fail' . PHP_EOL;
            }
        } else {
            echo 'Can\'t get exchange rate data' . PHP_EOL;
        }
    }


}
