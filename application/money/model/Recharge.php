<?php
namespace app\money\model;
use think\helper\Hash;
use app\money\model\Role as RoleModel;
use think\Model;
use app\member\model\MemberMessage as MemberMessageModel;
use think\Db;
use think\Exception;
class Recharge extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $table = '__MONEY_RECHARGE__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function setCreateTimeAttr()
    {
        return time();
    }

    public function setCreateIpAttr()
    {
        return get_client_ip(1);
    }


    public function getStatusAttr($value, $data)
    {
        $status = [0=>'待处理', 1=>'成功', 2=>'失败', 3=>'签名不符'];
        return $status[$data['status']];
    }

    public static function  getAll($map=[], $order='')
    {
        $data_list = self::view('money_recharge', true)
            ->view('member', 'mobile, name, id_card', 'member.id=money_recharge.mid', 'left')
            ->where($map)
            ->order($order)
            ->paginate()
            ->each( function($item, $key){
                $item->money = money_convert($item->money);
                $item->fee = money_convert($item->fee);
            });
        return $data_list;

    }

    /**
     * 充值审核信息保存
     * @return [type] [description]
     */
    public static function saveAudit()
    {
        $res1 = $res3 = true;
        $status = input('post.status');
        $id = input('post.id');
        $remark = input('post.remark');
        if(!$id){
            //$this->error = '缺少主键';
            return false;
        }
        $charge = Db('money_recharge')->where('id', $id)->find();

        $up_charge['status'] = $status;
        $up_charge['id'] = $id;
        $contents="充值审核未通过";
        Db::startTrans();
        try{
            if($status==1){
                $contents="充值审核通过";
                $account = Db('money')->where('mid', $charge['mid'])->lock(true)->value('account');
                $up_money['account'] = bcadd($account, $charge['money']);
                $res1 = Db('money')->where('mid', $charge['mid'])->update($up_money);

                $info = '充值单号：'.$charge['order_no'];
                $record = new Record;
                $res3 = $record->saveData($charge['mid'],  $charge['money'], $up_money['account'], 1, $info);
            }
            $res2 = Db('money_recharge')->update($up_charge);
            if($res1 && $res2 && $res3){
                $user_mobile = $account = Db('member')->where('id', $charge['mid'])->value('mobile');
                switch ($status){
                    case 1:
                        /*$content = \think\Config::get('sms_template')['stock_offline_auditing_success'];
                        $content = str_replace(array("#var#","#amount#"),array($user_mobile,money_convert($charge['money'])), $content);
                        $res = sendsms_mandao('',$content,'');*/
                        self::sms_recharge('stock_offline_auditing_success',$user_mobile,$charge['money']);
                        break;
                    case 2:
                        self::sms_recharge('stock_offline_auditing_fail',$user_mobile,$charge['money']);
                        break;
                }

                Db::commit();
                //添加站内信信息
                $MemberMessageModel = new MemberMessageModel();
                $MemberMessageModel->addInnerMsg($charge['mid'],'充值审核通知',$contents);//站内信
            }else{
                Db::rollback();
                return '数据更新失败';
            }

        }catch(\Exception $e){
            Db::rollback();
            return '数据异常';
        }

        $mobile = Db('member')->where('id', $charge['mid'])->value('mobile');
        $details = $mobile.' 字段(status)，原值：(0)新值：(' . $status . ') 备注：'.$remark;
        action_log('recharge_edit', 'money_recharge', $id, UID, $details);
        return true;


    }
    public function  sms_recharge($type,$mobile,$money){
        $contentarr  = getconfigSms_status(['name'=>$type]);
        $content = str_replace(array("#var#","#amount#"),array($mobile,money_convert($money)), $contentarr['value']);
        if($contentarr['status']==1){
        $res = sendsms_mandao($mobile,$content,'user');
        }
        return $res;
    }
    /**
     * 保存充值数据到数据库
     * @param  float $money     充值金额
     * @param  int $mid       用户id
     * @param  string $type      充值类型
     * @param  string $line_bank 线下转账充值的银行信息
     * @return string            返回订单号
     */
    public static function saveData($money, $mid, $type, $line_bank='',$receipt_img=null,$charge_type_id,$transfer)
    {
        //$data['order_no'] = 'cz'.generate_rand_str(10, 3);
        $data['order_no'] = 'cz'.date('YmdHis').generate_rand_str(4, 3);

        $data['mid'] = $mid;
        $data['money'] = $money*100;
        $data['type'] = $type;
        $data['line_bank'] = $line_bank;
        $data['create_time'] = time();
        $data['create_ip'] = time();
        $data['status'] = 0;
        $data['receipt_img'] = $receipt_img;
        $data['charge_type_id'] = $charge_type_id;
        $data['form_name'] = $transfer;

        $result = self::create($data);
        return $result->order_no;
    }

}
?>
