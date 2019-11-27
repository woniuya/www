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
use app\member\model\Member as MemberModel;
use app\money\model\Money as MoneyModel;
use app\money\model\Record as RecordModel;
use app\member\model\MemberMessage as MemberMessageModel;
use app\market\model\SubAccountMoney as submoney;
use think\model;
use think\Db;
class Addmoney extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_ADDMONEY__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    //返回追加配资申请列表
    public static function getAddmoney($map,$order)
    {
        $where['a.status'] = intval(0);
        $data_list = self::view('stock_addmoney a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->init_money = money_convert($item->init_money);
                $item->borrow_money = money_convert($item->borrow_money);
                $item->money = money_convert($item->money);
                $item->deposit_money = money_convert($item->deposit_money);
                $item->loss_warn = $item->loss_warn."%";
                $item->loss_close = $item->loss_close."%";
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->borrow_duration .= $unit;
            });
        return $data_list;
    }
    //返回追加配资记录
    public static function getAddmoneyList($map,$order)
    {
        $data_list = self::view('stock_addmoney a', true)
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
                $item->loss_warn = $item->loss_warn."%";
                $item->loss_close = $item->loss_close."%";
                if($item->getData('type')===3){$unit='个月';}elseif($item->getData('type')===2){$unit='周';}else{$unit='天';}
                $item->borrow_duration .= $unit;
            });
        return $data_list;
    }
    public static function getAddmoneyList2($map,$order)
    {
        $data_list = self::view('stock_addmoney a', true)
            ->view("stock_borrow b", 'multiple,init_money,borrow_money,deposit_money,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            //->where('a.status','<>',0)
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
            $data_list[$k]['money'] = money_convert($item['money']);
            $data_list[$k]['deposit_money'] = money_convert($item['deposit_money']);
        }
        return $data_list;
    }
    public static function getAddmoneyById($id)
    {
        $where['a.status'] = intval(0);
        $data = self::view('stock_addmoney a', true)
            ->view("stock_borrow b", 'stock_subaccount_id,multiple,init_money,order_id,borrow_money,deposit_money,borrow_interest as y_interest,borrow_duration,type,end_time', 'a.borrow_id=b.id', 'left')
            ->view('stock_subaccount_risk ssr', 'loss_warn, loss_close,renewal', 'b.stock_subaccount_id=ssr.stock_subaccount_id', 'left')
            ->view("member m", 'mobile,name', 'a.uid=m.id', 'left')
            ->where($where)
            ->where(['a.id'=>$id])
            ->find();
        return $data;
    }
    /*
     * 保存审核结果
     */
    public function saveAddmoney($id,$status,$info){
        $minfo_m =  Db::name('member')->where(['id'=>$info['uid']])->where(['status'=>1])->find();
        Db::startTrans();
        $minfo=Db::name("money")->lock(true)->where(['mid'=>$info['uid']])->find();
        if ($status == 1) {//审核通过
            $mmoney['account'] = $minfo['account'];
            $submoney_info=SubaccountMoney::getMoneyByID($info['stock_subaccount_id']);
            if(!$submoney_info){
                throw new Exception("系统错误");
            }
            $submoney=new submoney();
            $sub_res=$submoney->up_moneylog($info['stock_subaccount_id'],$info['money'],10);

            $type = 26;
            $msg = '审核通过，扣除冻结金额，增加保证金金额';
            $data['status'] = 1;
        } else {//审核不通过 退回冻结金额
            $mmoney['account'] = $minfo['account'] + $info['money'];
            $type = 27;
            $msg = '审核不通过，退回冻结金额';
            $sub_res=true;
            $data['status'] = 2;
        }

        $mmoney['freeze'] = $minfo['freeze'] - $info['money'];
        $money_res = MoneyModel::money_up($info['uid'], $mmoney);
        $record = new RecordModel();
        $record_res = $record->saveData($info['uid'], $affect = $info['money'], $mmoney['account'], $type, $msg);
        //更新状态及添加保证金

        $data['verify_time'] = time();
        $addf_res=self::where("id={$id}")->update($data);

        if ($record_res&&$money_res&&$addf_res&&$sub_res) {
           // send_sms($minfo['mobile'],'', $msg);
            if($data['status']==1){
                $contentarr  = getconfigSms_status(['name'=>'stock_addmoney_saveAddmoney']);
            }else{
                $contentarr  = getconfigSms_status(['name'=>'stock_addmoney_saveAddmoney_fail']);
            }
            $content = str_replace(array("#var#","#order_id#"),array($minfo_m['mobile'],$info['order_id']), $contentarr['value']);
            if($contentarr['status']==1){
            $res = sendsms_mandao($minfo_m['mobile'],$content,'user');
            }
            Db::commit();
            $MemberMessageModel = new MemberMessageModel();
            $MemberMessageModel->addInnerMsg($info['uid'],'追加保证金审核通知',$msg);//站内信
            return ['status'=>'1', 'msg'=>'提交成功'];
        }else{
            Db::rollback();
            return ['status'=>'0', 'msg'=>'提交失败'];
        }

    }

}