<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
namespace app\stock\model;
use app\money\model\Money as MoneyModel;
use app\money\model\Record as RecordModel;
use app\member\model\Member as MemberModel;
use app\member\model\MemberMessage as MemberMessageModel;
use app\market\model\SubAccountMoney as submoney;
use think\model;
use think\Db;
class Drawprofit extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_DRAWPROFIT__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    //返回提盈申请列表
    public static function getDrawprofit($map,$order)
    {
        $where['a.status'] = intval(0);
        $data_list = self::view('stock_drawprofit a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_money ssm', 'avail,available_amount', 'b.stock_subaccount_id=ssm.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->avail = money_convert($item->avail);
                $item->money = money_convert($item->money);
                $item->available_amount = money_convert($item->available_amount);
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->deposit_money = money_convert($item->deposit_money);
                $item->borrow_duration =$item->borrow_duration.$unit;
            });
        return $data_list;
    }
    //返回提盈记录列表
    public static function getDrawprofitList($map,$order)
    {
        $data_list = self::view('stock_drawprofit a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where('a.status','<>',0)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->money = money_convert($item->money);
                $item->deposit_money = money_convert($item->deposit_money);
            });
        return $data_list;
    }
    //根据id返回待审核的
    public static function getDrawprofitById($id){
        $where['a.status'] = intval(0);
        $data = self::view('stock_drawprofit a', true)
            ->view("stock_borrow b", 'stock_subaccount_id,multiple,init_money,order_id,borrow_money,deposit_money,borrow_interest as y_interest,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_money ssm', 'avail,available_amount,return_money', 'b.stock_subaccount_id=ssm.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where(['a.id'=>$id])
            ->find();
        return $data;
    }
    //存储提盈审核结果
    public function saveDrawprofit($id,$status,$info){
        $minfo_m =  Db::name('member')->where(['id'=>$info['uid']])->where(['status'=>1])->find();
        Db::startTrans();
        $minfo=Db::name("money")->lock(true)->where(['mid'=>$info['uid']])->find();
        if ($status == 1) {//审核通过
                $submoney_info=SubaccountMoney::getMoneyByID($info['stock_subaccount_id']);
                $sub_data=[];
                $sub_data['stock_drawprofit'] =$submoney_info['stock_drawprofit']*100+$info['money'];
                if((string)($submoney_info['available_amount']*100) < (string)$info['money']){
                    return ['status'=>'0', 'msg'=>'可提盈金额不足，审核失败'];
                }
                if($submoney_info['avail']*100 < $info['money']){
                    return ['status'=>'0', 'msg'=>'可用金额不足，审核失败'];
                }
                $submoney=new submoney();
                $sub_res=$submoney->up_moneylog($info['stock_subaccount_id'],$info['money'],12);
                $type = 85;
                $mmoney['account'] = $minfo['account'] + $info['money'];
                $money_res = MoneyModel::money_up($info['uid'], $mmoney);
                $record = new RecordModel();
                $msg = '申请提取盈利审核通过，添加金额' . $info['money']/100 . '元';
                $record_res = $record->saveData($info['uid'], $affect = $info['money'], $mmoney['account'], $type, $msg);

            } else {//审核未通过
                $msg = '申请提取盈利审核未通过';
                $status=2;
                $sub_res=true;
                $money_res=true;
                $record_res=true;
            }
            $data['status'] = $status;
            $data['verify_time'] = time();
            $drawprofit_res=self::where("id={$id}")->update($data);
            if($drawprofit_res&&$sub_res&&$record_res&&$money_res){
                if ($minfo_m['mobile']) {
						/*$params  = $minfo_m['mobile'];
						send_sms_member($msg,$params,1000);*/
                    //send_sms($minfo_m['mobile'],'', $msg);
                    $contentarr  = getconfigSms_status(['name'=>'stock_drawprofit_saveDrawprofit']);
                    $content = str_replace(array("#var#","#order_id#"),array($minfo_m['mobile'],$info['order_id']), $contentarr['value']);
                    if($contentarr['status']==1){
                    $res = sendsms_mandao($minfo_m['mobile'],$content,'user');
                    }
                }
                $MemberMessageModel = new MemberMessageModel();
                $MemberMessageModel->addInnerMsg($info['uid'],'提取盈利审核通知',$msg);//站内信
                Db::commit();
                return ['status'=>'1', 'msg'=>'审核成功'];
            }else{
                Db::rollback();
                return ['status'=>'0', 'msg'=>'审核失败'];
            }
    }


}