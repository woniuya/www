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
use app\money\model\Record as RecordModel;
use app\member\model\Member as MemberModel;
use app\money\model\Money as MoneyModel;
use app\member\model\MemberMessage as MemberMessageModel;
use app\market\model\SubAccountMoney as submoney;
use think\model;
use think\Db;
class Addfinancing extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_ADDFINANCING__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    //返回追加配资申请列表
    public static function getAddfinancing($map,$order)
    {
        $where['a.status'] = intval(0);
        $data_list = self::view('stock_addfinancing a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->borrow_interest = money_convert($item->borrow_interest);
                $item->money = money_convert($item->money);
                $item->last_deposit_money = money_convert($item->last_deposit_money);
                $item->loss_warn = $item->borrow_money+$item->loss_warn*money_convert($item->last_deposit_money);
                $item->loss_close = $item->borrow_money+$item->loss_close*money_convert($item->last_deposit_money);
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->borrow_duration .= $unit;

            });
        return $data_list;
    }
    //返回追加配资记录
    public static function getAddfList($map,$order)
    {
        $map2['a.status']=array(['=',1],['=',2],'or');
        $data_list = self::view('stock_addfinancing a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($map)
            ->where($map2)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->borrow_interest = money_convert($item->borrow_interest);
                $item->money = money_convert($item->money);
                $item->last_deposit_money = money_convert($item->last_deposit_money);
                $item->deposit_money = money_convert($item->deposit_money);
                $item->loss_warn = $item->borrow_money+$item->loss_warn*money_convert($item->deposit_money);
                $item->loss_close = $item->borrow_money+$item->loss_close*money_convert($item->deposit_money);
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->borrow_duration .= $unit;
        });
        return $data_list;
    }
    //
    public static function getAddfList2($map,$order)
    {
        //$map2['a.status']=array(['=',1],['=',2],'or');
        $data_list = self::view('stock_addfinancing a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            //->where($map2)
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
            $data_list[$k]['borrow_interest'] = money_convert($item['borrow_interest']);
            $data_list[$k]['money'] = money_convert($item['money']);
            $data_list[$k]['last_deposit_money'] = money_convert($item['last_deposit_money']);
            if($item['type']===3){$unit='个月';}elseif($item['type']===2){$unit='周';}else{$unit='天';}
            $data_list[$k]['borrow_duration'] .= $unit;
        }
        return $data_list;
    }
    //
    public static function getAddfinancingById($id)
    {
        $where['a.status'] = intval(0);
        $data = self::view('stock_addfinancing a', true)
            ->view("stock_borrow b", 'id as borrow_id,stock_subaccount_id,multiple,init_money,order_id,borrow_money,deposit_money,borrow_interest as y_interest,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where(['a.id'=>$id])
            ->find();
        return $data;
    }
    /*
     * 存储审核结果
     *
     */
    public function saveAddfinancing($id,$status,$info){

        $applyMoney = $info['money'] * $info['multiple'];//申请的配资总金额
        $minfo_m =  Db::name('member')->where(['id'=>$info['uid']])->where(['status'=>1])->find();
        Db::startTrans();
        $minfo=Db::name("money")->lock(true)->where(['mid'=>$info['uid']])->find();
        $sumMoney = $info['money'] + $info['borrow_interest'];
         try{
                if ($status ==1) {//审核成功
                    $bdata['init_money'] = $info['init_money']+$applyMoney+$info['money'];
                    $bdata['borrow_money'] = $info['borrow_money']+$applyMoney;
                    // 保证金等于 原配资保证金+新申请保证金
                    $bdata['deposit_money'] = $info['deposit_money'] + $info['money'];
                    $bdata['borrow_interest'] = $info['borrow_interest']+$info['y_interest'];//新管理费+原管理费

                    $borrow_res=Db::name("stock_borrow")->where("id={$info['borrow_id']}")->update($bdata);
                    if(!$borrow_res){
                        throw new \Exception("更新配资表异常");
                    }
                    $yuantotal = MoneyModel::getMoney($info['uid']);

                    $submoney_info=SubaccountMoney::getMoneyByID($info['stock_subaccount_id']);//子账户资金信息
                    if(!$submoney_info){
                        throw new \Exception("系统出错");
                    }
                    $submoney=new submoney();
                    $sub_res=$submoney->up_moneylog($info['stock_subaccount_id'],$info['money'],11,$applyMoney);

                    $mmoney['bond_account'] = $yuantotal['bond_account'] + $info['money'];
                    $mmoney['operate_account'] = $yuantotal['operate_account'] + $applyMoney+$info['money'];
                    $mmoney['account'] = $yuantotal['account'];
                    $init_m=$bdata['init_money']/100;
                    $msg = "恭喜您申请追加配资成功,总操盘资金增加为{$init_m}元";
                    $type = 24;
                    $data['status'] = 1;
                } else {//审核失败，退回冻结金额
                    $mmoney['account'] = $minfo['account'] + $sumMoney;
                    $msg = '追加配资审核不通过,释放冻结资金';
                    $type = 25;
                    $sub_res=true;
                    $data['status'] = 2;
                }
                $mmoney['freeze'] = $minfo['freeze'] - $sumMoney;
                $money_res = MoneyModel::money_up($info['uid'], $mmoney);
                $record = new RecordModel();
                $record_res = $record->saveData($info['uid'], $affect = $sumMoney, $mmoney['account'], $type, $msg);
                //更新状态

                $data['verify_time'] = time();
                $addf_res=self::where("id={$id}")->update($data);
                if ($record_res&&$money_res&&$addf_res&&$sub_res) {
                    //send_sms($minfo_m['mobile'],'', $msg);
                    if($data['status'] ==1){
                        $contentarr  = getconfigSms_status(['name'=>'stock_addfinancing_success']);
                    }else{
                        $contentarr  = getconfigSms_status(['name'=>'stock_addfinancing_saveAddfinancing']);
                    }
                    $content = str_replace(array("#var#","#order_id#"),array($minfo_m['mobile'],$info['order_id']), $contentarr['value']);
                    if($contentarr['status']==1) {
                        $res = sendsms_mandao($minfo_m['mobile'], $content, 'user');
                    }
                    Db::commit();
                    $MemberMessageModel = new MemberMessageModel();

                    $MemberMessageModel->addInnerMsg($info['uid'],"扩大配资审核通知",$msg);//站内信
                    return ['status'=>'1', 'msg'=>'提交成功'];
                }else{
                    Db::rollback();
                    return ['status'=>'0', 'msg'=>'提交失败'];
                }
            } catch (\Exception $e) {
                Db::rollback();
                return ['status'=>'0', 'msg'=>'处理异常，请联系客服'];
            }
    }

}