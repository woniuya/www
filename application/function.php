<?php
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\Log;
use think\Config;

/*if (PHP_SAPI != 'cli') {
	$defurl = $_SERVER['SERVER_NAME'];
	$dfu = explode('127.0.0.1', $defurl);

	if (count($dfu) <= 1) {
		$dfu = explode('loanwang.com', $defurl);
	}

	if (count($dfu) <= 1) {
		$dfu = explode('lvmaque.net', $defurl);
	}

	if (count($dfu) <= 1) {
		$dfu = explode('localhost', $defurl);
	}

	if (count($dfu) <= 1) {
		header('Content-type:text/html;charset=\'utf-8\'');
		exit('<div style=\'height:200px;line_height:200px;font-size:24px;text-align:center;\'>对不起,该程序没有对您正在使用的域名进行授权,如需使用，请到联系客服获取授权！</div>');
	}
}*/

if (!function_exists('sned_sms_ali')) {
	/**
	 * 发送阿里云短信
	 *
	 * @param [type] $mobile 手机号
	 * @param [type] $type 类型
	 * @param [type] $data 参数
	 * @return void
	 */
	function sned_sms_ali($mobile,$type,$data)
	{
		$config=Config::get('ali_sms');
		AlibabaCloud::accessKeyClient($config['accessKeyId'],$config['accessSecret'])
		->regionId('cn-hangzhou')
		->asDefaultClient();
		$query=[
			'RegionId'=>'cn-hangzhou',
			'PhoneNumbers'=>$mobile,
			'SignName'=>$config['SignName'],
			'TemplateCode'=>$config['TemplateCode'][$type],
			'TemplateParam'=>json_encode($data)
		];
		try {
			$result = AlibabaCloud::rpc()
					->product('Dysmsapi')
					// ->scheme('https') // https | http
					->version('2017-05-25')
					->action('SendSms')
					->method('POST')
					->host('dysmsapi.aliyuncs.com')
					->options(['query' => $query])
					->request()
					->toArray();
			
			return $result['Code']=='OK';
		} catch (ClientException $e) {
			Log::record($e->getErrorMessage() . PHP_EOL);
			return false;
		} catch (ServerException $e) {
			Log::record($e->getErrorMessage() . PHP_EOL);
			return false;
		}
	}
}

if (!function_exists('build_rid_no')) {
	function build_rid_no()
	{
		$numbers = mt_rand(100, 1000) + time();
		return $numbers;
	}
}

if (!function_exists('gs')) {
	function gs($fun, $p = '')
	{
		return plugin_action('GreenSparrow/Sparrow/' . $fun, $p);
	}
}

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

if (!function_exists('week_config')) {
	function week_config()
	{
		$config['week_loss'] = explode('|', config('week_loss'));
		$config['week_rate'] = config('week_rate');
		$config['week_use_time'] = explode('|', config('week_use_time'));
		$config['week_position'] = config('week_position');
		return $config;
	}
}

if (!function_exists('free_config')) {
	function free_config()
	{
		$config['free_loss'] = explode('|', config('free_loss'));
		$config['free_set'] = explode('|', config('free_set'));
		$config['day_position'] = config('day_position');
		return $config;
	}
}

if (!function_exists('day_config')) {
	function day_config()
	{
		$config['day_loss'] = explode('|', config('day_loss'));
		$config['day_rate'] = config('day_rate');
		$config['day_position'] = config('day_position');
		$config['day_use_time'] = explode('|', config('day_use_time'));
		return $config;
	}
}

if (!function_exists('global_config')) {
	function global_config()
	{
		$config['money_range'] = explode('|', config('money_range'));
		$config['limit_fee'] = config('limit_fee');
		$config['commission'] = config('commission');
		$config['stamp_duty'] = config('stamp_duty');
		$config['transfer_fee'] = config('transfer_fee');
		$config['profit_share'] = config('profit_share');
		$config['stop_fee'] = config('stop_fee');
		$config['stock_count'] = config('stock_count');
		$config['rebate'] = config('rebate');
		return $config;
	}
}

if (!function_exists('getEndDay2')) {
	function getEndDay2($start = 'now', $exception = '')
	{
		$starttime = strtotime($start);
		$weekday = date('N', $starttime);
		$tmpday = date('Y-m-d', $starttime);

		if (is_array($exception)) {
			$bfd = in_array($tmpday, $exception);
		}
		else {
			$bfd = $exception == $tmpday;
		}

		if ($weekday <= 5 && !$bfd) {
			$status = 1;
		}
		else {
			$status = 2;
		}

		return $status;
	}
}

if (!function_exists('festival')) {
	function festival()
	{
		$t0 = date('Y', time());
		$data = cache('festival' . $t0);

		if ($data) {
			return $data;
		}
		else {
			$hello = array();
			$d0 = config('legal_holiday');

			if (empty($d0)) {
				$url = 'http://tool.bitefu.net/jiari/?d=' . $t0;
				$d0 = file_get_contents($url);
				$d = json_decode($d0);

				if (is_object($d->{$t0})) {
					$n = 0;

					foreach ($d->{$t0} as $key => $value) {
						$hello[$n] = $key;
						$n++;
					}
				}
				else {
					$hello = $d->{$t0};
				}
			}
			else {
				$d = json_decode($d0);
				$hello = $d->{$t0};
			}

			cache('festival' . $t0, $hello);
			return $hello;
		}
	}
}

if (!function_exists('calculate_rate')) {
	function calculate_rate($multiple, $rate, $type, $deposit_money, $endTime)
	{
		if ($type == 1 || $type == 2) {
			$duration = 0;
			$durationTmp = ceil(($endTime - mktime(0, 0)) / 3600 / 24);
			$durationDays = array();

			for ($i = 0; $i < $durationTmp; $i++) {
				$durationDays[$i] = date('Y-m-d', strtotime('+' . $i . ' day'));
			}

			if ($type == 1) {
				$hello = festival();

				foreach ($durationDays as $v) {
					if (getEndDay2($v, $hello) == 1) {
						$duration++;
					}
				}
			}

			if ($type == 2) {
				$duration = round($durationTmp / 7, 2);
			}
		}
		else {
			$durationTmp = ceil(($endTime - mktime(0, 0)) / 3600 / 24);
			$duration = round($durationTmp / 30, 2);
		}

		$fee = round($deposit_money * $multiple * $duration * $rate / 100, 2);
		return $fee;
	}
}

if (!function_exists('getEndDay')) {
	function getEndDay($start = 'now', $offset = 0, $exception = '')
	{
		$starttime = strtotime($start);
		$endtime = $starttime + $offset * 24 * 3600;
		$end = date('Y-m-d', $endtime);
		$weekday = date('N', $starttime);
		$newoffset = 0;

		for ($i = 1; $i <= $offset; $i++) {
			$today = date('md', $starttime + $i * 24 * 3600);

			switch (($weekday + $i) % 7) {
			case 6:
				$newoffset += 1;
				break;

			case 0:
				$newoffset += 1;
				break;

			default:
				if (is_array($exception)) {
					if (in_array($today, $exception)) {
						$newoffset += 1;
					}
				}
				else if ($today == $exception) {
					$newoffset += 1;
				}

				break;
			}
		}

		if (0 < $newoffset) {
			return getEndDay($end, $newoffset, $exception);
		}
		else {
			return $end;
		}
	}

	if (!function_exists('z_market')) {
		function z_market($code)
		{
			$res = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($res) {
			case 1:
				$res = qq_market($code);
				$data = qq_to_api($res);
				break;

			case 2:
				$res = sina_market($code);
				$data = sina_to_api($res);
				break;

			case 3:
				$res = gs('market', array($code));

				if (empty($res['error'])) {
					$data = $res[0];
				}
				else {
					$data = $res['error'];
				}

				break;

			default:
				$res = qq_market($code);
				$data = qq_to_api($res);
				break;
			}

			return $data;
		}
	}

	if (!function_exists('z_market_bat')) {
		function z_market_bat($code)
		{
			$d = '';
			$data = array();
			$res = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');
			switch ($res) {
			case 1:
				$res = explode(',', $code);
				$count = count($res);

				foreach ($res as $v) {
					$d .= fenxi($v) . ',';
				}

				$tmd = qq_market_b($d);

				if (empty($tmd)) {
					break;
				}

				for ($i = 0; $i < $count; $i++) {
					$k = $i * 53;

					for ($n = 1; $n <= 53; $n++) {
						$num = $n + $k;
						$data[$i][$n] = $tmd[$num];
					}

					$data[$i][0] = '';
					$data[$i] = qq_to_api($data[$i]);
				}

				break;

			case 2:
				$res = explode(',', $code);
				$count = count($res);

				foreach ($res as $v) {
					$d .= fenxi($v) . ',';
				}
				$tmd = sina_market_b($d);

				if (empty($tmd)) {
					break;
				}

				for ($i = 0; $i < $count; $i++) {
					$k = $i * 32;

					for ($n = 0; $n < 32; $n++) {
						$num = $n + $k;
						$data[$i][$n] = $tmd[$num];
					}

					if ($i == 0) {
						list($tmp) = explode(';', $data[$i][0]);
						$data[$i][0] = substr($tmp, 11, 8);
						$data[$i][32] = substr($tmp, 21);
					}
					else {
						list(, $tmp) = explode(';', $data[$i][0]);
						$data[$i][0] = substr($tmp, 12, 8);
						$data[$i][32] = substr($tmp, 22);
					}
					$data[$i] = sina_to_api_b($data[$i]);
				}

				break;

			case 3:
				$res = gs('market_bat', array($code));

				if (!empty($res['error'])) {
					$data = $res['error'];
					break;
				}

				if (is_array($res)) {
					unset($res[0]);
					$count = count($res);

					for ($i = 0; $i < $count; $i++) {
						$data[$i] = api_filter($res[$i + 1]);
					}
				}

				break;

			default:
				$res = explode(',', $code);
				$count = count($res);

				foreach ($res as $v) {
					$d .= fenxi($v) . ',';
				}

				$tmd = qq_market_b($d);

				for ($i = 0; $i < $count; $i++) {
					$k = $i * 53;

					for ($n = 1; $n <= 53; $n++) {
						$num = $n + $k;
						$data[$i][$n] = $tmd[$num];
					}

					$data[$i][0] = '';
					$data[$i] = qq_to_api($data[$i]);
				}

				break;
			}

			return $data;
		}
	}

	if (!function_exists('sohu_keyword')) {
		function sohu_keyword($key)
		{
			$e = preg_match('/^[\\x{4E00}-\\x{9FA5}]+$/u', $key);

			if ($e) {
				return '';
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://q.stock.sohu.com/app1/stockSearch?method=search&callback=&type=cnwbj&sTypeId=15,16&keyword=' . $key . '&_=0.4941991283558309');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), 42, -5);
			$t3 = explode('],["', $t2);
			$res = array();

			foreach ($t3 as $k => $v) {
				$tmd = explode(',', $v);

				if ($tmd[0] == '') {
					$res[$k] = '';
					continue;
				}

				if (strlen($key) === 6) {
					if ($tmd[6] != '15') {
						$res[$k] = '';
						continue;
					}

					$res[0]['code'] = substr($tmd[0], 3, -1);
					$res[0]['name'] = substr($tmd[2], 1, -1);
					$res[0]['pin'] = substr($tmd[3], 1, -1);
				}
				else {
					$res[$k]['code'] = substr($tmd[0], 3, -1);
					$res[$k]['name'] = substr($tmd[2], 1, -1);
					$res[$k]['pin'] = substr($tmd[3], 1, -1);
				}
			}

			ksort($res);
			return $res;
		}
	}

	if (!function_exists('sina_market_b')) {
		function sina_market_b($code)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://hq.sinajs.cn/list=' . $code);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = explode(',', mb_convert_encoding($output, 'utf-8', 'gbk'));
			return $t2;
		}
	}

	if (!function_exists('sina_market')) {
		function sina_market($code)
		{
			$d = fenxi($code);
			$randm = time() . mt_rand(100, 999);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://hq.sinajs.cn/rn=' . $randm . '&list=' . $d . ',' . $d . '_i');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$pos = strpos($output, '=') + 2;
			$output = substr($output, $pos, -3);
			$t2 = explode(',', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$t2[32] = $t2[0];
			$t2[0] = $d;
			return $t2;
		}
	}

	if (!function_exists('qq_market')) {
		function qq_market($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/q=' . $d);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = explode('~', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$t2[0] = substr($t2[0], 2, 8);
			return $t2;
		}
	}

	if (!function_exists('z_minute_k')) {
		function z_minute_k($code)
		{
			$res = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($res) {
			case 1:
				$data = qq_minute_k($code);
				break;

			case 2:
				$data = wy_minute_k($code);
				break;

			case 3:
				$data = api_minute_k($code);
				break;

			default:
				$data = qq_minute_k($code);
				break;
			}

			return $data;
		}
	}

	if (!function_exists('z_day_k')) {
		function z_day_k($code)
		{
			$res = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($res) {
			case 1:
				$data = qq_day_k($code);
				break;

			case 2:
				$data = sina_day_k($code);
				break;

			case 3:
				$data = api_k($code, 5);
				break;

			default:
				$data = qq_day_k($code);
				break;
			}

			return $data;
		}
	}

	if (!function_exists('z_week_k')) {
		function z_week_k($code)
		{
			$res = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($res) {
			case 1:
				$data = qq_week_k($code);
				break;

			case 2:
				$data = sina_week_k($code);
				break;

			case 3:
				$data = api_k($code, 6);
				break;

			default:
				$data = qq_week_k($code);
				break;
			}

			return $data;
		}
	}

	if (!function_exists('z_month_k')) {
		function z_month_k($code)
		{
			$res = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($res) {
			case 1:
				$data = qq_month_k($code);
				break;

			case 2:
				$data = sina_month_k($code);
				break;

			case 3:
				$data = api_k($code, 7);
				break;

			default:
				$data = qq_month_k($code);
				break;
			}

			return $data;
		}
	}

	if (!function_exists('api_minute_k')) {
		function api_minute_k($code)
		{
			if ($code === 'sh000001') {
				$code = '999999';
			}

			$tmd = gs('TimeData', $code);
			unset($tmd[0]);
			$time = strtotime(date('y-m-d 09:29:00', time()));
			$res = array();

			foreach ($tmd as $k => $v) {
				if ($k == 122) {
					$time = $time + 5400;
				}

				$res[$k - 1]['time'] = date('Hi', $time + $k * 60);
				$res[$k - 1]['price'] = $v[0];
				$res[$k - 1]['volume'] = $v[1];
			}

			return $res;
		}
	}

	if (!function_exists('api_k')) {
		function api_k($code, $period)
		{
			$index = 0;

			if ($code === 'sh000001') {
				$code = '999999';
				$index = 1;
			}

			if ($code === '399001' || $code === '399006') {
				$index = 1;
			}

			$tmd = gs('GetBar', array($code, $period, $index));
			unset($tmd[0]);
			$res = array();

			foreach ($tmd as $k => $v) {
				$res[$k - 1]['time'] = substr($v[0], 2);
				$res[$k - 1]['open'] = substr($v[1], 0, -4);
				$res[$k - 1]['close'] = substr($v[2], 0, -4);
				$res[$k - 1]['high'] = substr($v[3], 0, -4);
				$res[$k - 1]['low'] = substr($v[4], 0, -4);
				$res[$k - 1]['volume'] = substr($v[5], 0, -2);
			}

			return $res;
		}
	}

	if (!function_exists('qq_minute_k')) {
		function qq_minute_k($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://data.gtimg.cn/flashdata/hushen/minute/' . $d . '.js');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$count = count($t2);
			unset($t2[0]);
			unset($t2[1]);
			unset($t2[$count - 1]);

			foreach ($t2 as $k => $v) {
				$t2[$k - 2] = explode(' ', trim($v));
			}

			unset($t2[$count - 2]);
			unset($t2[$count - 3]);
			ksort($t2);
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k]['time'] = $v[0];
				$res[$k]['price'] = $v[1];
				$res[$k]['volume'] = $v[2];
			}

			$ret = array();

			foreach ($res as $k => $v) {
				$ret[$k]['time'] = $res[$k]['time'];
				$ret[$k]['price'] = $res[$k]['price'];

				if ($k == 0) {
					$ret[$k]['volume'] = $res[$k]['volume'];
					continue;
				}

				$ret[$k]['volume'] = (string) ($res[$k]['volume'] - $res[$k - 1]['volume']);
			}

			return $ret;
		}
	}

	if (!function_exists('z_search_keyword')) {
		function z_search_keyword($key)
		{
			$res = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($res) {
			case 1:
				$data = qq_keyword($key);
				break;

			case 2:
				$data = sina_keyword($key);
				break;

			case 3:
				$data = wy_keyword($key);
				break;

			default:
				$data = qq_keyword($key);
				break;
			}

			return $data;
		}
	}

	if (!function_exists('wy_keyword')) {
		function wy_keyword($key)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://quotes.money.163.com/stocksearch/json.do?type=&count=10&word=' . $key . '&t=0.599924786016345');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = json_decode(substr($output, 27, -1));
			$res = array();

			if (empty($t2)) {
				return $res;
			}

			foreach ($t2 as $k => $v) {
				$res[$k]['code'] = $v->symbol;
				$res[$k]['name'] = $v->name;
				$res[$k]['pin'] = $v->spell;
			}

			return $res;
		}
	}

	if (!function_exists('qq_keyword')) {
		function qq_keyword($key)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://smartbox.gtimg.cn/s3/?v=2&q=' . $key . '&t=all');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$output = preg_replace_callback('#\\\\u([0-9a-f]+)#i', function($m) {
				return mb_convert_encoding(pack('H4', $m[1]), 'UTF-8', 'UCS-2');
			}, $output);
			$t2 = substr($output, 8, -1);

			if ($t2 == 'N"') {
				return '';
			}

			$t3 = explode('^', $t2);
			$res = array();

			foreach ($t3 as $k => $v) {
				$tmd = explode('~', $v);
				$res[$k]['code'] = $tmd[1];
				$res[$k]['name'] = $tmd[2];
				$res[$k]['pin'] = $tmd[3];
			}

			return $res;
		}
	}

	if (!function_exists('hx_keyword')) {
		function hx_keyword($key)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://so.hexun.com/ajax.do?key=' . $key . '&type=stock?math=0.4235173752531409');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), 19);
			$t2 = json_decode($t2);
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k]['code'] = $v->code;
				$res[$k]['name'] = $v->name;
				$res[$k]['pin'] = $v->pinyin;
			}

			return $res;
		}
	}

	if (!function_exists('jyj_keyword')) {
		function jyj_keyword($key)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://code.jrjimg.cn/code?1=1&item=10&areapri=cn|hk|us|stb|ylbtg&typepri=s|w|i|f|b.hg&key=' . $key . '&d=131149');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), 26, -63);
			$t3 = explode('},{', $t2);
			$res = array();

			foreach ($t3 as $k => $v) {
				$tmd = explode('",', $v);
				list(, $res[$k]['code']) = explode(':"', $tmd[1]);
				list(, $res[$k]['name']) = explode(':"', $tmd[2]);
				list(, $res[$k]['pin']) = explode(':"', $tmd[3]);
			}

			return $res;
		}
	}

	if (!function_exists('api_keyword')) {
		function api_keyword($key)
		{
			$res = array();

			if (preg_match('/\\d/', $key)) {
				$res = gs('SearchByCode', array($Name = $key));
			}

			if (preg_match('/[A-Za-z]/', $key)) {
				$res = gs('searchByName', array($Name = $key));
			}

			if (0 < preg_match('/[\\x{4e00}-\\x{9fa5}]/u', $key)) {
				$res = gs('searchByName', array($Name = $key));
			}

			return $res;
		}
	}

	if (!function_exists('')) {
		function sina_keyword($key)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://suggest3.sinajs.cn/suggest/type=&key=' . $key . '&name=suggestdata_' . time());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = substr(mb_convert_encoding($output, 'utf-8', 'gbk'), 28, -3);
			$t3 = explode(';', $t2);
			$res = array();

			foreach ($t3 as $k => $v) {
				$tmd = explode(',', $v);

				if ($tmd[1] != '31') {
					$res[$k]['code'] = $tmd[2];
					$res[$k]['name'] = $tmd[4];
					$res[$k]['pin'] = strtoupper($tmd[5]);
				}
			}

			return $res;
		}
	}

	if (!function_exists('')) {
		function qq_month_k($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://data.gtimg.cn/flashdata/hushen/latest/monthly/' . $d . '.js');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$count = count($t2);
			unset($t2[0]);
			unset($t2[1]);
			unset($t2[$count - 1]);

			foreach ($t2 as $k => $v) {
				$t2[$k - 2] = explode(' ', trim($v));
			}

			unset($t2[$count - 2]);
			unset($t2[$count - 3]);
			ksort($t2);
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k]['time'] = $v[0];
				$res[$k]['open'] = $v[1];
				$res[$k]['close'] = $v[2];
				$res[$k]['high'] = $v[3];
				$res[$k]['low'] = $v[4];
				$res[$k]['volume'] = $v[5];
			}

			return $res;
		}
	}

	if (!function_exists('')) {
		function qq_week_k($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://data.gtimg.cn/flashdata/hushen/latest/weekly/' . $d . '.js');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$count = count($t2);
			unset($t2[0]);
			unset($t2[1]);
			unset($t2[$count - 1]);

			foreach ($t2 as $k => $v) {
				$t2[$k - 2] = explode(' ', trim($v));
			}

			unset($t2[$count - 2]);
			unset($t2[$count - 3]);
			ksort($t2);
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k]['time'] = $v[0];
				$res[$k]['open'] = $v[1];
				$res[$k]['close'] = $v[2];
				$res[$k]['high'] = $v[3];
				$res[$k]['low'] = $v[4];
				$res[$k]['volume'] = $v[5];
			}

			return $res;
		}
	}

	if (!function_exists('')) {
		function fenxi($code)
		{
			switch (substr($code, 0, 1)) {
			case '0':
				$d = 'sz' . $code;
				break;

			case '3':
				$d = 'sz' . $code;
				break;

			case '1':
				$d = 'sz' . $code;
				break;

			case '2':
				$d = 'sz' . $code;
				break;

			case '6':
				$d = 'sh' . $code;
				break;

			case '5':
				$d = 'sh' . $code;
				break;

			case '9':
				$d = 'sh' . $code;
				break;

			default:
				$d = $code;
				break;
			}

			return $d;
		}
	}

	if (!function_exists('object2array')) {
		function object2array($object)
		{
			$array = array();

			if (is_object($object)) {
				foreach ($object as $key => $value) {
					$array[$key] = $value;
				}
			}
			else {
				$array = $object;
			}

			return $array;
		}
	}

	if (!function_exists('wy_minute_k')) {
		function wy_minute_k($code)
		{
			switch (substr($code, 0, 1)) {
			case '0':
				$d = '1' . $code;
				break;

			case '3':
				$d = '1' . $code;
				break;

			case '6':
				$d = '0' . $code;
				break;

			case 's':
				$d = '0' . substr($code, 2);
				break;

			default:
				$d = $code;
				break;
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://img1.money.126.net/data/hs/time/today/' . $d . '.json');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$tmd = json_decode($output)->data;
			$res = array();

			foreach ($tmd as $k => $v) {
				$res[$k]['time'] = $v[0];
				$res[$k]['price'] = $v[1];
				$res[$k]['volume'] = round($v[3] / 100, 0);
			}

			return $res;
		}
	}

	if (!function_exists('sina_day_k')) {
		function sina_day_k($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol=' . $d . '&scale=240&ma=no&datalen=100');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$output = substr($output, 2);
			$output = substr($output, 0, -3);
			$t2 = explode('},{', $output);
			$res = array();

			foreach ($t2 as $k => $v) {
				$tmd = explode(',', $v);
				$res[$k]['time'] = date('ymd', strtotime(substr($tmd[0], 5, -1)));
				$res[$k]['open'] = substr($tmd[1], 6, -2);
				$res[$k]['high'] = substr($tmd[2], 6, -2);
				$res[$k]['low'] = substr($tmd[3], 5, -2);
				$res[$k]['close'] = substr($tmd[4], 7, -2);
				$res[$k]['volume'] = substr($tmd[5], 8, -3);
			}

			return $res;
		}
	}

	if (!function_exists('sina_week_k')) {
		function sina_week_k($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol=' . $d . '&scale=1200&ma=no&datalen=100');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$output = substr($output, 2);
			$output = substr($output, 0, -3);
			$t2 = explode('},{', $output);
			$res = array();

			foreach ($t2 as $k => $v) {
				$tmd = explode(',', $v);
				$res[$k]['time'] = date('ymd', strtotime(substr($tmd[0], 5, -1)));
				$res[$k]['open'] = substr($tmd[1], 6, -2);
				$res[$k]['high'] = substr($tmd[2], 6, -2);
				$res[$k]['low'] = substr($tmd[3], 5, -2);
				$res[$k]['close'] = substr($tmd[4], 7, -2);
				$res[$k]['volume'] = substr($tmd[5], 8, -3);
			}

			return $res;
		}
	}

	if (!function_exists('sina_month_k')) {
		function sina_month_k($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData?symbol=' . $d . '&scale=7200&ma=no&datalen=100');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$output = substr($output, 2);
			$output = substr($output, 0, -3);
			$t2 = explode('},{', $output);
			$res = array();

			foreach ($t2 as $k => $v) {
				$tmd = explode(',', $v);
				$res[$k]['time'] = date('ymd', strtotime(substr($tmd[0], 5, -1)));
				$res[$k]['open'] = substr($tmd[1], 6, -2);
				$res[$k]['high'] = substr($tmd[2], 6, -2);
				$res[$k]['low'] = substr($tmd[3], 5, -2);
				$res[$k]['close'] = substr($tmd[4], 7, -2);
				$res[$k]['volume'] = substr($tmd[5], 8, -3);
			}

			return $res;
		}
	}

	if (!function_exists('qq_day_k')) {
		function qq_day_k($code)
		{
			$d = fenxi($code);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://data.gtimg.cn/flashdata/hushen/latest/daily/' . $d . '.js?maxage=43201&visitDstTime=1');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = explode('\\n\\', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$count = count($t2);
			unset($t2[0]);
			unset($t2[1]);
			unset($t2[$count - 1]);

			foreach ($t2 as $k => $v) {
				$t2[$k - 2] = explode(' ', trim($v));
			}

			unset($t2[$count - 2]);
			unset($t2[$count - 3]);
			ksort($t2);
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k]['time'] = $v[0];
				$res[$k]['open'] = $v[1];
				$res[$k]['close'] = $v[2];
				$res[$k]['high'] = $v[3];
				$res[$k]['low'] = $v[4];
				$res[$k]['volume'] = $v[5];
			}

			return $res;
		}
	}

	if (!function_exists('qq_market_b')) {
		function qq_market_b($code)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/q=' . $code);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$t2 = explode('~', mb_convert_encoding($output, 'utf-8', 'gbk'));
			unset($t2[0]);
			return $t2;
		}
	}

	if (!function_exists('qq_to_api')) {
		function qq_to_api($res)
		{
			if (substr($res[0], 0, 2) === 'sh') {
				$res[40] = '1';
			}
			else {
				$res[40] = '0';
			}

			if (mktime(15, 0) < time()) {
				$time = date('H:i:s', mktime(15, 0, 0));
			}
			else {
				$time = date('H:i:s', time());
			}

			return array('code' => $res[2], 'name' => $res[1], 'yesterday_price' => $res[4], 'open_price' => $res[5], 'national_debt' => '0.00', 'current_price' => $res[3], 'buy_one_price' => $res[9], 'buy_two_price' => $res[11], 'buy_three_price' => $res[13], 'buy_one_amount' => $res[10], 'buy_two_amount' => $res[12], 'buy_three_amount' => $res[14], 'sell_one_price' => $res[19], 'sell_two_price' => $res[21], 'sell_three_price' => $res[23], 'sell_one_amount' => $res[20], 'sell_two_amount' => $res[22], 'sell_three_amount' => $res[24], 'buy_four_price' => $res[15], 'buy_five_price' => $res[17], 'buy_four_amount' => $res[16], 'buy_five_amount' => $res[18], 'sell_four_price' => $res[25], 'sell_five_price' => $res[27], 'sell_four_amount' => $res[26], 'sell_five_amount' => $res[28], 'exchange_code' => $res[40], 'mini_trans' => 100, 'buy_chang_price' => '0.01', 'sell_chang_price' => '0.01', 'type' => 1, 'currency' => 0, 'debt_sign' => 255, 'info' => '', 'highest' => $res[41], 'lowest' => $res[42], 'volume' => $res[36], 'turnover' => $res[37], 'time' => $time, 'turnover_rate' => $res[38], 'pe_ratio' => $res[39], 'circulation_market_value' => $res[44], 'total_market_value' => $res[45], 'pb_ratio' => $res[46]);
		}
	}

	if (!function_exists('sina_to_api')) {
		function sina_to_api($res)
		{
			if (substr($res[0], 0, 2) === 'sh') {
				$res[53] = '1';
			}
			else {
				$res[53] = '0';
			}

			return array('code' => substr($res[0], 2), 'name' => $res[32], 'yesterday_price' => $res[2], 'open_price' => $res[1], 'national_debt' => '0.00', 'current_price' => $res[3], 'buy_one_price' => $res[11], 'buy_two_price' => $res[13], 'buy_three_price' => $res[15], 'buy_one_amount' => substr($res[10], 0, -2), 'buy_two_amount' => substr($res[12], 0, -2), 'buy_three_amount' => substr($res[14], 0, -2), 'sell_one_price' => $res[21], 'sell_two_price' => $res[23], 'sell_three_price' => $res[25], 'sell_one_amount' => substr($res[20], 0, -2), 'sell_two_amount' => substr($res[22], 0, -2), 'sell_three_amount' => substr($res[24], 0, -2), 'buy_four_price' => $res[17], 'buy_five_price' => $res[19], 'buy_four_amount' => substr($res[16], 0, -2), 'buy_five_amount' => substr($res[18], 0, -2), 'sell_four_price' => $res[27], 'sell_five_price' => $res[29], 'sell_four_amount' => substr($res[26], 0, -2), 'sell_five_amount' => substr($res[28], 0, -2), 'exchange_code' => $res[53], 'mini_trans' => 100, 'buy_chang_price' => '0.01', 'sell_chang_price' => '0.01', 'type' => 1, 'currency' => 0, 'debt_sign' => 255, 'info' => '', 'highest' => $res[4], 'lowest' => $res[5], 'volume' => (string) ($res[8] / 100), 'turnover' => (string) ($res[9] / 10000), 'time' => $res[31], 'turnover_rate' => (string) round($res[8] / $res[41] / 100, 2), 'pe_ratio' => (string) round($res[3] / $res[34], 2), 'circulation_market_value' => (string) round($res[3] * $res[41] / 10000, 2), 'total_market_value' => (string) round($res[3] * $res[39] / 10000, 2), 'pb_ratio' => (string) round($res[3] / $res[37], 2));
		}
	}

	if (!function_exists('sina_to_api_b')) {
		function sina_to_api_b($res)
		{
			if (substr($res[0], 0, 2) === 'sh') {
				$res[53] = '1';
			}
			else {
				$res[53] = '0';
			}

			return array('code' => substr($res[0], 2), 'name' => $res[32], 'yesterday_price' => $res[2], 'open_price' => $res[1], 'national_debt' => '0.00', 'current_price' => $res[3], 'buy_one_price' => $res[11], 'buy_two_price' => $res[13], 'buy_three_price' => $res[15], 'buy_one_amount' => substr($res[10], 0, -2), 'buy_two_amount' => substr($res[12], 0, -2), 'buy_three_amount' => substr($res[14], 0, -2), 'sell_one_price' => $res[21], 'sell_two_price' => $res[23], 'sell_three_price' => $res[25], 'sell_one_amount' => substr($res[20], 0, -2), 'sell_two_amount' => substr($res[22], 0, -2), 'sell_three_amount' => substr($res[24], 0, -2), 'buy_four_price' => $res[17], 'buy_five_price' => $res[19], 'buy_four_amount' => substr($res[16], 0, -2), 'buy_five_amount' => substr($res[18], 0, -2), 'sell_four_price' => $res[27], 'sell_five_price' => $res[29], 'sell_four_amount' => substr($res[26], 0, -2), 'sell_five_amount' => substr($res[28], 0, -2), 'exchange_code' => $res[53], 'mini_trans' => 100, 'buy_chang_price' => '0.01', 'sell_chang_price' => '0.01', 'type' => 1, 'currency' => 0, 'debt_sign' => 255, 'info' => '', 'highest' => $res[4], 'lowest' => $res[5], 'volume' => (string) ($res[8] / 100), 'turnover' => (string) ($res[9] / 10000), 'time' => $res[31]);
		}
	}

	if (!function_exists('api_filter')) {
		function api_filter($res)
		{
			return array('code' => $res[0], 'name' => $res[1], 'yesterday_price' => $res[2], 'open_price' => $res[3], 'national_debt' => $res[4], 'current_price' => $res[5], 'buy_one_price' => $res[6], 'buy_two_price' => $res[7], 'buy_three_price' => $res[8], 'buy_one_amount' => $res[9], 'buy_two_amount' => $res[10], 'buy_three_amount' => $res[11], 'sell_one_price' => $res[12], 'sell_two_price' => $res[13], 'sell_three_price' => $res[14], 'sell_one_amount' => $res[15], 'sell_two_amount' => $res[16], 'sell_three_amount' => $res[17], 'buy_four_price' => $res[18], 'buy_five_price' => $res[19], 'buy_four_amount' => $res[20], 'buy_five_amount' => $res[21], 'sell_four_price' => $res[22], 'sell_five_price' => $res[23], 'sell_four_amount' => $res[24], 'sell_five_amount' => $res[25], 'exchange_code' => $res[26], 'mini_trans' => $res[27], 'buy_chang_price' => $res[28], 'sell_chang_price' => $res[29], 'type' => $res[30], 'currency' => $res[31], 'debt_sign' => $res[32], 'info' => $res[33]);
		}
	}

	if (!function_exists('time_check')) {
		function time_check()
		{
			$tim = time() - strtotime(date('y-m-d 00:00:00', time()));
			if (54000 <= $tim || $tim <= 34200) {
				return false;
			}

			if (41400 <= $tim && $tim <= 46800) {
				return false;
			}

			return true;
		}
	}

	if (!function_exists('time_check')) {
		function microtime_float()
		{
			list($usec, $sec) = explode(' ', microtime());
			return (double) $usec + (double) $sec;
		}
	}

	if (!function_exists('http')) {
		function http()
		{
			return empty($_SERVER['HTTP_X_CLIENT_PROTO']) ? 'http://' : $_SERVER['HTTP_X_CLIENT_PROTO'] . '://';
		}
	}

	if (!function_exists('sinahy')) {
		function sinahy()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://vip.stock.finance.sina.com.cn/q/view/newSinaHy.php');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '{"';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -2);
			$t2 = explode('","', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();

			foreach ($t2 as $k => $v) {
/* [31m * TODO SEPARATE[0m */
				$res[$k] = explode(',', substr(explode(':', $v)[1], 1));
			}

			return $res;
		}
	}

	if (!function_exists('qqtop10')) {
		function qqtop10()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=bkqt_top10');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '="';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -1);
			$t2 = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k] = explode('~', $v);
			}

			return $res;
		}
	}

	if (!function_exists('qqbot10')) {
		function qqbot10()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=bkqt_bot10');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '="';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -1);
			$t2 = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k] = explode('~', $v);
			}

			return $res;
		}
	}

	if (!function_exists('sinabot10')) {
		function sinabot10()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://hq.sinajs.cn/rn=1528781824076&format=text&list=sinaindustry_down');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '=[';
			$pos = strpos($output, $findme) + 3;
			$output = substr($output, $pos, -3);
			$t2 = explode('\',\'', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k] = explode(',', $v);
			}

			return $res;
		}
	}

	if (!function_exists('sinatop10')) {
		function sinatop10()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://hq.sinajs.cn/rn=1528781848576&format=text&list=sinaindustry_up');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '=[';
			$pos = strpos($output, $findme) + 3;
			$output = substr($output, $pos, -3);
			$t2 = explode('\',\'', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();

			foreach ($t2 as $k => $v) {
				$res[$k] = explode(',', $v);
			}

			return $res;
		}
	}

	if (!function_exists('z_top10')) {
		function z_top10()
		{
			$ret = 2;
			$data = array();

			switch ($ret) {
			case 1:
				$data = qqtop10();

				foreach ($data as $k => $v) {
					unset($data[$k][8]);
				}

				$res = $data;
				break;

			case 2:
				$data = sinatop10();

				foreach ($data as $k => $v) {
					$res[$k][0] = $v[0];
					$res[$k][1] = $v[1];
					$res[$k][2] = $v[2];
					$res[$k][3] = $v[3];
					$res[$k][4] = $v[5];
					$res[$k][5] = $v[6];
					$res[$k][6] = $v[7];
					$res[$k][7] = $v[8];
				}

				break;

			case 3:
				$data = qqtop10();

				foreach ($data as $k => $v) {
					unset($data[$k][8]);
				}

				$res = $data;
				break;

			default:
				$data = qqtop10();

				foreach ($data as $k => $v) {
					unset($data[$k][8]);
				}

				$res = $data;
				break;
			}

			return $res;
		}
	}

	if (!function_exists('z_bot10')) {
		function z_bot10()
		{
			$ret = 2;
			$data = array();

			switch ($ret) {
			case 1:
				$data = qqbot10();

				foreach ($data as $k => $v) {
					unset($data[$k][8]);
				}

				$res = $data;
				break;

			case 2:
				$data = sinabot10();

				foreach ($data as $k => $v) {
					$res[$k][0] = $v[0];
					$res[$k][1] = $v[1];
					$res[$k][2] = $v[2];
					$res[$k][3] = $v[3];
					$res[$k][4] = $v[5];
					$res[$k][5] = $v[6];
					$res[$k][6] = $v[7];
					$res[$k][7] = $v[8];
				}

				break;

			case 3:
				$data = qqbot10();

				foreach ($data as $k => $v) {
					unset($data[$k][8]);
				}

				$res = $data;
				break;

			default:
				$data = qqbot10();

				foreach ($data as $k => $v) {
					unset($data[$k][8]);
				}

				$res = $data;
				break;
			}

			return $res;
		}
	}

	if (!function_exists('sina_stock_top10')) {
		function sina_stock_top10()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=1&num=10&sort=changepercent&asc=0&node=hs_a&symbol=&_s_r_a=setlen');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '[';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -2);
			$t2 = explode('},{', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();
			$ret = array();

			foreach ($t2 as $k => $v) {
				$findme = '",buy:"';
				$pos = strpos($v, $findme);
				$v = substr($v, 0, $pos);
				$res[$k] = explode('",', $v);

				foreach ($res[$k] as $key => $value) {
					$res[$k][$key] = explode(':"', $value);
					$ret[$k][$res[$k][$key][0]] = $res[$k][$key][1];
				}

				unset($ret[$k]['pricechange']);
				unset($ret[$k]['symbol']);
			}

			return $ret;
		}
	}

	if (!function_exists('qq_stock_top')) {
		function qq_stock_top($page = 1, $num = 20, $asc = 0)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://stock.gtimg.cn/data/view/rank.php?t=ranka/chr&p=' . $page . '&o=' . $asc . '&l=' . $num . '&v=list_data');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$num = strpos($output, 'data:\'') + 6;
			$code = substr($output, $num, -3);
			$data = z_market_bat($code);
			$res = array();

			foreach ($data as $k => $v) {
				$res[$k]['code'] = $v['code'];
				$res[$k]['name'] = $v['name'];
				$res[$k]['trade'] = $v['current_price'];
				$res[$k]['changepercent'] = (string) round(($v['current_price'] - $v['yesterday_price']) / $v['yesterday_price'] * 100, 1);
			}

			return $res;
		}
	}

	if (!function_exists('sina_stock_top')) {
		function sina_stock_top($page = 1, $num = 20, $asc = 0)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=' . $page . '&num=' . $num . '&sort=changepercent&asc=' . $asc . '&node=hs_a&symbol=&_s_r_a=setlen');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '[';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -2);
			$t2 = explode('},{', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();
			$ret = array();

			foreach ($t2 as $k => $v) {
				$findme = '",buy:"';
				$pos = strpos($v, $findme);
				$v = substr($v, 0, $pos);
				$res[$k] = explode('",', $v);

				foreach ($res[$k] as $key => $value) {
					$res[$k][$key] = explode(':"', $value);
					$ret[$k][$res[$k][$key][0]] = $res[$k][$key][1];
				}

				unset($ret[$k]['pricechange']);
				unset($ret[$k]['symbol']);
			}

			return $ret;
		}
	}

	if (!function_exists('sina_stock_bot10')) {
		function sina_stock_bot10()
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://vip.stock.finance.sina.com.cn/quotes_service/api/json_v2.php/Market_Center.getHQNodeData?page=1&num=10&sort=changepercent&asc=1&node=hs_a&symbol=&_s_r_a=setlen');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '[';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -2);
			$t2 = explode('},{', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$res = array();
			$ret = array();

			foreach ($t2 as $k => $v) {
				$findme = '",buy:"';
				$pos = strpos($v, $findme);
				$v = substr($v, 0, $pos);
				$res[$k] = explode('",', $v);

				foreach ($res[$k] as $key => $value) {
					$res[$k][$key] = explode(':"', $value);
					$ret[$k][$res[$k][$key][0]] = $res[$k][$key][1];
				}

				unset($ret[$k]['pricechange']);
				unset($ret[$k]['symbol']);
			}

			return $ret;
		}
	}

	if (!function_exists('qq_stock_top10')) {
		function qq_stock_top10()
		{
			$time = time() . rand(100, 999);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=azdftop10&_=' . $time);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '="';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -4);
			$t2 = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$ret = array();

			foreach ($t2 as $k => $v) {
				$t2[$k] = explode('~', $v);
				$ret[$k]['code'] = $t2[$k][3];
				$ret[$k]['name'] = $t2[$k][0];
				$ret[$k]['trade'] = $t2[$k][1];
				$ret[$k]['changepercent'] = $t2[$k][2];
			}

			return $ret;
		}
	}

	if (!function_exists('qq_stock_bot10')) {
		function qq_stock_bot10()
		{
			$time = time() . rand(100, 999);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://qt.gtimg.cn/?q=azdfend10&_=' . $time);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$findme = '="';
			$pos = strpos($output, $findme) + 2;
			$output = substr($output, $pos, -4);
			$t2 = explode('^', mb_convert_encoding($output, 'utf-8', 'gbk'));
			$ret = array();

			foreach ($t2 as $k => $v) {
				$t2[$k] = explode('~', $v);
				$ret[$k]['code'] = $t2[$k][3];
				$ret[$k]['name'] = $t2[$k][0];
				$ret[$k]['trade'] = $t2[$k][1];
				$ret[$k]['changepercent'] = $t2[$k][2];
			}

			return $ret;
		}
	}

	if (!function_exists('z_stock_top10')) {
		function z_stock_top10()
		{
			$ret = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($ret) {
			case 1:
				$data = qq_stock_top10();
				break;

			case 2:
				$data = sina_stock_top10();
				break;

			case 3:
				$data = qq_stock_top10();
				break;

			default:
				$data = qq_stock_top10();
				break;
			}

			return $data;
		}
	}

	if (!function_exists('z_stock_bot10')) {
		function z_stock_bot10()
		{
			$ret = \think\Db::name('admin_config')->where(array('name' => 'market_data_in'))->value('value');

			switch ($ret) {
			case 1:
				$data = qq_stock_bot10();
				break;

			case 2:
				$data = sina_stock_bot10();
				break;

			case 3:
				$data = qq_stock_bot10();
				break;

			default:
				$data = qq_stock_bot10();
				break;
			}

			return $data;
		}
	}
}

?>
