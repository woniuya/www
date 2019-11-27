<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
namespace app\market\model;
use think\Model;
use think\Db;
class SubAccountMoney extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT_MONEY__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function get_account_money($subaccount_id){
        $result=Db::name('stock_subaccount_money')->field(true)->where(['stock_subaccount_id'=>$subaccount_id])->find();
        if(empty($result))return null;
        return $result;
    }
    public function get_account_money_inf($subaccount_id){
        $result=Db::view('stock_subaccount_money m')
            ->view('stock_subaccount_risk r','loss_warn,loss_close,renewal','r.stock_subaccount_id=m.stock_subaccount_id','left')
            ->view('stock_borrow b','type,init_money,borrow_money,deposit_money,end_time,id as borrow_id,status as b_status','b.stock_subaccount_id=m.stock_subaccount_id','left')
            ->where(['m.stock_subaccount_id'=>$subaccount_id])
            ->find();
        if(isset($result['b_status'])&&$result['b_status']===2){
            $result['available_amount']=0;
        }
        $result['init_money'] = $result['init_money'] /100;
        $result['loss_warn_money'] = ($result['borrow_money']+$result['loss_warn'] * $result['deposit_money'] /100)/100;
        $result['loss_close_money'] = ($result['borrow_money']+$result['loss_close'] * $result['deposit_money']/100)/100;
        $result['end_time']=date('Y-m-d H:i:s',$result['end_time']);
        if(empty($result))return null;
        return $result;
    }
    public  function up_moneylog($sub_id,$affect_money,$type,$return_money=0,$Balance=0,$code="600000"){
        $vm=Db::name('stock_subaccount_money')->lock(true)->field(true)->where(['stock_subaccount_id'=>$sub_id])->find();
        if(empty($vm)){return false;}
        //$nowTime = time();
        switch ($type){
            case 1://购买股票扣款
                if($vm['avail']<$affect_money){return false;}
                $mmoney['avail'] = $vm['avail']-$affect_money;
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],1,"(-)购买股票扣款,可用减少".$fee."元");
                break;
            case 2://卖出股票回款
                $mmoney['avail'] = $vm['avail']+$affect_money;
                $mmoney['return_money'] = $vm['return_money']+$return_money;
                $mmoney['return_rate']=$mmoney['return_money']/$vm['deposit_money']*100;
                $mmoney['available_amount'] = $vm['available_amount']+$return_money;//将盈亏加到可提盈字段
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],2,"(+)卖出股票回款,可用增加".$fee."元");
                break;
            case 3://实盘或限价购买股票扣款
                if($vm['avail']<$affect_money){return false;}
                $mmoney['avail'] = $vm['avail']-$affect_money;
                $mmoney['freeze_amount'] = $vm['freeze_amount']+$affect_money;
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],3,"(-)购买股票扣款,可用减少".$fee."元");
                break;
            case 4://实盘卖出股票回款
                $mmoney['freeze_amount'] = $vm['freeze_amount']+$affect_money;
                self::record($sub_id,$affect_money,$vm['avail'],4,"(=)卖出股票回款");
                break;
            case 5://实盘或限价卖出成功解除冻结
                $mmoney['avail'] = $vm['avail']+$affect_money+$Balance;
                $mmoney['freeze_amount'] = $vm['freeze_amount']-$affect_money;
                $mmoney['return_money'] = $vm['return_money']+$return_money;
                $mmoney['return_rate']=$mmoney['return_money']/($vm['deposit_money']/100);
                $mmoney['available_amount'] = $vm['available_amount']+$return_money;//将盈亏加到可提盈字段
                $fee=round(($Balance+$affect_money)/100,2);
                self::record($sub_id,$affect_money,$mmoney['avail'],5,"(+)卖出成功解除冻结,可用增加".$fee."元");
                break;
            case 6://实盘买入成功解除冻结
                $mmoney['avail'] = $vm['avail']+$Balance;
                $mmoney['freeze_amount'] = $vm['freeze_amount']-$affect_money;
                $fee=round($Balance/100,2);
                self::record($sub_id,$affect_money,$mmoney['avail'],6,"(+)买入成功解除冻结,可用增加".$fee."元");
                break;
            case 7://在子账户保证金中扣费用
                $mmoney['deposit_money'] = $vm['deposit_money']-$affect_money;
                self::record($sub_id,$affect_money,$vm['avail'],7,"(=)扣费用,可用不变");
                break;
            case 8://购买股票撤单
                $mmoney['avail'] = $vm['avail']+$affect_money;
                $mmoney['freeze_amount'] = $vm['freeze_amount']-$affect_money;
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],8,"(+)撤单解除冻结,可用增加".$fee."元");
                break;
            case 9://卖出股票撤单
                $mmoney['avail']=$vm['avail'];
                $mmoney['freeze_amount'] = $vm['freeze_amount']-$affect_money;
                self::record($sub_id,$affect_money,$mmoney['avail'],9,"(=)卖出撤单解除冻结,可用不变");
                break;
            case 10://追加保证金
                $mmoney['avail']=$vm['avail']+$affect_money;
                $mmoney['stock_addmoney'] = $vm['stock_addmoney']+$affect_money;
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],10,"(+)用户追加保证金,可用增加".$fee."元");
                break;
            case 11://扩大配资
                $applyMoney=$return_money;
                $mmoney['avail']=$vm['avail']+$affect_money+$applyMoney;
                $mmoney['deposit_money'] = $vm['deposit_money']+$affect_money;
                $mmoney['borrow_money'] = $vm['borrow_money']+$applyMoney;
                $mmoney['stock_addfinancing']=$vm['stock_addfinancing'] + $affect_money;
                $fee=($applyMoney+$affect_money)/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],11,"(+)用户扩大配资,可用增加".$fee."元");
                break;
            case 12://提取盈利
                $mmoney['avail']=$vm['avail']-$affect_money;
                $mmoney['available_amount'] = $vm['available_amount']-$affect_money;
                $mmoney['stock_drawprofit'] = $vm['stock_drawprofit']+$affect_money;
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],12,"(-)用户提取盈利,可用减少".$fee."元");
                break;
            case 13://股票分红
                $mmoney['avail']=$vm['avail']+$affect_money;
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],13,"(+)用户股票".$code."分红,可用增加".$fee."元");
                break;
            case 15://收取股票红利税
                $mmoney['avail']=$vm['avail']-$affect_money;
                $fee=$affect_money/100;
                self::record($sub_id,$affect_money,$mmoney['avail'],15,"(-)用户股票".$code."分红代扣利息税,可用减少".$fee."元");
                break;
            default:
                return false;
        }
        $ret=Db::name('stock_subaccount_money')->where(['stock_subaccount_id'=>$sub_id])->update($mmoney);
        return $ret;
    }
    public static function record($sub_id,$affect_money,$account,$type,$info){
        $record['sub_id'] = $sub_id;
        $record['affect'] = $affect_money;
        $record['account'] = $account;
        $record['type'] = $type;
        $record['info'] = $info;
        $record['create_time'] = time();
        $record['create_ip'] = get_client_ip(1);
        $res=Db::name('stock_submoney_record')->insert($record);
        return $res;
    }
    //子账户资金单位由分转为元
    public static function ftoy($res){
        if(!empty($res)) {
            $res["min_commission"] = money_convert($res["min_commission"]);
            $res["avail"] = money_convert($res["avail"]);
            $res["available_amount"] = money_convert($res["available_amount"]);
            $res["freeze_amount"] = money_convert($res["freeze_amount"]);
            $res["return_money"] = money_convert($res["return_money"]);
            //$res["return_rate"] = money_convert($res["return_rate"]);
            $res["deposit_money"] = money_convert($res["deposit_money"]);
            $res["borrow_money"] = money_convert($res["borrow_money"]);
            $res["stock_addfinancing"] = money_convert($res["stock_addfinancing"]);
            $res["stock_addmoney"] = money_convert($res["stock_addmoney"]);
            $res["stock_drawprofit"] = money_convert($res["stock_drawprofit"]);
        }
        return $res;
    }


}