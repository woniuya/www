<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\apicom\home;
use  app\member\home\Common;
use  app\money\model\Recharge as ChargeModel;
use think\Db;
/**
 * 前台首页控制器
 * @package app\money\home
 */
class Recharge extends Common
{
    protected function _initialize()
    {
        parent::_initialize();
    }
   /**
    * 首页
    * @return [type] [description]
    */
    public function index()
    {	

    	$money = \app\money\model\Money::getMoney(MID);
        $money['account'] = bcdiv($money['account'],100,2);
        $money['operate_account'] = bcdiv($money['operate_account'],100,2);
        $money['operate_account'] = bcdiv($money['operate_account'],100,2);

		$data['money'] = $money;
		$data['account'] = config('web_site_account');
	
		ajaxmsg('用户账户信息',1,$data);

    }
	public function editCharge()
    {	

		$money = \app\money\model\Money::getMoney(MID);
    	$account=Db::name("admin_bank")->where(['status'=>1])->select();
        foreach($account as $k=>$v){
            if(!empty($v['image'])){
                $account[$k]['image'] =  get_file_path($v['image']);
            }
        }
		$data['money'] = $money;
		$data['offline'] = config('web_site_account');
		$data['account'] = $account;
        $data['kftime'] = config('web_site_service_time');
        $data['kfphone'] = config('web_site_telephone');
		
		ajaxmsg('用户账户信息',1,$data);

    }
/*
 * 充值操作
 */
	public function doCharge()
		{
		
			
			$data = $this->request->Post();
    		$line_bank = '';
            $receipt_img='';
            $charge_type_id = 0;
            $form_name = $data['form_name'];
    		$money = $data['money'];
    		if($money <= 0){
				ajaxmsg('充值金额必须大于0',0);
    		}
    		$type = $data['transfer']; // transfer 线下转账支付
    		if($type == 'transfer'){
    		    $card_id=$data['cardno'];
    			$line_bank = '卡号：'.$card_id.'；时间：'.date('Y-m-d',time());
    		}


    		$order_no = ChargeModel::saveData($money, MID, $type, $line_bank,$receipt_img,$charge_type_id,$form_name);
    		
    		if($type == 'transfer'){
                $var = Db::name('member')->where('id',MID)->value('mobile');
                $contentarr  = getconfigSms_status(['name'=>'stock_offline']);
                $content = str_replace(array("#var#","#amount#"),array($var,$money), $contentarr['value']);
                if($contentarr['status']==1){
                    sendsms_mandao('', $content, '');
                }
                ajaxmsg('充值已提交，请耐心等待审核',1);
				ajaxmsg('充值已提交，请耐心等待审核',1);
    		}else{
				ajaxmsg('走接口才能完成',0);
    		}

		}
}