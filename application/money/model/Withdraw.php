<?php
    namespace app\money\model;
    use think\helper\Hash;
    use app\money\model\Role as RoleModel;
    use app\money\model\Record;
    use app\member\model\Bank as BankModel;
    use app\member\model\MemberMessage as MemberMessageModel;
    use think\Model;
    use think\Db;
    use think\Exception;

    class Withdraw extends Model
    {
        
        // 设置当前模型对应的完整数据表名称
        protected $table = '__MONEY_WITHDRAW__';

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
        	$status = [0=>'待处理', 1=>'成功', 2=>'失败', 3=>'退回'];
        	return $status[$data['status']];
        }

        public static function  getAll($map=[], $order='')
        {
            $data_list = self::view('money_withdraw', true)
                        ->view('member', 'mobile, name, id_card', 'member.id=money_withdraw.mid', 'left')
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
         * 提现审核信息保存
         * @return [type] [description]
         */
        public static function saveAudit()
        {
        	$res1 = $res2 = $res3 = true;
        	$status = input('post.status');
        	$id = input('post.id');
        	$remark = input('post.remark');
        	if(!$id){
        		return false;
        	}
        	$withdraw = Db('money_withdraw')->where('id', $id)->find();
            $user_mobile = Db('member')->where('id',$withdraw['mid'])->value('mobile');
        	$up_withdraw['status'] = $status;
        	$up_withdraw['id'] = $id;

        	Db::startTrans();
        	try{
                $record = new record;
                $money_info = Db('money')->where('id', $withdraw['mid'])->lock(true)->find();
        		if($status==1){// 提现通过 减去冻结金额

                    self::sms_withdraw('stock_withdraw_auditing_success',$user_mobile,$withdraw['money']);
                    $contents="提现审核通过";
                    $up_money['freeze'] = bcsub($money_info['freeze'], $withdraw['money']);
	        		
                    $info = "提现单号：".$withdraw['order_no'];
                    $affect = $withdraw['money'];
                    $type = 3;
                    $account = $money_info['account'];
	        		
				}elseif($status==2){ // 提现失败 解冻冻结金额
                    self::sms_withdraw('stock_withdraw_auditing_fail',$user_mobile,$withdraw['money']);
                    $contents="提现审核未通过";
                    $up_money['freeze'] = bcsub($money_info['freeze'], $withdraw['money']);
                    $up_money['account'] = bcadd($money_info['account'], $withdraw['money']);
                   
                    $info = "解冻金额".($withdraw['money']/100)."元,提现单号：".$withdraw['order_no'];
                    $affect = $withdraw['money'];
                    $type = 4;
                    $account = $up_money['account'];
                  
                }elseif($status==3){// 提现退回 补充可用金额
                    $up_money['account'] = bcadd($money_info['account'], $withdraw['money']);
                    
                    $info = "退回金额".($withdraw['money']/100)."元,提现单号：".$withdraw['order_no'];
                    $affect = $withdraw['money'];
                    $type = 6;
                    $account = $up_money['account'];
                }else{
                    return '状态有误';
                }

                $res1 = Db('money')->where('mid', $withdraw['mid'])->update($up_money); 
                $res3 = $record->saveData($withdraw['mid'], $affect, $account, $type, $info);
              
				$res2 = self::update($up_withdraw);
        		if($res1 && $res2 && $res3){
        			Db::commit();
                    //添加站内信信息
                    $MemberMessageModel = new MemberMessageModel();
                    $MemberMessageModel->addInnerMsg($withdraw['mid'],'提现审核通知',$contents);//站内信
        		}else{
        			Db::rollback();
        			return '数据更新失败';
        		}
        		
        	}catch(\Exception $e){ 
        		Db::rollback();
        		return $e->getMessage();
        	}
        	
        	$mobile = Db('member')->where('id', $withdraw['mid'])->value('mobile');
        	$details = $mobile.' 字段(status)，原值：(0)新值：(' . $status . ') 备注：'.$remark.$info;
        	action_log('withdraw_edit', 'money_withdraw', $id, UID, $details);
        	return true;

        	
        }
        static  public function  sms_withdraw($type,$mobile,$money){
            $contentarr  = getconfigSms_status(['name'=>$type]);
            $content = str_replace(array("#var#","#amount#"),array($mobile,money_convert($money)), $contentarr['value']);
            $res=false;
            if($contentarr['status']==1){
            $res = sendsms_mandao($mobile,$content,'');
            }
            return $res;
        }
        public static function saveData($parameter)
        {
            $bank_id = $parameter['bank_id'];
            $bank = BankModel::bankInfo($bank_id);
            $where1['id'] = $parameter['mid'];//会员ID
            $where1['status'] = 1;//会员状态
            $names = Db::name('member')->field('name')
                ->where($where1)
                ->find();
            $data['bank'] = $bank['bank']."|".$bank['card'].'|'.$bank['province'].$bank['city'].$bank['branch']."|".$names['name'];
            $data['mid'] = $parameter['mid'];
            $data['money'] = $parameter['money']*100;
            $data['order_no'] = 'tx'.generate_rand_str(10, 3);
            $data['create_time'] = time();
            $data['create_ip'] = get_client_ip(1);

            $record = new record;
            Db::startTrans();
            $money_info = Db('money')->where('mid', $data['mid'])->lock(true)->find();
            $account = bcsub($money_info['account'], $data['money']);

            $up_money['freeze'] = bcadd($money_info['freeze'], $data['money']);
            $up_money['account'] = $account;

            $info = "提现冻结金额".($data['money']/100)."元,提现单号：".$data['order_no'];
            try{
                $res1 = self::create($data);
                $res2 = $record->saveData($data['mid'], -$data['money'], $account, 2, $info);
                $res3 = Db('money')->where('mid', $data['mid'])->update($up_money); 
                if($res1 && $res2 && $res3){
                    Db::commit();
                    return ['status'=>1, 'message'=>'提交成功'];
                }else{
                    Db::rollback();
                    return ['status'=>0, 'message'=>'提交失败'];
                }
            }catch(\Exception $e){
                Db::rollback();
                return ['status'=>0, 'message'=>'数据异常'];
            }
        }

    }
?>
