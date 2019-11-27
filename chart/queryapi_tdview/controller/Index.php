<?php
namespace app\queryapi_tdview\controller;

use think\Cache;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return view('index/index');
    }

    public function charts()
    {
        return view('index/charts');
    }

    public function qnews()
    {
        return view('index/qnews');
    }

    public function lists()
    {
        return view('index/lists');
    }

//*****************************************************************************

    //获取快讯输出
    public function getnews()
    {
        $types = input('get.types');
        $url   = "http://alirm-com.konpn.com/query/qnews?pidx=1&ps=10&types=" . $types;
        echo ($this->http_request($url, config('AppCode')));
    }

    //获取实时行情数据输出
    public function rms()
    {
        $symbols = input('get.symbols');
        $url     = "http://alirm-com.konpn.com/query/comrms?symbols=" . $symbols;
        echo ($this->http_request($url, config('AppCode')));
    }

    //Tradingview图表接口
    public function tdvapi()
    {
        $m       = input('get._m');
        $symbol  = input('get.symbol');
        $period  = input('get.period');
        $lsttick = input('get.lsttick');
        $cb      = input('get.cb');

        if (is_numeric($lsttick)) {} else { $lsttick = 0;}

        echo ($this->tdvchartapirequest($m, $symbol, $period, $cb, $lsttick));
    }

    //Tradingview图表数据输出
    public function tdvchartapirequest($m, $symbol, $period, $cb, $lsttick)
    {
        $url = "http://alirm-com.konpn.com/aliv4tdv/view?_m=" . $m . "&symbol=" . $symbol . "&period=" . $period . "&lsttick=" . $lsttick . "&cb=" . urlencode($cb);

        $cache = ($m == "kmv2");
        $ckey  = $this->getCacheKey($symbol, $period);

        $retstr = "";

        if ($cache) {
            $retstr = cache($ckey);
        }

        if (empty($retstr) == true) {
            $retstr = $this->http_request($url, config('AppCode'));

            if ($cache && (empty($retstr) != true)) {
                if ($period == "D" || $period == "W" || $period == "M") {
                    cache($ckey, $retstr, 10 * 60);
                } else if ($period == "1M") {
                    cache($ckey, $retstr, 60);
                } else if ($period == "5M") {
                    cache($ckey, $retstr, 2 * 60);
                } else {
                    cache($ckey, $retstr, 5 * 60);
                }

            }

            echo ($retstr);
        } else {
            echo ($retstr);
        }
    }

    public function getCacheKey($symbol, $period)
    {
        $datekey = date("mdHi");
        if ($period == "D" || $period == "W" || $period == "M") {
            $datekey = date("md");
        } else if ($period == "1M") {
            $datekey = date("mdHi");
        } else if ($period == "5M") {
            $datekey = date("mdH") . floor(date("i") / 5);
        } else if ($period == "15M") {
            $datekey = date("mdH") . floor(date("i") / 15);
        } else if ($period == "30M") {
            $datekey = date("mdH") . floor(date("i") / 30);
        } else if ($period == "1H") {
            $datekey = date("mdH");
        } else if ($period == "4H") {
            $datekey = date("md") . floor(date("H") / 4);
        }

        return "querytdv" . $symbol . $period . $datekey;
    }
    //*****************************************************************************

//http请求
    public function http_request($url, $appcode)
    {
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        array_push($headers, "Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function http_request_clear($url)
    {
        $headers = array();
        array_push($headers, "Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
