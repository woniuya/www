<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 路人甲乙科技有限公司 [ http://www.luranjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
namespace app\stock\model;
use app\member\model\Member as MemberModel;
use app\money\model\Money as MoneyModel;
use app\money\model\Record as RecordModel;
use app\stock\model\Borrow as BorrowModel;
use app\member\model\MemberMessage as MemberMessageModel;
use think\model;
use think\Db;
class Renewal extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_RENEWAL__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    //返回续期申请列表
    public static function getRenewal($map,$order)
    {
        $where['a.status'] = intval(0);
        $where['a.type'] = intval(1);
        $data_list = self::view('stock_renewal a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration as y_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->borrow_fee = money_convert($item->borrow_fee);
                $item->deposit_money = money_convert($item->deposit_money);
                $item->loss_warn_money = $item->borrow_money+$item->loss_warn * $item->deposit_money /100;
                $item->loss_close_money = $item->borrow_money+$item->loss_close * $item->deposit_money /100;
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->borrow_duration .= $unit;
                $item->y_duration .= $unit;
            });
        return $data_list;
    }
    //返回续期记录列表
    public static function getRenewalList($map,$order)
    {
        $data_list = self::view('stock_renewal a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration as b_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where('a.status','<>',0)
            ->where('a.type','=',1)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->borrow_fee = money_convert($item->borrow_fee);
                $item->deposit_money = money_convert($item->deposit_money);
                $item->loss_warn_money = $item->borrow_money+$item->loss_warn * $item->deposit_money /100;
                $item->loss_close_money = $item->borrow_money+$item->loss_close * $item->deposit_money /100;
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->borrow_duration .= $unit;
                $item->b_duration .= $unit;
            });
        return $data_list;
    }
    public static function getRenewalList2($map,$order)
    {
        $data_list = self::view('stock_renewal a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration as b_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            //->where('a.status','<>',0)
            ->where('a.type','=',1)
            ->where($map)
            ->order($order)
            ->select();
        foreach ($data_list as $k=>$item){
            switch ($item['status']){
                case 0:
                    $data_list[$k]['status']="待审核";
                    break;
                case 1:
                    $data_list[$k]['status']="已通过";
                    break;
                case 2:
                    $data_list[$k]['status']="未通过";
                    break;
                default:
                    break;
            }
            $data_list[$k]['init_money'] = money_convert($item['init_money']);
            $data_list[$k]['borrow_money'] = money_convert($item['borrow_money']);
            $data_list[$k]['borrow_fee'] = money_convert($item['borrow_fee']);
            $data_list[$k]['deposit_money'] = money_convert($item['deposit_money']);
            $data_list[$k]['loss_warn_money'] = $item['borrow_money']+$item['loss_warn'] * $item['deposit_money'] /100;
            $data_list[$k]['loss_close_money'] = $item['borrow_money']+$item['loss_close'] * $item['deposit_money'] /100;
            if($item['type']===3){$unit='个月';}elseif($item['type']===2){$unit='周';}else{$unit='天';}
            $data_list[$k]['borrow_duration'] .= $unit;
            $data_list[$k]['b_duration'] .= $unit;
        }
        return $data_list;
    }
    //返回提前终止申请
    public static function getStop($map,$order)
    {
        $where['a.status'] = 0;
        $where['a.type'] = 2;
        $data_list = self::view('stock_renewal a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration as b_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->borrow_fee = money_convert($item->borrow_fee);
                $item->deposit_money = money_convert($item->deposit_money);
                $item->loss_warn_money = $item->borrow_money+$item->loss_warn * $item->deposit_money /100;
                $item->loss_close_money = $item->borrow_money+$item->loss_close * $item->deposit_money /100;
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->borrow_duration .= $unit;
                $item->b_duration .= $unit;
            });
        return $data_list;
    }
    /*
     *  返回对应borrow_id的提前终止申请
     */
    public static function stopfind($id){
        return Db::name("stock_renewal")->where(["status"=>0])->where(["type"=>2])->where(["borrow_id"=>$id])->find();
    }
    //返回提前终止记录列表
    public static function getStopList($map,$order)
    {
        $data_list = self::view('stock_renewal a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration as b_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where('a.status','<>',0)
            ->where('a.type','=',2)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->borrow_fee = money_convert($item->borrow_fee);
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->deposit_money = money_convert($item->deposit_money);
                $item->b_duration.=$unit;
                $item->borrow_duration .= $unit;
            });
        return $data_list;
    }
    //返回单条提前终止记录
    public static function getStopById($id)
    {
        $data_list = self::view('stock_renewal a', true)
            ->view("stock_borrow b", 'stock_subaccount_id,multiple,init_money,borrow_money,borrow_duration as b_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where('a.status','=',0)
            ->where('a.type','=',2)
            ->where('a.id','=',$id)
            ->find();
        return $data_list;
    }
    //返回续期记录
    public static function getRenewalById($id)
    {
        $data_list = self::view('stock_renewal a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,order_id,borrow_duration as b_duration,borrow_interest,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where('a.status','=',0)
            ->where('a.type','=',1)
            ->where('a.id','=',$id)
            ->find();
        return $data_list;
    }
    //存储续期数据
    public function saveRenewal($id,$status,$info){
        $minfo_m =  Db::name('member')->where(['id'=>$info['uid']])->where(['status'=>1])->find();
        Db::startTrans();
        $minfo=Db::name("money")->lock(true)->where(['mid'=>$info['uid']])->find();
        if ($status == 1) {//审核通过
            $infomoney=$info['borrow_fee']/100;
            $msg = "审核通过，扣除冻结利息费{$infomoney}元";
            $mmoney['account'] = $minfo['account'];
            $type = 28;
            //增加续期
            $sdata['borrow_duration'] = $info['b_duration']+$info['borrow_duration'];
            if($info['type']!==3){
                $sdata['borrow_interest'] = $info['borrow_interest']+$info['borrow_fee'];
            }
            $sdata['end_time'] = $this->getAddTime($info['type'], $info['end_time'], $info['borrow_duration']);
            if($sdata['end_time']>time()){
                $sdata['status']=1;
                $sub_id= Db::name("stock_borrow")->where("id={$info['borrow_id']}")->value('stock_subaccount_id');
                $risk_info=Db::name("stock_subaccount_risk")->where(['stock_subaccount_id'=>$sub_id])->find();
                if(isset($risk_info['prohibit_open'])&&$risk_info['prohibit_open']==1&&$risk_info['prohibit_close']==1){
                    $risk_res=true;
                }else{
                    $rdata['prohibit_open']=1;
                    $rdata['prohibit_close']=1;
                    $risk_res=Db::name("stock_subaccount_risk")->where(['stock_subaccount_id'=>$sub_id])->update($rdata);
                }
            }else{
                $risk_res=true;
            }
            $borrow_res=Db::name("stock_borrow")->where("id={$info['borrow_id']}")->update($sdata);
            $adata['status'] = 1;
        } else {//审核不通过
            $mmoney['account'] = $minfo['account'] + $info['borrow_fee'];
            $msg = '审核未通过，返回冻结服务费';
            $type = 29;
            $borrow_res=true;
            $risk_res=true;
            $adata['status'] = 2;
        }

        //退冻结费用
        $mmoney['freeze'] = $minfo['freeze'] - $info['borrow_fee'];
        $money_res = MoneyModel::money_up($info['uid'], $mmoney);

        $record = new RecordModel();
        $record_res = $record->saveData($info['uid'], $affect = $info['borrow_fee'], $mmoney['account'], $type, $msg);

        $adata['verify_time'] = time();
        $renewal_res=self::where("id={$id}")->update($adata);

        if ($record_res&&$money_res&&$renewal_res&&$borrow_res&&$risk_res) {
			/*$params  = $minfo_m['mobile'];
			send_sms_member($msg,$params,1000);*/
            if($adata['status']==1){
                $contentarr  = getconfigSms_status(['name'=>'stock_renewal_saveRenewal']);
            }else{
                $contentarr  = getconfigSms_status(['name'=>'stock_renewal_saveRenewal_fail']);

            }
            $content = str_replace(array("#var#","#order_id#"),array($minfo_m['mobile'],$info['order_id']), $contentarr['value']);
            if($contentarr['status']==1){
            $res = sendsms_mandao($minfo_m['mobile'],$content,'user');
            }
            //send_sms($minfo_m['mobile'],'', $msg);
            $MemberMessageModel = new MemberMessageModel();
            $MemberMessageModel->addInnerMsg($info['uid'],'续期审核通知',$msg);//站内信
            Db::commit();
            return ['status'=>'1', 'msg'=>'提交成功'];
        }else{
            Db::rollback();
            return ['status'=>'0', 'msg'=>'提交失败'];
        }
    }

    //存储提前终止数据
    public function saveStop($id,$status,$info,$surplus_money,$addmoney,$drawprofit){
        $minfo_m =  Db::name('member')->where(['id'=>$info['uid']])->where(['status'=>1])->find();
        Db::startTrans();
        $minfo=Db::name("money")->lock(true)->where(['mid'=>$info['uid']])->find();
        //解冻金额
        if ($status == 1) {//通过审核
            $type = 30;
            $msg = '申请终止配资审核通过';
            $mmoney['account'] = $minfo['account'];
        } else {//未通过 退回冻结利息
            $status=2;
            $type = 31;
            $msg = '申请终止配资审核未通过';
            $mmoney['account'] = $minfo['account'] + $info['borrow_fee'];

        }
        if ($info['borrow_fee'] > 0) {//终止配资费用
            $mmoney['freeze'] = $minfo['freeze'] - $info['borrow_fee'];
            $money_res = MoneyModel::money_up($info['uid'], $mmoney);
            $record = new RecordModel();
            $record_res = $record->saveData($info['uid'], $affect = $info['borrow_fee'], $mmoney['account'], $type, $msg);
        }else{
            $record_res=true;
            $money_res=true;
        }
        if($status == 1){
            //结算
            $settlement_res=$this->settlementFinancing($surplus_money,$addmoney,$drawprofit, $info['borrow_id']);
        }else{
            $settlement_res=true;
        }
        //更新状态
        $stopData['status'] = $status;
        $stopData['verify_time'] = time();
        $stop_res=self::where("id={$id}")->update($stopData);

        if ($record_res&&$money_res&&$stop_res&&$settlement_res) {
			$params  = $minfo_m['mobile'];
			send_sms_member($msg,$params,1000);
           // send_sms($minfo['mobile'],'', $msg);
            $MemberMessageModel = new MemberMessageModel();
            $MemberMessageModel->addInnerMsg($info['uid'],'提前终止配资审核通知',$msg);//站内信
            Db::commit();
            return ['status'=>'1', 'msg'=>'审核成功'];
        }else{
            Db::rollback();
            return ['status'=>'0', 'msg'=>'审核失败'];
        }
    }
    /**
     *    结算
     *    $surplus_money :剩余配资金额
     *    $addmoney 累计追加保证金
     *    $drawprofit 累计提取盈利
     *    $id :配资ID
     *    $contents :说明
     */
    public function settlementFinancing($surplus_money,$addmoney=0,$drawprofit=0,$id){
        //$binfo = BorrowModel::getBorrowById($id,1);
        $binfo = Db::name("stock_borrow")->where(["id"=>$id])->where('status','in',[1,3])->find();
        if ($binfo) {
            $type2 = 0;
            $resu=$drawprofit-$addmoney;
            $money= round($surplus_money - $binfo['init_money'] + $resu,2);//盈利金额

            if($binfo['type']==6){//模拟操盘
                //最终盈利金额
                $sub_data['return_money'] = $money;
                $sub_data['return_rate'] = round($sub_data['return_money'] / $binfo['deposit_money'] * 100,2);
                $sub_data['avail'] = 0;//股票可用金额清零
                $sub_res=SubaccountMoney::saveSubMoney($sub_data,$binfo['stock_subaccount_id']);//更新子账户资金信息

                //更新状态
                $stockCondition['status'] = 1;
                $stockCondition['id'] = $binfo['id'];

                $data['status'] = 2;
                $data['stock_money'] = $surplus_money+$resu;
                $borrow_ret=Db::name("stock_borrow")->where($stockCondition)->update($data);
                if ($borrow_ret&&$sub_res) {
                    return true;
                } else {
                    return false;
                }
            }
            if ($money>0) {//盈利
                //免息配资设置
                $freeSet = explode('|',config('free_set'));
                $type = 9;//释放配资保证金
                $type2 = 20;//配资结算
                $affect_money = $binfo['deposit_money'];
                $msg = '配资使用期限结束，释放保证金';
                if($binfo['type'] == 5){//免费配资
                    $affect_money2 = ($money-$drawprofit+$addmoney) * $freeSet[2]/100 ;
                    $affect_money2_tmd = ($money-$drawprofit+$addmoney) * $freeSet[2]/100 / 100;
                    $share_rate=$freeSet[2];
                } else {
                    $affect_money2 = ($money-$drawprofit+$addmoney);
                    $affect_money2_tmd = ($money-$drawprofit+$addmoney)/100;
                    $share_rate=100;
                }
                $money_tmd=($money+$addmoney)/100;
                $msg2 = "配资使用期限结束，盈利{$money_tmd}元，盈利的{$share_rate}% {$affect_money2_tmd}元归您";

            } elseif (abs($money) < 0.001) {//持平
                $msg = '配资使用期限结束，释放保证金';
                $type = 9;
                $affect_money = $binfo['deposit_money'];
                $affect_money2=0+$addmoney-$drawprofit;
                $type2 = 20;//配资结算
                $msg2="配资使用期限结束,剩余配资金额与原总操盘金额相等，释放保证金";
                if($affect_money2>0){
                    $msg2 = '配资使用期限结束，由于您剩余配资资金大于总操盘资金，账户可用余额增加' . money_convert($affect_money2) . '元';
                }elseif($affect_money2<0){
                    $msg2 = '配资使用期限结束，由于您剩余配资资金小于总操盘资金，扣除亏损金额' . money_convert($affect_money2) . '元';
                }
            } else {//亏损 如果是免费配资亏损由平台承担，否则由用户承担
                $lossMoney = -$money;//转化为正值
                if (($binfo['type']===4) && ($lossMoney >= ($binfo['deposit_money']+$addmoney))) {//免费配资  如果亏损大于等于保证金
                    $sumMoney = -($binfo['deposit_money']);//扣除保证金
                } else {
                    $sumMoney = -($lossMoney+$drawprofit-$addmoney);
                }
                $msg = '配资使用期限结束，释放保证金';
                if(($addmoney-abs($money-$drawprofit))>0){
                    $msg2 = '配资使用期限结束，由于您剩余配资资金大于总操盘资金，账户可用余额增加' . money_convert($sumMoney) . '元';
                }elseif(($addmoney-abs($money-$drawprofit))<0){
                    $msg2 = '配资使用期限结束，由于您剩余配资资金小于总操盘资金，扣除亏损金额' . money_convert($sumMoney) . '元';
                }
                $type = 9;
                $type2 = 20;//试用配资，平台承担亏损、返回保证金
                $affect_money = $binfo['deposit_money'];
                $affect_money2 = $sumMoney;
            }


            $minfo=Db::name("money")->lock(true)->where(['mid'=>$binfo['member_id']])->find();
            //释放保证金
            $record_res1=$this->addMoneyLogInfo($binfo['member_id'],$minfo, $type, $affect_money, $binfo['deposit_money'],$binfo['init_money'],  $msg);
            //必须重新获取会员资金信息不然上次存储的资金资金变动不起作用

            $minfo=Db::name("money")->lock(true)->where(['mid'=>$binfo['member_id']])->find();
            if ($type2 === 20) {
                //将盈利（或亏损）加到会员可用余额
                $record_res2=$this->addMoneyLogInfo($binfo['member_id'],$minfo, $type2, $affect_money2, 0,0, $msg2);
            }else{
                $record_res2=true;
            }
            //最终盈利金额
            $sub_data['return_money'] = $money;
            $sub_data['return_rate'] = round($sub_data['return_money'] / $binfo['deposit_money'] * 100,2);
            $sub_data['avail'] = 0;//股票可用金额清零
            $sub_res=SubaccountMoney::saveSubMoney($sub_data,$binfo['stock_subaccount_id']);//更新子账户资金信息

            //更新状态
            //$stockCondition['status'] = 1;
            $stockCondition['id'] = $binfo['id'];

            $data['status'] = 2;
            $data['stock_money'] = $surplus_money+$resu;
            $borrow_ret=Db::name("stock_borrow")->where($stockCondition)->update($data);
            //更新还款状态
            $d_res=Db::name('stock_detail')->where("status=0 and borrow_id={$binfo['id']} and mid={$binfo['member_id']}")->find();
            if(!empty($d_res)){
                $detail_data['status'] = 1;
                $detail_res=Db::name('stock_detail')->where("status=0 and borrow_id={$binfo['id']} and mid={$binfo['member_id']}")->update($detail_data);
            }else{
                $detail_res=true;
            }
            if ($borrow_ret&&$record_res1&&$record_res2&&$sub_res&&$detail_res) {
                return true;
            } else {
                return false;
            }
        }else{ //如果状态不再操盘中，直接返回true跳过
            return true;
        }
    }
    //将盈利（或亏损）加到会员可用余额释放保证金
    private function addMoneyLogInfo($uid,$minfo,$type, $affect, $deposit_money = 0,$init_money=0, $info)
    {
        $account = $minfo['account']+ $affect;
        //record 释放配资保证金
        $record = new RecordModel();
        $record_res = $record->saveData($uid, $affect, $account, $type, $info);
        //退保证金并添加到可用余额
        if($affect==0&&$deposit_money == 0&&$init_money=0){
            return true;
        }
        $mmoney['account'] = $account;
        $mmoney['operate_account'] = $minfo['operate_account'] - $init_money;
        $mmoney['bond_account'] = $minfo['bond_account'] - $deposit_money;
        $money_res = MoneyModel::money_up($uid, $mmoney);
        if ($record_res && $money_res !== null) {
            return true;
        } else {
            return false;
        }
    }
    /*	得到续期时间
     *	$type :1、天，2、周，3、月
     *  $end_time :原过期时间
     *	$borrow_duration :申请时间段
     */
    public static function getAddTime($type, $end_time, $borrow_duration)
    {
        switch ($type){
            case 1:
                $nowData = date("Y-m-d", $end_time);//以之前结束时间开始算
                $set_holidays = festival();
                $endYmd = getEndDay($nowData, $borrow_duration, $set_holidays);
                $addEndTime = $endYmd . " 14:45:00";//结束时间
                $addEndTime = strtotime($addEndTime);
                break;
            case 2:
                $addEndTime = strtotime("+{$borrow_duration} week", $end_time);
                break;
            case 3:
                $addEndTime = strtotime("+{$borrow_duration} month", $end_time);
                break;
            default:
                $addEndTime=$end_time;
                break;
        }
        return $addEndTime;
    }

}