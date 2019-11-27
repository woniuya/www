<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------

namespace app\money\home;

use  app\member\home\Common;
use  app\money\model\Withdraw as WithdrawModel;
use  app\member\model\Bank as BankModel;
use  app\member\model\Member;
use app\member\home\Bank;
use  think\Db;
use  think\helper\Hash;
/**
 * 前台首页控制器
 * @package app\money\home
 */
class Withdraw extends Common
{
   /**
    * 首页
    * @return [type] [description]
    */
    public function index()
    {


    	$money = \app\money\model\Money::getMoney(MID);
    	$this->assign('money', $money);
        $member_info=Member::getMemberInfoByID(MID);
        if(isset($member_info['id_auth'])){
            empty($member_info['id_auth'])?$this->assign('id_auth_st', 0):$this->assign('id_auth_st', $member_info['id_auth']);
           // $this->assign('id_auth_st', $member_info['id_auth']);
            // $this->error("您还没有实11名认证，无法提现。",url('@member/profile/realname'));
        }
        $banks = BankModel::getBank(MID);
        $bank =  new Bank;
        $bank_logo = $bank->bankres('logo');
         foreach ($banks as $key=>$val){
            $banks[$key]['logo']= $bank_logo[$val['bank']];
         }
        isset($banks[0]['card'])?$this->assign('banks', $banks):$this->assign('banks', 0);
        $this->assign('default_bank', $banks[0]);
        $this->assign('active', 'charge');
        $this->assign('web_bank', config('web_bank'));
        return $this->fetch();
    }
    public function doWithdraw()
    {
    	if($this->request->isPost())
    	{
            $data = $this->request->post();
            $data['mid'] = MID;
            $result = $this->validate($data, "Withdraw.create");
            if(true !== $result){
                $this->error($result);
            }
            if($data['money']<0){
                $this->error('提现金额错误！');
            }
            $money_res=Db::name('money')->where(['mid'=>MID])->find();
            if(empty($money_res['account'])){
                $this->error('查询账户资金出错！');
            }
            if(isset($money_res['account'])&&$money_res['account']/100<$data['money']){
                $this->error('提现金额已经大于可用余额！');
                exit;
            }
            $withdraw_info=Db::name('money_withdraw')
                ->where(['mid'=>MID])
                ->where(['status'=>0])
                ->find();
            if(!empty($withdraw_info)){
                $this->error('您已有提现申请，请耐心等待审核。');
            }
            $c = Db::name('member')->where(["id"=>MID])->find();
            if(Hash::check((string)$data['paywd'], $c['paywd'])){
                $res = WithdrawModel::saveData($data);
            }else{
                $this->error('支付密码错误');
            }
    		if($res['status']){
                $contentarr  = getconfigSms_status(['name'=>'stock_withdraw']);
                $content = str_replace(array("#var#","#amount#"),array($c['mobile'],$data['money']), $contentarr['value']);
                if($contentarr['status']==1){
                    sendsms_mandao('',$content,'');
                }
    			$this->success('提现申请已提交，请耐心等待审核', url('@member/index/index'));
    		}else{
    			$this->error('提现申请提交失败');
    		}

    	}
    }
}