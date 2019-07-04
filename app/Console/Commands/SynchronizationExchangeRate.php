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

    /**
     * Layer open api interface.
     */
    const EXCHANGE_API_URL = 'http://apilayer.net/api/live';

    /**
     * Client Instance.
     *
     * @var Client
     */
    private $client;

    /**
     * Store exchange rate of USD to JPY.
     *
     * @var null | double
     */
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

    /**
     * Request layer open api get current exchange rate.
     * 请求Layer的OpenApi获取当前汇率。
     *
     * return void
     */
    private function getExchangeRateData() :void
    {
        //请求开发接口获取汇率数据
        $response = $this->client->request('get', self::EXCHANGE_API_URL, [
            'query' => [
                'access_key' => config('services.coin_api_key.access_key'),
                'currencies' => 'JPY',
                'source' => 'USD',
                'format' => 1
            ],
        ]);
        //获取响应头中的状态码
        $codeStatus = $response->getStatusCode();
        if ($codeStatus === 200) {
            //解码
            $body = json_decode($response->getBody());
            //取整保留后2位
            $this->usdToJpy = round($body->quotes->USDJPY, 2);
        } else {
            //请求错误，需要检查
            echo 'Request Error' . PHP_EOL;
            //TODO: E-mail notification system
            //中断脚本
            die;
        }
    }

    /**
     * Execute the console command.
     * 执行控制台命令。
     *
     * @return void
     */
    public function handle() : void
    {
        //设置脚本执行时间
        set_time_limit(30);
        //获取当天时间
        $time = '[' . date('Y-m-d H:i:s') . ']';
        //初始化数据
        $usdToJpy = null;
        $cynToJpy = null;
        //初始化重试计数器
        $retryCount = 0;
        //判断是否获取到了美元数据
        while ($this->usdToJpy === null && $retryCount < 3) {
            $this->getExchangeRateData();
        }
        //判断是否成功获取了汇率数据
        if ($this->usdToJpy !== null) {
            $exchangeRateModel = new ExchangeRate();
            $exchangeRateModel->usd_to_jpy = $this->usdToJpy;
            if (!$exchangeRateModel->save()) {
                echo $time . 'Exchange rate data save fail' . PHP_EOL;
                //TODO: E-mail notification system
            }
        } else {
            echo 'Can\'t get exchange rate data' . PHP_EOL;
            //TODO: E-mail notification system
        }
        //中断脚本
        die();
    }


}
