<?php
//dezend by http://www.yunlu99.com/
if (!function_exists('month_config')) {
	function month_config()
	{
		$config['month_loss'] = explode('|', config('month_loss'));
		$config['month_rate'] = config('month_rate');
		$config['month_use_time'] = explode('|', config('month_use_time'));
		$config['month_position'] = config('month_position');
		return $config;
	}
}

if (!function_exists('typetoc')) {
	function typetoc($num)
	{
		$type_arr = array(5 => '免费配资', 1 => '按天配资', 2 => '按周配资', 3 => '按月配资', 4 => '试用配资', 6 => '模拟操盘');
		return $type_arr[$num];
	}
}

if (!function_exists('df_today_bonus')) {
	function df_today_bonus()
	{
		$date = date('Y-m-d', time() + 86400);
		$time = time() . mt_rand(100, 999);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://datainterface.eastmoney.com/EM_DataCenter/JS.aspx?type=GSRL&sty=GSRL&stat=8&fd=' . $date . '&p=1&ps=100&js=({pages:(pc),data:[(x)]})&cb=callback&callback=callback&_=' . $time);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		$num = strpos($output, 'data:') + 5;
		$tmd = substr($output, $num, -2);
		return $tmd;
	}
}

if (!function_exists('Handle')) {
	function Handle($v, $k, $rul)
	{
		$yuan = $zuan = $gu = $pai = $song = 0;
		$sub = '#' . substr($v['sj'], 2);
		$tmd = preg_match_all($rul, $sub, $ret);
		if ($tmd && isset($ret[0])) {
			$count = count($ret[0]);
			$num = $ret[0];
		}
		else {
			return false;
		}

		$pai = strpos($sub, '派');
		$song = strpos($sub, '送');
		$zuan = strpos($sub, '转');
		$yuan = strpos($sub, '元');
		$gu = strpos($sub, '股');
		$res[$k]['bonus'] = 0;
		$res[$k]['zuan'] = 0;
		$res[$k]['song'] = 0;

		switch ($count) {
		case 1:
			if ($pai < $yuan && empty($song) && empty($zuan) && empty($gu)) {
				$res[$k]['bonus'] = $num[0];
			}

			if ($song < $gu && empty($pai) && empty($zuan) && empty($yuan)) {
				$res[$k]['song'] = $num[0];
			}

			if ($zuan < $gu && empty($pai) && empty($song) && empty($yuan)) {
				$res[$k]['zuan'] = $num[0];
			}

			break;

		case 2:
			if ($gu < $yuan && empty($zuan) && !empty($yuan) && !empty($gu)) {
				$res[$k]['song'] = $num[0];
				$res[$k]['bonus'] = $num[1];
			}

			if ($gu < $yuan && empty($song) && !empty($yuan) && !empty($gu)) {
				$res[$k]['zuan'] = $num[0];
				$res[$k]['bonus'] = $num[1];
			}

			if ($yuan < $gu && empty($zuan) && !empty($yuan) && !empty($gu)) {
				$res[$k]['song'] = $num[1];
				$res[$k]['bonus'] = $num[0];
			}

			if ($yuan < $gu && empty($song) && !empty($yuan) && !empty($gu)) {
				$res[$k]['zuan'] = $num[1];
				$res[$k]['bonus'] = $num[0];
			}

			break;

		case 3:
			if ($song < $zuan && $zuan < $pai) {
				$res[$k]['song'] = $num[0];
				$res[$k]['zuan'] = $num[1];
				$res[$k]['bonus'] = $num[2];
			}

			if ($song < $pai && $pai < $zuan) {
				$res[$k]['song'] = $num[0];
				$res[$k]['bonus'] = $num[1];
				$res[$k]['zuan'] = $num[2];
			}

			if ($zuan < $song && $song < $pai) {
				$res[$k]['zuan'] = $num[0];
				$res[$k]['song'] = $num[1];
				$res[$k]['bonus'] = $num[2];
			}

			if ($zuan < $pai && $pai < $song) {
				$res[$k]['zuan'] = $num[0];
				$res[$k]['bonus'] = $num[1];
				$res[$k]['song'] = $num[2];
			}

			if ($pai < $song && $song < $zuan) {
				$res[$k]['bonus'] = $num[0];
				$res[$k]['song'] = $num[1];
				$res[$k]['zuan'] = $num[2];
			}

			if ($pai < $zuan && $zuan < $song) {
				$res[$k]['bonus'] = $num[0];
				$res[$k]['zuan'] = $num[1];
				$res[$k]['song'] = $num[2];
			}

			break;

		default:
			$res = false;
			break;
		}

		return $res;
	}
}

if (!function_exists('hexun_bonus')) {
	function hexun_bonus($code = '')
	{
		if (empty($code)) {
			return false;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://stockdata.stock.hexun.com/gszl/data/jsondata/fhrzfhTable.ashx?no=' . $code);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		$num = strpos($output, 'list:') + 5;
		$tmd = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), $num, -2);
		$tmd = str_replace('{', '{"', $tmd);
		$tmd = str_replace(':', '":', $tmd);
		$tmd = str_replace(',', ',"', $tmd);
		$tmd = str_replace('},"{', '},{', $tmd);
		$tmd = str_replace('\'', '"', $tmd);
		$tmd = str_replace('\'{', '\'[{', $tmd);
		$tmd = str_replace('}\'', '}]\'', $tmd);
		$tmd = str_replace('target="_blank"', '', $tmd);
		return $tmd;
	}
}

if (!function_exists('qq_bonus')) {
	function qq_bonus()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://web.ifzq.gtimg.cn/stock/notice/tzbw/search?_var=v_tzbw_index');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}

if (!function_exists('HttpGet')) {
	function HttpGet($url, $status = false)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36');

		if ($status) {
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept:application', 'X-Request:JSON', 'X-Requested-With:XMLHttpRequest'));
		}

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}

if (!function_exists('Http_Spider')) {
	function Http_Spider($url)
	{
		$ch = curl_init();
		$ip = '115.239.211.112';
		$timeout = 15;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip . '', 'CLIENT-IP:' . $ip . ''));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.baidu.com/');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$content = curl_exec($ch);
		return $content;
	}
}

?>
