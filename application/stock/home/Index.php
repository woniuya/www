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

use app\index\controller\Home;
use app\member\home\Common;
use app\money\model\Money;
use app\member\model\Member;
use think\Request;

/**
 * 申请配资控制器
 * @package app\stock\home
 */
class Index extends Home
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        // 配资金额范围
        $this->assign("money_range", explode('|', config('money_range')));

        // 账户余额，未登录显示 0
        $account_money = is_member_signin() ? Money::getMoney(is_member_signin())['account'] : 0;
        $this->assign("account_money",money_convert($account_money));
    }

    /**
     * 免息配资
     * @return mixed
     */
    public function free()
    {
        $DivideInto =explode('|', config('free_set'))[2].'%';;
        $day_position = json_encode(config('day_position'));
        $this->assign('free_loss', explode('|', config('free_loss'))); //预警线|平仓线（按天）
        $this->assign('free_set', explode('|', config('free_set'))); //免息设置
        $this->assign('DivideInto', $DivideInto);
        $this->assign('day_position', $day_position);
        return $this->fetch();
    }
    //返回是否登录
    public function islogin(){
        return is_member_signin();
    }
    /**
     * 按天配资
     * @return mixed
     */
    public function day()
    {
        $day_position = json_encode(config('day_position'));
        $day_use_time = explode('|', config('day_use_time')); // 按天操盘期限
        $max_use_time = max($day_use_time); // 最大使用天数
        $min_use_time = min($day_use_time); // 最短使用时间

        $this->assign('day_loss', explode('|', config('day_loss'))); //预警线|平仓线（按天）
        $this->assign('day_rate', config('day_rate')); // 倍率及费率
        $this->assign('day_use_time', $day_use_time);
        $this->assign('max_use_time', $max_use_time);
        $this->assign('min_use_time', $min_use_time);
        $this->assign('day_position', $day_position);

        return $this->fetch();
    }

    /**
     * 按周配资
     * @return mixed
     */
    public function week()
    {
        $week_use_time = explode('|', config('week_use_time')); // 按周操盘期限
        $max_use_time = max($week_use_time); // 最大使用时间
        $min_use_time = min($week_use_time); // 最短使用时间
        //单股持仓比例
        $week_position = json_encode(config('week_position'));
        $this->assign('week_loss', explode('|', config('week_loss'))); //预警线|平仓线（按月）
        $this->assign('week_rate', config('week_rate')); // 倍率及费率
        $this->assign('week_use_time', $week_use_time);
        $this->assign('max_use_time', $max_use_time);
        $this->assign('min_use_time', $min_use_time);
        $this->assign('week_position', $week_position);

        return $this->fetch();
    }

    /**
     * 按月配资
     * @return mixed
     */
    public function month()
    {
        $month_use_time = explode('|', config('month_use_time')); // 按月操盘期限
        $max_use_time = max($month_use_time); // 最大使用时间
        $min_use_time = min($month_use_time); // 最短使用时间
        //单股持仓比例
        $month_position = json_encode(config('month_position'));
        $this->assign('month_loss', explode('|', config('month_loss'))); //预警线|平仓线（按月）
        $this->assign('month_rate', config('month_rate')); // 倍率及费率
        $this->assign('month_use_time', $month_use_time);
        $this->assign('max_use_time', $max_use_time);
        $this->assign('min_use_time', $min_use_time);
        $this->assign('month_position', $month_position);

        return $this->fetch();
    }

    /*
     * 实盘交易平台操盘协议
     */
    public function protocol(){

        $res=$this->islogin();
        $info=file_get_contents(config('data_backup_path')."contract.txt");
        $arr=array(
            "name"=>config('set_site_company_name'),//甲方：委托人
            "dizhi"=>config('web_site_address'),//甲方地址
            "borrowMoney"=>"--",//委托金额
            "borrow_duration"=>"--",//配资周期
            "type"=>"天",//配资类型
            "investor"=>"--",//总利息
            "user_name"=>"--",//乙方用户名
            "real_name"=>"--",//
            "idcard"=>"--",//
            "WEB_URL"=>http().$_SERVER["HTTP_HOST"],//
            "web_name"=>config('web_site_title'),//
            "rate"=>"--",//
            "deposit_money"=>"--",//
            "add_time"=>"--",//
            "end_time"=>"--",//
        );
        $member=new Member();
        if($res===0){
            foreach ($arr as $k =>$v){
                $info=str_replace("[".$k."]",$v,$info);
            }
        }else{
            $minfo=$member->getMemberInfoByID($res);
            $arr['user_name']=isset($minfo['mobile'])?$minfo['mobile']:"138********";
            $arr['real_name']=isset($minfo['name'])?$minfo['name']:"***";
            $arr['idcard']=isset($minfo['id_card'])?$minfo['id_card']:"******************";
            foreach ($arr as $k =>$v){
                $info=str_replace("[".$k."]",$v,$info);
            }
        }
        $this->assign('info',$info);
        return $this->fetch();
    }


}
