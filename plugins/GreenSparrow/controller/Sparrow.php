<?php
namespace plugins\GreenSparrow\controller;

class Sparrow extends \app\common\controller\Common
{
	public function ret($socket, $query)
	{
		$socket->send($query);
		$data = json_decode($query, true);
		$res = json_decode($socket->receive(), true);

		if (!empty($res['error'])) {
			return $res;
		}

		if ($res['rid'] != $data['rid']) {
			return false;
		}

		if ($res['ret'] != 0) {
			return false;
		}

		return $res['data'];
	}

	public function ret_1($req, $para)
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$query = json_encode(array('req' => $req, 'rid' => build_rid_no(), 'para' => $para));
		$trade_id = json_encode(array('com' => 'trade_id', 'id' => 9999));
		$socket->send($trade_id);
		return $this->ret($socket, $query);
	}

	public function ret_3($req, $para)
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$query = json_encode(array('req' => $req, 'rid' => build_rid_no(), 'para' => $para));
		$socket->send('star');
		return $this->ret($socket, $query);
	}

	public function ret_api($trade_id, $req, $para)
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$query = json_encode(array('req' => $req, 'rid' => build_rid_no(), 'para' => $para));
		$trade_id = json_encode(array('com' => 'trade_id', 'id' => $trade_id));
		$socket->send($trade_id);
		return $this->ret($socket, $query);
	}

	public function ret_d($trade_id, $query)
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$socket->send('double');
		$trade_id = json_encode(array('com' => 'trade_id', 'id' => $trade_id));
		$socket->send($trade_id);
		$socket->send($query);
		$data = json_decode($query, true);
		$res = json_decode($socket->receive(), true);

		if (!empty($res['error'])) {
			return $res;
		}

		if ($res['rid'] != $data['rid']) {
			return false;
		}

		if (isset($res['data']['ErrInfo'])) {
			return $res['data'];
		}

		return $res['data'];
	}

	public function TradeArr()
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$socket->send('TradeArr');
		$res = json_decode($socket->receive(), true);
		return $res;
	}

	public function BrokerLogin($LID, $user, $password, $broker, $clienver, $id)
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$socket->send('BrokerLogin');
		$tmd = json_encode(array('operation' => 'BrokerLogin', 'LID' => $LID, 'user' => $user, 'password' => $password, 'broker' => $broker, 'clienver' => $clienver, 'id' => $id));
		$socket->send($tmd);
		$res = json_decode($socket->receive(), true);
		return $res;
	}

	public function BrokerOut($id)
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$socket->send('BrokerOut');
		$tmd = json_encode(array('operation' => 'BrokerOut', 'id' => $id));
		$socket->send($tmd);
		$res = json_decode($socket->receive(), true);
		return $res;
	}

	public function CheckBroker($LID, $user, $password, $broker, $clienver)
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$socket->send('CheckBroker');
		$tmd = json_encode(array('operation' => 'CheckBroker', 'LID' => $LID, 'user' => $user, 'password' => $password, 'broker' => $broker, 'clienver' => $clienver));
		$socket->send($tmd);
		$res = json_decode($socket->receive(), true);
		return $res;
	}

	public function CheckStatus()
	{
		$para = array('Server' => 1);
		return $this->ret_3('Trade_CheckStatus', $para);
	}

	public function ListCount($etype)
	{
		$para = array('EType' => $etype, 'Server' => 1);
		return $this->ret_3('Market_ListCount', $para);
	}

	public function CodeList($etype, $star)
	{
		$para = array('EType' => $etype, 'JsonType' => 0, 'Server' => 1, 'Start' => $star);
		return $this->ret_3('Market_CodeList', $para);
	}

	public function FinaInfo($code)
	{
		$para = array('Code' => $code, 'JsonType' => 1, 'Server' => 1);
		return $this->ret_3('Market_FinaInfo', $para);
	}

	public function XdxrInfo($code)
	{
		$para = array('Code' => $code, 'JsonType' => 1, 'Server' => 1);
		return $this->ret_3('Market_XdxrInfo', $para);
	}

	public function TimeData($code)
	{
		$para = array('Code' => $code, 'JsonType' => 1, 'Server' => 1);
		return $this->ret_3('Market_TimeData', $para);
	}

	public function TimeTrade($code)
	{
		$para = array('Code' => $code, 'JsonType' => 1, 'Server' => 1);
		return $this->ret_3('Market_TimeTrade', $para);
	}

	public function GetBar($code, $period, $index = 0)
	{
		$para = array('Code' => $code, 'Count' => 100, 'JsonType' => 1, 'Index' => $index, 'Period' => $period, 'Server' => 1, 'Start' => 0);
		return $this->ret_3('Market_GetBar', $para);
	}

	public function queryTradeData($trade_id, $type = 1)
	{
		$para = array('JsonType' => 1, 'QueryType' => $type);
		$data = $this->ret_api($trade_id, 'Trade_QueryData', $para);
		return $data;
	}

	public function GetCommitLog($trade_id, $status = 0, $beginday = NULL, $endday = NULL)
	{
		if ($beginday === null) {
			$beginday = date('Y-m-d', time() - 86400 * 30);
		}

		if ($endday === null) {
			$endday = date('Y-m-d', time());
		}

		$para = array('JsonType' => 0, 'TID' => 1, 'Status' => $status, 'BeginDay' => $beginday, 'EndDay' => $endday);
		$data = $this->ret_api($trade_id, 'Trade_GetCommitLog', $para);
		return $data;
	}

	public function market($code)
	{
		$para = array('Codes' => $code, 'Sync' => 1);
		return $this->ret_api(9999, 'Trade_QueryQuote', $para);
	}

	public function market_bat($codes)
	{
		$para = array('Codes' => $codes, 'Sync' => 2);
		$query = json_encode(array('req' => 'Trade_QueryQuote', 'rid' => build_rid_no(), 'para' => $para));
		$re = $this->ret_d(9999, $query);
		return $re;
	}

	public function history($trade_id, $type = 1, $beginday = '', $endday = '')
	{
		$para = array('JsonType' => '1', 'QueryType' => $type, 'BeginDay' => $beginday, 'EndDay' => $endday);
		$data = $this->ret_api($trade_id, 'Trade_QueryHisData', $para);

		if (is_null($data)) {
			return null;
		}

		return $data;
	}

	public function commitOrder($trade_id, $code, $count, $etype, $otype, $ptype, $price)
	{
		if (!$this->time_check()) {
			return 'Non trading time';
		}

		$para = array('Code' => $code, 'Count' => $count, 'EType' => $etype, 'OType' => $otype, 'PType' => $ptype, 'Price' => $price);
		$query = json_encode(array(
	'req'  => 'Trade_CommitOrder',
	'rid'  => build_rid_no(),
	'para' => array($para)
	));
		$re = $this->ret_d($trade_id, $query);
		return $re;
	}

	public function commitOrder_bat($trade_id, $data)
	{
		if (!$this->time_check()) {
			return 'Non trading time';
		}

		foreach ($data as $k => $v) {
			if (strlen($v['Code']) != 6) {
				return '股票代码长度错误';
			}

			if (substr($v['Code'], 0, 2) == '60') {
				$etype = 2;
			}
			else {
				$etype = 1;
			}

			$para[$k] = array('Code' => $v['Code'], 'Count' => $v['Count'], 'EType' => $etype, 'OType' => $v['OType'], 'PType' => $v['PType'], 'Price' => $v['Price']);
		}

		$query = json_encode(array('req' => 'Trade_CommitOrder', 'rid' => build_rid_no(), 'para' => $para));
		return $this->ret_d($trade_id, $query);
	}

	public function time_check()
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

	public function stagging($trade_id)
	{
		if (!$this->time_check()) {
			return 'Non trading time';
		}

		return $this->ret_api($trade_id, 'Trade_IPO', '');
	}

	public function oneKeyClearance($trade_id, $etype = 3, $ptype = 5)
	{
		if (!$this->time_check()) {
			return 'Non trading time';
		}

		$para = array('EType' => $etype, 'PType' => $ptype);
		return $this->ret_api($trade_id, 'Trade_SellAll', $para);
	}

	public function cancelOrder($trade_id, $orderid = '', $type = 1)
	{
		if (!$this->time_check()) {
			return 'Non trading time';
		}

		$para = array('OrderID' => $orderid, 'Type' => $type);
		return $this->ret_api($trade_id, 'Trade_CancelOrder', $para);
	}

	public function tradableCount($trade_id, $Code, $EType, $OType, $PType, $Price)
	{
		$para = array('Code' => $Code, 'EType' => $EType, 'OType' => $OType, 'PType' => $PType, 'Price' => $Price);
		return $this->ret_api($trade_id, 'Trade_TradableCount', $para);
	}

	public function repay($trade_id, $money)
	{
		if (!$this->time_check()) {
			return 'Non trading time';
		}

		$para = array('Money' => $money);
		return $this->ret_api($trade_id, 'Trade_Repay', $para);
	}

	public function getPlateList($trade_id, $ptype = 1)
	{
		$para = array('JsonType' => 1, 'PType' => $ptype);
		return $this->ret_api($trade_id, 'Tdx_GetPlateList', $para);
	}

	public function getPlateMember($pid = '206')
	{
		$para = array('JsonType' => 1, 'PID' => $pid);
		return $this->ret_1('Tdx_GetPlateMember', $para);
	}

	public function getStockPlate($code = '601288')
	{
		$para = array('JsonType' => 0, 'Code' => $code);
		return $this->ret_1('Tdx_GetStockPlate', $para);
	}

	public function searchByName($Name = '浦发', $SType = 2)
	{
		$para = array('JsonType' => 0, 'SType' => $SType, 'Name' => $Name);
		return $this->ret_1('Tdx_SearchByName', $para);
	}

	public function GetInfoByID($SType = 2, $Id = '2')
	{
		$para = array('JsonType' => 0, 'SType' => $SType, 'ID' => $Id);
		return $this->ret_1('Tdx_GetInfoByID', $para);
	}

	public function SearchByCode($Code = '0000', $SType = 2)
	{
		$para = array('JsonType' => 0, 'SType' => $SType, 'Code' => $Code);
		return $this->ret_1('Tdx_SearchByCode', $para);
	}

	public function LogOut()
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$socket->send('exit');
		json_decode($socket->receive(), true);
		return true;
	}

	public function ui()
	{
		$socket = new \plugins\GreenSparrow\model\Trade();
		$socket->send('ui');
		return true;
	}
}

?>
