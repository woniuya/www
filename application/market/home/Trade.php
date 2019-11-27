<?php
//dezend by http://www.yunlu99.com/
namespace app\market\home;

class Trade extends Common
{
	public function index()
	{
		return $this->view->fetch('index/index');
	}

	protected function _initialize()
	{
		parent::_initialize();

		if (!MID) {
		}
	}

	public function buy()
	{
		if (!yan_time()) {
			return json(array('status' => 0, 'message' => '非交易时间'));
		}

		$req = request();
		$sub_id = $req::instance()->param('id');
		$code = $req::instance()->param('code');
		$count = $req::instance()->param('count');
		$price = $req::instance()->param('price');
		$model = $req::instance()->param('model');
		$list_res = \think\Db::name('stock_list')->where(array('code' => $code))->find();

		if ($list_res['status'] == 0) {
			return json(array('status' => 0, 'message' => $code . '为禁买股票'));
		}

		$etype = \app\stock\model\Borrow::market_type($code);

		if ($etype == 5) {
			return json(array('status' => 0, 'message' => '您只能购买沪深A股股票'));
		}

		if ($count % 100 != 0) {
			return json(array('status' => 0, 'message' => '购买量错误,必须是100的整数倍'));
		}

		if ($count <= 0) {
			return json(array('status' => 0, 'message' => '购买量错误!'));
		}

		$stockinfo = z_market($code);

		if (empty($stockinfo)) {
			return json(array('status' => 0, 'message' => '系统错误请联系管理员处理'));
		}

		if (intval($stockinfo['yesterday_price']) == 0) {
			return json(array('status' => 0, 'message' => '请检查该股是否退市'));
		}

		if (intval($stockinfo['open_price']) == 0) {
			return json(array('status' => 0, 'message' => '该股今日还没开盘'));
		}

		if (0.080000000000000002 <= abs($stockinfo['yesterday_price'] - $stockinfo['current_price']) / $stockinfo['yesterday_price']) {
			return json(array('status' => 0, 'message' => '涨跌幅过大，禁止购买！'));
		}

		if ($model == 1 && 0.080000000000000002 <= abs($stockinfo['yesterday_price'] - $price) / $stockinfo['yesterday_price']) {
			return json(array('status' => 0, 'message' => '委托价格超过风控限制，禁止购买！'));
		}

		if (intval($stockinfo['sell_one_price']) <= 0) {
			return json(array('status' => 0, 'message' => '系统错误，请稍后再试！'));
		}

		$trade_money = 0;
		if (!empty($price) && $model == 1) {
			$trade_money = $count * $price;
		}
		else if ($model == 2) {
			$price = 0;
			$arr[1] = $stockinfo['sell_one_amount'] * 100;
			$arr[2] = $stockinfo['sell_two_amount'] * 100;
			$arr[3] = $stockinfo['sell_three_amount'] * 100;
			$arr[4] = $stockinfo['sell_four_amount'] * 100;
			$arr[5] = $stockinfo['sell_five_amount'] * 100;
			$p_arr[1] = $stockinfo['sell_one_price'];
			$p_arr[2] = $stockinfo['sell_two_price'];
			$p_arr[3] = $stockinfo['sell_three_price'];
			$p_arr[4] = $stockinfo['sell_four_price'];
			$p_arr[5] = $stockinfo['sell_five_price'];
			$tmd = 0;

			foreach ($arr as $key => $v) {
				$tmd = $tmd + $v;

				if ($count <= $tmd) {
					$sum_money = 0;
					$sum_count = $count;

					for ($i = 1; $i < $key; $i++) {
						$sum_money += $arr[$i] * $p_arr[$i];
						$sum_count -= $arr[$i];
					}

					$trade_money = $sum_money + $sum_count * $p_arr[$key];
					$price = round($trade_money / $count, 2);
					$trade_money = $count * $price;
					break;
				}

				if ($key == 5) {
					return json(array('status' => 0, 'message' => '卖量不足，无法成交'));
				}
			}
		}
		else {
			return json(array('status' => 0, 'message' => '参数错误'));
		}

		if ($trade_money <= 0) {
			return json(array('status' => 0, 'message' => '购买量或网络错误'));
		}

		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($sub_id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		$pos_sum = \think\Db::name('stock_position')->where(array('sub_id' => $sub_id))->where(array('gupiao_code' => $code))->where(array('buying' => 0))->sum('stock_count');
		if ($list_res['quota'] != 0 && $list_res['quota'] < $pos_sum * $stockinfo['current_price'] + $trade_money) {
			return json(array('status' => 0, 'message' => '这只股票在平台上的购买量超过了平台单支股票最大购买限额'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$moneymodel = new \app\market\model\SubAccountMoney();
		$moneyinfo = $moneymodel->get_account_money($sub_id);

		if ($moneyinfo['avail'] < $trade_money * 100) {
			return json(array('status' => 0, 'message' => '购买资金不足'));
		}

		$bs_res = \think\Db::name('stock_borrow')->where(array('stock_subaccount_id' => $sub_id))->find();

		if (empty($bs_res)) {
			return json(array('status' => 0, 'message' => '没有对应的配资'));
		}
		else {
			if ($bs_res['type'] == 4 && $bs_res['end_time'] <= time() + 28800) {
				return json(array('status' => 0, 'message' => '您的配资今日到期，今日购买将无法卖出'));
			}
		}

		$risk = new \app\market\model\StockSubAccountRisk();
		$risk_res = $risk->get_risk($sub_id);

		if ($risk_res['prohibit_open'] == 0) {
			return json(array('status' => 0, 'message' => '您被禁止开新仓，请联系管理员咨询原因。'));
		}

		if ($bs_res['end_time'] <= time()) {
			return json(array('status' => 0, 'message' => '配资已经到期，不允许买入。'));
		}

		$position = new \app\market\model\Position();
		$position_res = $position->get_position($sub_id);
		$sum = 0;

		if (1 <= count($position_res)) {
			$code_b = '';
			$counts = array();

			foreach ($position_res as $k => $v) {
				$code_b = $code_b . $v['gupiao_code'] . ',';
				$counts[$v['gupiao_code']] = $v['stock_count'];
			}

			$code_b = substr($code_b, 0, -1);
			$p_res = z_market_bat($code_b);

			foreach ($p_res as $key => $vv) {
				$sum += $counts[$vv['code']] * $vv['current_price'];
			}
		}

		$binfo = \think\Db::name('stock_borrow')->where(array('stock_subaccount_id' => $sub_id))->find();
		$type_arr = array(1, 2, 3, 5);

		if (in_array($binfo['type'], $type_arr)) {
			$p_rate = $moneyinfo['deposit_money'] * $risk_res['loss_warn'] / 100 + $moneyinfo['borrow_money'] - $sum * 100 - $moneyinfo['avail'] - $moneyinfo['freeze_amount'];

			if (0 <= $p_rate) {
				return json(array('status' => 0, 'message' => '您的亏损已达预警线，系统禁止开新仓。'));
			}
		}

		if ($risk_res['position'] / 100 < ($pos_sum * $stockinfo['current_price'] * 100 + $trade_money * 100) / ($sum * 100 + $moneyinfo['avail'] + $moneyinfo['stock_addmoney'])) {
			return json(array('status' => 0, 'message' => '您的总购买量超过了单股最大持仓比例。'));
		}

		$commission = round($trade_money * $moneyinfo['commission_scale'] / 10000, 2);

		if ($commission < $moneyinfo['min_commission'] / 100) {
			$commission = $moneyinfo['min_commission'] / 100;
		}

		if ($etype == 2) {
			$Transfer = round($trade_money / 50000, 2);

			if ($Transfer < config('transfer_fee')) {
				$Transfer = config('transfer_fee');
			}
		}
		else {
			$Transfer = 0;
		}

		$effect_money = $trade_money + $Transfer + $commission;
		$broker = $submodel->get_broker($res['account_id']);
		\think\Db::startTrans();

		if ($broker['broker'] === -1) {
			$type = 1;
		}
		else {
			$type = 3;
		}

		if ($model == 1) {
			$type = 3;
		}

		$ret = $moneymodel->up_moneylog($sub_id, $effect_money * 100, $type);

		if (!$ret) {
			\think\Db::rollback();
			return json(array('status' => 0, 'message' => '交易失败!'));
		}

		$lid = $broker['lid'];
		$user = $broker['user'];
		$soure = $broker['stockjobber'];
		$Trust_no = mt_rand(101010, 999999) . substr(time(), 1);
		$Trust = new \app\market\model\Trust();
		$Trust_res = $Trust->add_m_trust($stockinfo, $count, $price, $sub_id, $lid, $user, $soure, $Trust_no, $broker, $model);

		if (!$Trust_res) {
			\think\Db::rollback();
			return json(array('status' => 0, 'message' => '交易失败!!'));
		}

		$deal_stack = new \app\market\model\Deal_stock();
		$deal_res = $deal_stack->add_m_deal_stock($stockinfo, $count, $price, $sub_id, $lid, $user, $soure, $Trust_no, $model);

		if (!$deal_res) {
			\think\Db::rollback();
			return json(array('status' => 0, 'message' => '交易失败'));
		}

		$position = new \app\market\model\Position();
		$ck_price = round($effect_money / $count, 3);
		$pos_res = $position->add_m_position($stockinfo, $count, $sub_id, $lid, $user, $soure, $ck_price, $model, $Trust_no, $broker['broker']);

		if (!$pos_res) {
			\think\Db::rollback();
			return json(array('status' => 0, 'message' => '交易失败'));
		}

		$Delivery = new \app\market\model\Delivery();
		$avail = $moneyinfo['avail'] / 100 - $effect_money;
		$position_res_b = \think\Db::name('stock_position')->where(array('sub_id' => $sub_id))->where(array('gupiao_code' => $code))->where(array('buying' => 0))->find();
		if ($model == 2 && !empty($position_res_b)) {
			$amount = $position_res_b['canbuy_count'];
		}

		if ($model == 1 && !empty($position_res_b)) {
			$amount = $position_res_b['canbuy_count'] + $count;
		}

		if (empty($position_res_b)) {
			$amount = $count;
		}

		$pos_res = $Delivery->add_m_delivery_order($stockinfo, $count, $price, $sub_id, $lid, $user, $soure, $commission, $Transfer, $Trust_no, $avail, $amount, $model);

		if (!$pos_res) {
			\think\Db::rollback();
			return json(array('status' => 0, 'message' => '交易失败'));
		}

		if (get_spapi() != '1') {
			\think\Db::commit();
			return json(array('status' => 1, 'message' => '买入委托已提交'));
		}

		if ($broker['broker'] != -1) {
			$trade_id = $broker['id'];

			if (config('web_real_api') == 1) {
				$otype = 1;

				if ($model == 1) {
					$ptype = 1;
				}
				else {
					$ptype = 5;
				}

				$data = gs('commitOrder', array($trade_id, $code, $count, $etype, $otype, $ptype, $price));

				if (isset($data['error'])) {
					\think\Db::rollback();
					return json(array('status' => 0, 'message' => $data['error']));
				}

				if (isset($data['ErrInfo'])) {
					\think\Db::rollback();
					return json(array('status' => 0, 'message' => $data['ErrInfo']));
				}

				\think\Db::commit();
			}

			if (config('web_real_api') == 2) {
				$otype = 0;

				if ($model == 1) {
					$ptype = 0;
				}
				else {
					$ptype = 4;
				}

				$data = \app\market\model\Grid::grid_order($otype, $ptype, $code, $price, $count, $trade_id);

				if (!preg_match('/^\\d*$/', $data)) {
					\think\Db::rollback();
					return json(array('status' => 0, 'message' => $data));
				}
				else {
					\think\Db::commit();
				}
			}
		}
		else {
			\think\Db::commit();
		}

		return json(array('status' => 1, 'message' => '买入委托已提交'));
	}

	public function sell()
	{
		if (!yan_time()) {
			return json(array('status' => 0, 'message' => '非交易时间'));
		}

		$req = request();
		$id = $req::instance()->param('id');
		$code = $req::instance()->param('code');
		$count = $req::instance()->param('count');
		$price = $req::instance()->param('price');
		$model = $req::instance()->param('model');
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);
		if (isset($res['uid']) && $res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$borrow = new \app\stock\model\Borrow();
		$res = $borrow->savesell($id, $code, $count, 0, $price, $model);
		return json($res);
	}

	public function deal_stock()
	{
		$req = request();
		$id = $req::instance()->param('id');
		$beginday = $req::instance()->param('beginday');
		$endday = $req::instance()->param('endday');
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$deal_stack = new \app\market\model\Deal_stock();
		$res = $deal_stack->get_deal_stock($id, $beginday, $endday);
		return json(array('data' => $res, 'status' => 1, 'message' => '操作成功'));
	}

	public function canbuy_count()
	{
		$req = request();
		$sub_id = $req::instance()->param('id');
		$code = $req::instance()->param('code');
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($sub_id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$position = new \app\market\model\Position();
		$data = $position->get_canbuy_count($sub_id, $code);
		return json(array('data' => $data, 'status' => 1, 'message' => '操作成功'));
	}

	public function position()
	{
		$req = request();
		$id = $req::instance()->param('id');
		$is_app = $req::instance()->param('is_app');
		$page = $req::instance()->param('page');
		$page = empty($page) ? 1 : $page;
		$pnum = $req::instance()->param('pnum');
		$pnum = empty($pnum) ? 15 : $pnum;
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$sub_id = $id;
		$total = \think\Db::name('stock_position')->where(array('sub_id' => $sub_id))->where(array('buying' => 0))->count();
		$total_page = ceil($total / $pnum);
		$start = $page * $pnum - $pnum;

		if (intval($is_app) == 1) {
			$res = \think\Db::name('stock_position')->where(array('sub_id' => $sub_id))->where(array('buying' => 0))->order('id desc')->select();
		}
		else {
			$res = \think\Db::name('stock_position')->where(array('sub_id' => $sub_id))->where(array('buying' => 0))->limit($start, $pnum)->order('id desc')->select();
		}

		if (count($res) === 1) {
			foreach ($res as $k => $v) {
				$info = z_market($v['gupiao_code']);
				$res[$k]['now_price'] = $info['current_price'];
				$res[$k]['market_value'] = $info['current_price'] * $v['canbuy_count'];
				$res[$k]['ck_profit'] = round(($info['current_price'] - $v['buy_average_price']) * $v['stock_count'], 2);
				$res[$k]['profit_rate'] = round($res[$k]['ck_profit'] / ($v['buy_average_price'] * $v['stock_count']) * 100, 2);
				$res_d1 = \think\Db::name('stock_delivery_order')->where(array('sub_id' => $sub_id, 'gupiao_code' => $v['gupiao_code']))->where('deal_date', '>', mktime(0, 0))->where(array('status' => 1))->where(array('business_name' => '证券买入'))->sum('volume');
				$res[$k]['canbuy_count'] = $res[$k]['canbuy_count'] - $res_d1;
				$res[$k] = array_merge($res[$k], $info);
			}
		}
		else {
			if (2 <= count($res) && count($res) < 80) {
				$code = '';

				foreach ($res as $k => $v) {
					$code = $code . $v['gupiao_code'] . ',';
				}

				$code = substr($code, 0, -1);
				$info = z_market_bat($code);

				foreach ($res as $k => $v) {
					foreach ($info as $kk => $vv) {
						if ($res[$k]['gupiao_code'] === $vv['code']) {
							$res[$k]['now_price'] = $vv['current_price'];
							$res[$k]['market_value'] = $vv['current_price'] * $v['canbuy_count'];
							$res[$k]['ck_profit'] = round(($vv['current_price'] - $v['buy_average_price']) * $v['stock_count'], 2);
							$res[$k]['profit_rate'] = round($res[$k]['ck_profit'] / ($v['buy_average_price'] * $v['stock_count']) * 100, 2);
							$res_d1 = \think\Db::name('stock_delivery_order')->where(array('sub_id' => $sub_id, 'gupiao_code' => $vv['code']))->where('deal_date', '>', mktime(0, 0))->where(array('status' => 1))->where(array('business_name' => '证券买入'))->sum('volume');
							$res[$k]['canbuy_count'] = $res[$k]['canbuy_count'] - $res_d1;
							$res[$k] = array_merge($res[$k], $vv);
						}
					}
				}
			}
		}

		$data['page'] = $page;
		$data['pnum'] = $pnum;
		$data['all_page'] = $total_page;
		$data['total'] = $total;
		$data['list'] = $res;
		return json(array('data' => $data, 'status' => 1, 'message' => '操作成功'));
	}

	public function delivery()
	{
		$req = request();
		$id = $req::instance()->param('id');
		$beginday = $req::instance()->param('beginday');
		$endday = $req::instance()->param('endday');
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$Delivery = new \app\market\model\Delivery();
		$res = $Delivery->get_delivery_order($id, $beginday, $endday);
		return json(array('data' => $res, 'status' => 1, 'message' => '操作成功'));
	}


	//持仓
	public function account_info()
	{
		$req = request();
		$subaccount_id = $req::instance()->param('id');
		$submodel = new \app\market\model\SubAccountMoney();
		$res = $submodel->get_account_money_inf($subaccount_id);
		dump($res);die;

		if ($res['available_amount'] < 0) {
			$res['available_amount'] = 0;
		}

		$position = new \app\market\model\Position();
		$position_res = $position->get_position_b($subaccount_id);
		$sum = 0;

		if (!empty($position_res)) {
			foreach ($position_res as $k => $v) {
				$sum += $v['market_value'];
			}
		}

		$res['market_value'] = round($sum, 2);
		$res['total_money'] = round($sum + $res['avail'] / 100 + $res['freeze_amount'] / 100, 2);
		$res = \app\market\model\SubAccountMoney::ftoy($res);
		return json(array('data' => $res, 'status' => 1, 'message' => '操作成功'));
	}

	public function user_sub_id()
	{
		$req = request();
		$uid = $req::instance()->param('uid');
		$submodel = new \app\market\model\StockSubAccount();
		$account = $submodel->get_account_by_uid($uid);
		$time = time() - 604800;
		$borrow_info = \think\Db::name('stock_borrow')->field('stock_subaccount_id')->where(array('status' => 2, 'member_id' => $uid))->where('end_time', '<', $time)->select();

		if (!empty($borrow_info)) {
			$res = array();

			foreach ($borrow_info as $k => $v) {
				array_push($res, $v['stock_subaccount_id']);
			}

			foreach ($account as $key => $value) {
				if (in_array($value['id'], $res)) {
					unset($account[$key]);
				}
			}

			$account = array_values($account);
		}
		dump($account);die;
		return json(array('data' => $account, 'status' => 1, 'message' => '操作成功'));
	}

	public function subaccount_id()
	{
		$req = request();
		$sub_account = $req::instance()->param('sub_account');
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_name($sub_account);
		return json(array('data' => $res['id'], 'status' => 1, 'message' => '操作成功'));
	}

	public function subaccount_info()
	{
		$req = request();
		$id = $req::instance()->param('id');
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);
		return $res;
	}

	public function trust()
	{
		$req = request();
		$id = $req::instance()->param('id');
		$is_app = $req::instance()->param('is_app');
		$page = $req::instance()->param('page');
		$beginday = $req::instance()->param('beginday');
		$endday = $req::instance()->param('endday');
		$page = empty($page) ? 1 : $page;
		$pnum = $req::instance()->param('pnum');
		$pnum = empty($pnum) ? 15 : $pnum;
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$start = $page * $pnum - $pnum;

		if (empty($endday)) {
			$endday = time();
		}
		else {
			$endday = strtotime($endday);
		}

		if (empty($beginday)) {
			$beginday = mktime(0, 0, 0);
		}
		else {
			$beginday = strtotime($beginday);
		}

		$total = \think\Db::name('stock_trust')->where(array('sub_id' => $id))->where('trust_date', '>=', $beginday)->where('trust_date', '<=', $endday)->count();
		$total_page = ceil($total / $pnum);

		if (intval($is_app) == 1) {
			$res = \think\Db::name('stock_trust')->where(array('sub_id' => $id))->where('trust_date', '>=', $beginday)->where('trust_date', '<=', $endday)->order('id desc')->select();
		}
		else {
			$res = \think\Db::name('stock_trust')->where(array('sub_id' => $id))->where('trust_date', '>=', $beginday)->where('trust_date', '<=', $endday)->limit($start, $pnum)->order('id desc')->select();
		}

		foreach ($res as $k => $v) {
			$res[$k]['trust_date'] = date('Y-m-d', $v['trust_date']);
		}

		$data['page'] = $page;
		$data['pnum'] = $pnum;
		$data['all_page'] = $total_page;
		$data['total'] = $total;
		$data['list'] = $res;
		return json(array('data' => $data, 'status' => 1, 'message' => '操作成功'));
	}

	public function cancel_trust()
	{
		$req = request();
		$id = $req::instance()->param('id');
		$page = $req::instance()->param('page');
		$page = empty($page) ? 1 : $page;
		$pnum = $req::instance()->param('pnum');
		$pnum = empty($pnum) ? 15 : $pnum;
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$time = mktime(0, 0, 0);
		$total = \think\Db::name('stock_trust')->where(array('sub_id' => $id, 'trust_date' => $time, 'status' => '已委托'))->count();
		$start = $page * $pnum - $pnum;
		$total_page = ceil($total / $pnum);
		$res = \think\Db::name('stock_trust')->where(array('sub_id' => $id, 'trust_date' => $time, 'status' => '已委托'))->limit($start, $pnum)->select();
		$data['page'] = $page;
		$data['pnum'] = $pnum;
		$data['all_page'] = $total_page;
		$data['total'] = $total;
		$data['list'] = $res;
		return json(array('data' => $data, 'status' => 1, 'message' => '操作成功'));
	}

	public function cancel_order()
	{
		if (!yan_time()) {
			return json(array('status' => 0, 'message' => '非交易时间'));
		}

		$req = request();
		$id = $req::instance()->param('id');
		$trust_no = $req::instance()->param('trust_no');
		$submodel = new \app\market\model\StockSubAccount();
		$res = $submodel->get_account_by_id($id);

		if (empty($res['account_id'])) {
			return json(array('status' => 0, 'message' => '不存在的子账号'));
		}

		if ($res['uid'] != MID) {
			return json(array('status' => 0, 'message' => '登录超时请重新登录'));
		}

		$subaccount = new \app\market\model\SubAccountMoney();
		\think\Db::startTrans();
		$yes = false;
		$tempinfo = \think\Db::name('stock_temp')->where(array('trust_no' => $trust_no))->lock(true)->find();

		if (!empty($tempinfo)) {
			switch (substr($tempinfo['gupiao_code'], 0, 1)) {
			case '0':
				$d = 1;
				break;

			case '3':
				$d = 1;
				break;

			case '1':
				$d = 1;
				break;

			case '2':
				$d = 1;
				break;

			case '6':
				$d = 2;
				break;

			case '5':
				$d = 2;
				break;

			case '9':
				$d = 2;
				break;

			default:
				\think\Db::rollback();
				return json(array('status' => 0, 'message' => '股票代码不对，撤单失败'));
				break;
			}

			$type = $d;
		}
		else {
			\think\Db::rollback();
			return json(array('status' => 0, 'message' => '没找到对应委托，撤单失败'));
		}

		$affect_money = \think\Db::name('stock_delivery_order')->where(array('trust_no' => $trust_no))->value('liquidation_amount');

		if ($tempinfo['deal_no'] == null) {
			$trust['status'] = '已撤';
			$trust['cancel_order_flag'] = '1';
			$trust['cancel_order_count'] = $tempinfo['trust_count'];
			$trust_res = \think\Db::name('stock_trust')->where(array('trust_no' => $trust_no))->update($trust);
			$deal_res = \think\Db::name('stock_deal_stock')->where(array('trust_no' => $trust_no))->delete();
			$delivery_res = \think\Db::name('stock_delivery_order')->where(array('trust_no' => $trust_no))->delete();
			$ret = \think\Db::name('stock_temp')->where(array('trust_no' => $trust_no))->delete();
			$position = \think\Db::name('stock_position')->where(array('sub_id' => $id))->where(array('gupiao_code' => $tempinfo['gupiao_code']))->find();
			$subm_res = false;
			$position_in = false;

			if ($tempinfo['flag2'] == '买入委托') {
				$subm_res = $subaccount->up_moneylog($id, $affect_money * 100, 8);
				$position_in = true;
			}
			else if ($tempinfo['flag2'] == '卖出委托') {
				$position['canbuy_count'] = $position['canbuy_count'] + $tempinfo['trust_count'];
				$position_in = \think\Db::name('stock_position')->where(array('sub_id' => $id))->where(array('gupiao_code' => $tempinfo['gupiao_code']))->update($position);
				$subm_res = $subaccount->up_moneylog($id, $affect_money * 100, 9);
			}

			if ($trust_res && $deal_res && $delivery_res && $ret && $subm_res && $position_in) {
				$yes = true;
			}
			else {
				\think\Db::rollback();
				$num = $trust_res . $deal_res . $delivery_res . $ret . $subm_res . $position_in;
				return json(array('status' => 0, 'message' => '撤单失败', 'data' => $num));
			}
		}

		$broker = $submodel->get_broker($res['account_id']);
		$trade_id = $broker['id'];
		$res = \think\Db::name('admin_plugin')->where(array('name' => 'GreenSparrow'))->find();
		if ($res && get_spapi() != '1') {
			\think\Db::commit();
			return json(array('status' => 1, 'message' => '撤单成功'));
		}

		if (!empty($res) && $yes && $broker['broker'] != -1) {
			$day_re = array();

			if (config('web_real_api') == 1) {
				$day_re = gs('queryTradeData', array($broker['id'], 3));
			}

			if (config('web_real_api') == 2) {
				$day_re = \app\market\model\Grid::grid_category_trust($broker['id']);
			}

			unset($day_re[0]);
			$orderid = '';

			foreach ($day_re as $k => $v) {
				if ($v[4] == $tempinfo['gupiao_code'] && $v[9] == $tempinfo['trust_count'] && $v[7] == $tempinfo['flag2']) {
					$orderid = $v[10];
				}
			}

			$data = array();

			if (!empty($orderid)) {
				if (config('web_real_api') == 1) {
					$data = gs('cancelOrder', array($trade_id, $orderid, $type));
				}

				if (config('web_real_api') == 2) {
					if ($type = 1) {
						$type = 0;
					}
					else {
						$type = 1;
					}

					$data = \app\market\model\Grid::grid_cancel($type, $orderid, $trade_id);
				}
			}

			if ($data) {
				\think\Db::commit();
				return json(array('status' => 1, 'message' => '撤单成功'));
			}
		}
		else if ($yes) {
			if ($broker['broker'] != -1) {
				\think\Db::rollback();
				return json(array('status' => 0, 'message' => '请安装股票实盘交易插件'));
			}
			else {
				\think\Db::commit();
				return json(array('status' => 1, 'message' => '撤单成功'));
			}
		}

		\think\Db::rollback();
		return json(array('status' => 0, 'message' => '撤单失败'));
	}
}

?>
