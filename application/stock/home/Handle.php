<?php
	// +----------------------------------------------------------------------
	// | 版权所有 2016~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
	// +----------------------------------------------------------------------
	// | 官方网站: http://lurenjiayi.com
	// +----------------------------------------------------------------------
	// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
	// +----------------------------------------------------------------------
	// | @author 张继立 <404851763@qq.com>
	// +----------------------------------------------------------------------
	namespace app\stock\home;
	use think\Db;
	use think\Hook;
	use app\index\controller\Home;
	use app\stock\model\Borrow as BorrowModel;
	use app\member\model\Member as MemberModel;

	/**
	 * 配资处理类
	 */
	class Handle extends Home
	{

		protected function _initialize()
	    {
	        parent::_initialize();
	    }
		/**
		 * 申请配资提交
		 * @param  int $type            类型
		 * @param  int $multiple        倍数
		 * @param  int $deposit_money   保证金
		 * @param  int $borrow_duration 资金使用时间
		 * @param  int $trading_time    交易时间 0 今日 1 下个交易日
		 * @return string               
		 * @author 张继立 <404851763@qq.com>
		 */
		 ///handle/apply.html?type=4&deposit_money=2000&borrow_duration=2&trading_time=1
		public function applySave()
		{
			$mid = is_member_signin();

			if(!$mid){
				$this->error('请登陆后进行申请', URL('/login'));
			}
            $req=request();
            $type=intval($req::instance()->param('type'));
            $multiple=intval($req::instance()->param('multiple'));
            $deposit_money=intval($req::instance()->param('deposit_money'));
            $borrow_duration=intval($req::instance()->param('borrow_duration'));
            $trading_time=intval($req::instance()->param('trading_time'));
            $money_range=explode('|', config('money_range'));
            $money_min = $money_range[0];//最低配资金额
            //$money_max = $money_range[1]*$multiple; //最高配资金额
            $money_step = $money_range[2]; //配资金额递增幅度

            if($req::instance()->param('agreement') != 'true'){
                switch ($type)
                {
                    case 6:
                        $msg = '您没有勾选《实盘交易平台模拟操盘协议》';
                    break;
                    case 4:
                        $msg = '您没有勾选《实盘交易平台体验协议》';
                    break;
                    default:
                        $msg = '您没有勾选《实盘交易平台操盘协议》';
                }
                $this->error($msg);
            }
            if($deposit_money % $money_step != 0 && $type !== 4){
                $this->error('您要配资的金额必须是'.$money_step.'的整数倍');
            }
            if($deposit_money < intval($money_min) && $type !== 4){
                $this->error('配资金额不能低于'.$money_min.'元',URL('/money/recharge/index'));
            }
            $db = new BorrowModel;
            if($type!=4){
                $onecheck=$db->onecheck($mid);
                if(!empty($onecheck)){
                    $this->error('您已有配资申请，请耐心等候审核');
                }
            }
            //配资类型  1:按天配资 2:按周配资 3:按月配资 4:试用配资 5:免息配资
            switch ($type){
                case 1:
                    $config  = day_config();
                    $data['multiple'] = intval($multiple); // 倍率
                    if(isset($config['day_rate'][$data['multiple']])){//不在设置的倍率范围内
                        $data['rate'] = $config['day_rate'][$data['multiple']];
                    }else{
                        $this->error('参数出错，请联系管理员');
                    }
                    $data['total']  = 1;
                    $data['loss_warn'] =  $config['day_loss'][0]; // 预警线
                    $data['loss_close'] = $config['day_loss'][1];// 止损线
                    $mmax=count($config['day_position']);
                    $data['position']=isset($config['day_position'][$data['multiple']])?$config['day_position'][$data['multiple']]:$config['day_position'][$mmax];
                    break;
                case 2:
                    $config  = week_config();
                    $data['multiple'] = $multiple; // 倍率
                    if(isset($config['week_rate'][$data['multiple']])){//不在设置的倍率范围内
                        $data['rate'] = $config['week_rate'][$data['multiple']];
                    }else{
                        $this->error('参数出错，请联系管理员');
                    }
                    $data['total']  = 1;
                    $data['loss_warn'] =  $config['week_loss'][0]; // 预警线
                    $data['loss_close'] = $config['week_loss'][1];// 止损线
                    $mmax=count($config['week_position']);
                    $data['position']=isset($config['week_position'][$data['multiple']])?$config['week_position'][$data['multiple']]:$config['week_position'][$mmax];
                    break;
                case 3:
                    $config  = month_config();
                    $data['multiple'] = intval($multiple); // 倍率
                    if(isset($config['month_rate'][$data['multiple']])){//不在设置的倍率范围内
                        $data['rate'] = $config['month_rate'][$data['multiple']];
                    }else{
                        $this->error('参数出错，请联系管理员!');
                    }
                    $data['total']  = intval($borrow_duration);
                    $data['loss_warn'] =  $config['month_loss'][0]; // 预警线
                    $data['loss_close'] = $config['month_loss'][1];// 止损线
                    $mmax=count($config['month_position']);
                    $data['position']=isset($config['month_position'][$data['multiple']])?$config['month_position'][$data['multiple']]:$config['month_position'][$mmax];
                    break;
                case 4:
                     $check=$db->trycheck($mid,4);
                    if(!empty($check)){
                        $this->error('您只能试用一次');
                    }
                    $config  = explode('|',config('trial_set'));
                    $data['loss_warn'] =  0; // 预警线 这里借用了免费配资的避免为空 其实无意义
                    $data['loss_close'] = 0;// 止损线 这里借用了免费配资的避免为空 其实无意义
                    $data['position'] = 100;
                    $deposit_money= $config[0];
                    $borrow_duration= $config[2];
                    $data['rate']=0;
                    $data['multiple'] = 1; // 倍率
                    $data['total']  = $config[0];
                    $data['borrow_money']=$config[1] * 100;
                    break;
                case 5:
                    $config  = free_config();
                    $multiple=$config['free_set'][0];
                    $borrow_duration=$config['free_set'][1];
                    $data['rate']=0;
                    $data['multiple'] = intval($multiple); // 倍率
                    $data['total']  = 1;
                    $data['loss_warn'] =  $config['free_loss'][0]; // 预警线
                    $data['loss_close'] = $config['free_loss'][1];// 止损线
                    $mmax=count($config['day_position']);
                    $data['position']=isset($config['day_position'][$data['multiple']])?$config['day_position'][$data['multiple']]:$config['day_position'][$mmax];
                    break;
                case 6:
                    $check=$db->trycheck($mid,6);
                    if(!empty($check)){
                        $this->error('您只能模拟一次');
                    }
                    $config  = free_config();
                    $data['loss_warn'] =  $config['free_loss'][0]; // 预警线 这里借用了免费配资的避免为空 其实无意义
                    $data['loss_close'] = $config['free_loss'][1];// 止损线 这里借用了免费配资的避免为空 其实无意义
                    $data['position']=$config['day_position'][1];
                    $data['rate']=0;
                    $data['multiple'] = 1; // 倍率
                    $data['total']  = 1;
                    $data['deposit_money']=config('web_site_mock')*500000;
                    $data['borrow_money']=config('web_site_mock')*500000;
                    $data['borrow_duration']=30;
                    $data['borrow_interest']=0;
                    $data['order_id'] = generate_rand_str(15, 3);
                    $data['sort_order'] = 1;//配资收费批次
                    if(time()>mktime(14,45)){
                        $data['trading_time'] = 1;
                    }else{
                        $data['trading_time'] = 0;
                    }
                    $data['create_time'] = time();
                    $data['type'] = 6;
                    $data['member_id'] = $mid;
                    $data['init_money'] = $data['borrow_money'] + $data['deposit_money'];// 初始资金金额
                    $borrow_res=Db::name("stock_borrow")->insert($data);
                    if(!$borrow_res){
                        $this->error("提交失败");
                    }else{
                        $memberInfo = MemberModel::getMemberInfoByID($data['member_id']);
                        $adminmsg="用户".$memberInfo['mobile']."申请了模拟盘配资";
                        //sendsms_to_admin($adminmsg);
                        $this->success("提交成功，已通知管理员审核", '/member');
                    }
                    break;
                default:
                    $this->error('类型出错，请重新输入！');
            }
            $data['deposit_money'] = $deposit_money * 100;
            if($type!=4){
                $money_max = $money_range[1]*$multiple; //最高配资金额
                $data['borrow_money'] = $data['deposit_money'] * $data['multiple']; // 配资金额
            }else{
                $money_max = $money_range[1]; //最高配资金额
            }
            $data['init_money'] = $data['borrow_money'] + $data['deposit_money'];// 初始资金金额
            $money=$data['init_money']/100;
            if($money>intval($money_max)){
                $this->error('最多只能配资'.$money_max.'元');
            }
            $data['order_id'] = generate_rand_str(15, 3);
            $data['trading_time'] = intval($trading_time);
            $data['type'] = $type;
            $data['member_id'] = $mid;
            $data['borrow_duration'] = $borrow_duration; // 操盘期限
			$type==3 && $data['total'] = $data['borrow_duration'];
			$data['sort_order'] = 1;//配资收费批次
			$result = $db->createStock($data);
			if(!$result['status']){
				$this->error($result['msg']);
			}else{
                $memberInfo = MemberModel::getMemberInfoByID($data['member_id']);
              /*  $adminmsg='用户{$var}申请了配资';
                $params  = $memberInfo['mobile'];
                send_sms_member($adminmsg,$params);*/
                $var = $memberInfo['mobile'];
                //$content = \think\Config::get('sms_template')['stock_handle_applySave'];
                $contentarr  = getconfigSms_status(['name'=>'stock_handle_applySave']);
                $content = str_replace(array("#var#"),array($var), $contentarr['value']);
                if($contentarr['status']==1){
                        sendsms_mandao('', $content, '');
                }
                    $this->success($result['msg'], '/member');


			}
		}

		
	}

?>