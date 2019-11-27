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
use  app\money\model\Withdraw as WithdrawModel;
use  app\member\model\Bank as BankModel;
use app\money\model\Money as Money;
use app\stock\model\Borrow as Borrow;
use think\Request;
use think\Db;
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
        $money['account'] =bcdiv($money['account'],100,2);
        $money['operate_account'] = bcdiv($money['operate_account'],100,2);
        $money['operate_account'] = bcdiv($money['operate_account'],100,2);
        $banks = BankModel::getBank(MID);

        if(empty($banks)) ajaxmsg('您未绑定银行卡，请先绑定银行卡',0);

        $data['money'] = $money;
        $data['banks'] = $banks;
        $data['default_bank'] = $banks[0];
        $data['bankSetting'] =  preg_replace('/\|img/', '',config("web_bank"));

        ajaxmsg('线下提现信息',1,$data);

    }
    /*
     * 操作提现操作
     */
    public function doWithdraw()
    {

        $data = $this->request->post();
        $data['mid'] = MID;
        $result = $this->validate($data, "Withdraw.create");
        if(true !== $result){
            ajaxmsg($result,0);
            //$this->error($result);
        }
        if($data['money']<0){
            ajaxmsg('提现金额错误！',0);
        }
        $money_res=Db::name('money')->where(['mid'=>MID])->find();
        if(empty($money_res['account'])){
            ajaxmsg('查询账户资金出错！',0);
        }
        if(isset($money_res['account'])&&$money_res['account']<$data['money']){
            ajaxmsg('提现金额已经大于可用余额！',0);
        }
        $withdraw_info=Db::name('money_withdraw')
            ->where(['mid'=>MID])
            ->where(['status'=>0])
            ->find();
        if(!empty($withdraw_info)){
            ajaxmsg('您已有提现申请，请耐心等待审核。',0);
        }
        $c = Db::name('member')->where(["id"=>MID])->find();
        if(Hash::check((string)$data['paywd'], $c['paywd'])){
            $res = WithdrawModel::saveData($data);
        }else{
            ajaxmsg('支付密码错误',0);
        }
        if($res['status']){
            ajaxmsg('提现申请已提交，请耐心等待审核',1);
        }else{
            ajaxmsg('提现申请提交失败',0);
        }
    }
}