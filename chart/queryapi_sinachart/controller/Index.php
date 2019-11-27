<?php
namespace app\queryapi_sinachart\controller;
use think\Controller;
use think\Cache;

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
		$types=input('get.types');
		$url = "http://alirm-com.konpn.com/query/qnews?pidx=1&ps=10&types=".$types;
		echo($this->http_request($url,config('AppCode')));
    }

	//获取实时行情数据输出
	public function rms()
	{
		$symbols=input('get.symbols');
		$url = "http://alirm-com.konpn.com/query/comrms?symbols=".$symbols;
		echo($this->http_request($url,config('AppCode')));
	}

	//新浪图表数据输出
	public function sinachartapi()
	{
		$m=input('get._m');
		$symbol=input('get.symbol');
		$list=input('get.list');
		$cb=input('get.cb');

		$scale=input('get.scale');
		if(is_numeric($scale)){}else{$scale=1;}

		$datalen=input('get.datalen');
		if(is_numeric($datalen)){}else{$datalen=1440;}

		echo($this->sinachartapirequest($m,$symbol,$list,$cb,$scale,$datalen));
	}

//新浪图表函数
	public function sinachartapirequest($m,$symbol,$list,$cb,$scale,$datalen)
	{
		if($m=="utilityjs")
		{
			//获取授权js.不计次数
			$url = "http://map.konpn.com:10002/html/js/utils/utility.js?_=".time();
			echo($this->http_request_clear($url));
		}
		else
		{
			$withcache=((empty($symbol)!=true)&&($m=="m"||$m=="t1"||$m=="t5"||$m=="k"));

			$ckey=$this->getCacheKey($symbol, $m, $scale);
	
			$cachekms = "";

			if($withcache){$cachekms=cache($ckey);}

			if(empty($cachekms)==true)
			{
				$url = "http://alirm-com.konpn.com/alirm4sc/view?_m=".$m."&symbol=".$symbol."&list=".$list."&scale=".$scale."&datalen=".$datalen."&cb=".urlencode($cb);

				$cachekms=$this->http_request($url,config('AppCode'));

				if($withcache && (empty($cachekms)!=true))
				{
					$cachekms2 = str_replace(urldecode($cb),"",$cachekms);;		

					if($m=="k"){cache($ckey,$cachekms2,10*60);}
					else if($m=="t1"){cache($ckey,$cachekms2,60);}
					else if($m=="t5"){cache($ckey,$cachekms2,60);}
					else if($m=="m")
					{
						if($scale=="1"){cache($ckey,$cachekms2,60);}
						else if($scale=="5"){cache($ckey,$cachekms2,120);}
						else {cache($ckey,$cachekms2,600);}
					}
				}

				return $cachekms;
			}
			else
			{
				return urldecode($cb).$cachekms;
			}
		}
	}

	function getCacheKey($symbol, $m, $scale)
	{
		$datekey = date("mdHi");
		if ($m == "k") { $datekey = date("md"); }
		else if ($m == "t1") { $datekey = date("mdHi"); }
		else if ($m == "t5") { $datekey = date("mdHi"); }
		else if ($m == "m")
		{
			if ($scale == 1) $datekey = date("mdHi");
			else if ($scale == 5) $datekey = date("mdH").floor(date("i")/5);
			else if ($scale == 15) $datekey = date("mdH").floor(date("i")/ 15);
			else if ($scale == 30) $datekey = date("mdH").floor(date("i")/ 30);
			else if ($scale == 60) $datekey = date("mdH");
			else if ($scale == 240) $datekey = date("md").floor(date("H")/4);
		}

		return "querysina".$symbol.$m.$scale.$datekey;
	}
	//*****************************************************************************

//http请求
    function http_request($url, $appcode)
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
		if (1 == strpos("$".$url, "https://"))
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		}

		$result = curl_exec ( $curl );
        curl_close($curl);
        return $result;
    }

   function http_request_clear($url)
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
		if (1 == strpos("$".$url, "https://"))
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		}

		$result = curl_exec ( $curl );
        curl_close($curl);
        return $result;
    }
}