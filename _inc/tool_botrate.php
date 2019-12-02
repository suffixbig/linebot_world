<?php
/* 台灣銀行 最新匯率 */
class BotRate
{
    protected $botSourceSite = "http://rate.bot.com.tw";
    protected $botSourceUrl  = "xrt/flcsv/0/day";
    //buyCash 現金買入 ， buySpot即期買入
    protected $csvColumnNameMapping = [
        'currency' => 0, 'buyCash' => 2, 'buySpot' => 3, 'sellCash' => 12, 'sellSpot' => 13,
    ];
    protected $updateTime = 0;
    public $json          = ''; //抓到的資料

    public function __construct()
    {
    }
    public function getRates()
    {
        return $this->fetchRateFromSource();
    }
    protected function fetchCSV()
    {
        $url  = implode('/', [$this->botSourceSite, $this->botSourceUrl]);
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Host: rate.bot.com.tw\r\n",
            ),
        );
        $context = stream_context_create($opts);
        $file    = file_get_contents($url, false, $context);
        $this->parseResponseHeaderUpdateTime($http_response_header);
        return $file;
    }
    protected function parseCSV($csvContents)
    {
        $rates = [];
        $rows  = explode("\r\n", $csvContents);
        foreach ($rows as $index => $row) {
            if ($index == 0) {continue;}
            $data = explode(",", $row);
            if (count($data) < 10) {continue;}
            $rates += $this->parseCSVRow($data);
        }
        return $rates;
    }
    protected function parseCSVRow(array $row)
    {
        $rate = [];
        foreach ($this->csvColumnNameMapping as $name => $colIndex) {
            $$name = trim($row[$colIndex]);
        }
        //沒有即期買入價格就用現金買入價格
        if ($buySpot == '0.00000') {
            $buySpot = $buyCash;
        }
        if ($sellSpot == '0.00000') {
            $sellSpot = $sellCash;
        }
        $rate[$currency] = compact('buyCash', 'buySpot', 'sellCash', 'sellSpot');
        return $rate;
    }
    protected function parseResponseHeaderUpdateTime($headers)
    {
        $str = '';
        foreach ($headers as $header) {
            if (strpos($header, 'attachment; filename') === false) {continue;}
            $matches = [];
            preg_match('/ExchangeRate@(.*).csv/', $header, $matches);

            if (isset($matches[1])) {
                $this->updateTime = strtotime($matches[1]);
            }
        }
    }

    //抓資料的方法
    public function fetchRateFromSource()
    {
        $csvContents = $this->fetchCSV();
        $rates       = $this->parseCSV($csvContents);
        return [
            'createTime' => time(),
            'updateTime' => $this->updateTime,
            'rates'      => $rates,
        ];
    }

    //抓資料
    public function fetchRateFromSource2()
    {
        $this->json = $this->fetchRateFromSource();
    }
    //抓資料某一種幣
    public function get_code($code)
    {
        $this->json = $this->fetchRateFromSource();
        $usd        = $this->json['rates'][$code]; //抓臺灣銀行的賣價
        return $usd;
    }
    //換算
    public function c_conversion($base_currency, $code)
    {
        //print_r($this->json['rates']);exit;//匯率表
        if ($base_currency && $code) {
            $usd = $this->json['rates']['USD']['buySpot']; //抓臺灣銀行的賣價
            //基礎幣是台幣-不用處理
            if ($base_currency == 'TWD') {
                if (empty($this->json['rates'][$code]['buySpot'])) {
                    return false;
                } else {
                    $value = (float) 1 / $this->json['rates'][$code]['buySpot'];
                }
//轉換為匯率換算比
            } elseif ($base_currency == 'USD') {
                //換算-基礎幣是美金
                if ($code == 'TWD') {
                    $value = (float) $usd; //就等於
                } else {
                    //公式=美金賣出價除該幣賣出價
                    if (empty($this->json['rates'][$code]['buySpot'])) {
                        return false;
                    } else {
                        $value = (float) $usd / $this->json['rates'][$code]['buySpot'];
                    }
                    //美金賣出價除該幣賣出價
                }
            } else {
                //換算其他幣
                if ($code == 'TWD') {
                    $value = (float) $this->json['rates'][$base_currency]['buySpot']; //就等於
                } else {
                    //公式=美金賣出價除該幣賣出價
                    if (empty($this->json['rates'][$code]['buySpot'])) {
                        return false;
                    } else {
                        $value = (float) $this->json['rates'][$base_currency]['buySpot'] * (1 / $this->json['rates'][$code]['buySpot']);
                    }
                    //公式日幣換成 台幣 換成 該幣
                }
            }
            return $value;
        }
        return false;
    }
}
