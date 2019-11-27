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

use app\index\controller\Home;
use app\member\home\Common;
use app\money\model\Money;
use app\member\model\Member;
use think\Request;

/**
 * 申请配资控制器
 * @package app\stock\home
 */
class Stock extends Home
{

    /**
     * 免息配资
     * @return mixed
     */
    public function free_m()
    {

        $money_range =  explode('|', config('money_range'));
        $account_money = is_member_signin() ? Money::getMoney(is_member_signin())['account'] : 0;

        $data['free_loss'] = explode('|', config('free_loss'));
        $data['free_set'] = explode('|', config('free_set'));
        $data['account_money'] = bcdiv($account_money,100,2);
        $data['money_range'] = $money_range;
        $data['position'] = [];

        ajaxmsg('免息配资',1,$data);
    }

    /**
     * 免费体验设置
     *
     * @return void
     */
    public function trial()
    {
        $account_money = is_member_signin() ? Money::getMoney(is_member_signin())['account'] : 0;

        $data['account_money'] = bcdiv($account_money,100,2);
        $data['setting'] = explode('|',config('trial_set'));
        ajaxmsg('免费体验', 1, $data);
    }

    /**
     * 按天配资
     * @return mixed
     */
    public function day()
    {


        $money_range =  explode('|', config('money_range'));
        $account_money = is_member_signin() ? Money::getMoney(is_member_signin())['account'] : 0;

        $day_use_time = explode('|', config('day_use_time')); // 按天操盘期限
        $max_use_time = max($day_use_time); // 最大使用天数
        $min_use_time = min($day_use_time); // 最短使用时间
        $data['day_loss'] =  explode('|', config('day_loss')); //预警线|平仓线（按天）
        $data['day_rate'] =  config('day_rate'); // 倍率及费率
        $rate_c = config('day_rate');
        $rate_arr = array();
        foreach ($rate_c as $k => $v){
            array_push($rate_arr,$k);
        }
        $data['day_rate_a']  = $rate_arr;

        $data['day_use_time'] = $day_use_time;
        $data['max_use_time'] = $max_use_time;
        $data['min_use_time'] = $min_use_time;
        $data['account_money'] = $account_money;
        $data['account_money'] = bcdiv($account_money,100,2);
        $data['money_range'] = $money_range;
        $data['position'] = config('day_position');

        ajaxmsg('按天配资',1,$data);

    }

    /**
     * 按周配资
     * @return mixed
     */
    public function week()
    {

        $money_range =  explode('|', config('money_range'));
        $account_money = is_member_signin() ? Money::getMoney(is_member_signin())['account'] : 0;

        $week_use_time = explode('|', config('week_use_time')); // 按周操盘期限
        $max_use_time = max($week_use_time); // 最大使用时间
        $min_use_time = min($week_use_time); // 最短使用时间

        $data['week_loss'] = explode('|', config('week_loss'));  //预警线|平仓线（按月）
        $data['week_rate'] = config('week_rate'); // 倍率及费率
        $rate_c = config('week_rate');
        $rate_arr = array();
        foreach ($rate_c as $k => $v){
            array_push($rate_arr,$k);
        }
        $data['week_rate_a']  = $rate_arr;

        $data['week_use_time'] = $week_use_time;
        $data['max_use_time'] = $max_use_time;
        $data['min_use_time'] = $min_use_time;
        $data['account_money'] = bcdiv($account_money,100,2);
        $data['money_range'] = $money_range;
        $data['position'] = config('week_position');

        ajaxmsg('按周配资',1,$data);

    }

    /**
     * 按月配资
     * @return mixed
     */
    public function month()
    {

        $money_range =  explode('|', config('money_range'));
        $account_money = is_member_signin() ? Money::getMoney(is_member_signin())['account'] : 0;

        $month_use_time = explode('|', config('month_use_time')); // 按月操盘期限
        $max_use_time = max($month_use_time); // 最大使用时间
        $min_use_time = min($month_use_time); // 最短使用时间


        $data['month_loss'] = explode('|', config('month_loss')); //预警线|平仓线（按月）
        $data['month_rate'] = config('month_rate'); // 倍率及费率

        $rate_c = config('month_rate');
        $rate_arr = array();
        foreach ($rate_c as $k => $v){
            array_push($rate_arr,$k);
        }
        $data['month_rate_a']  = $rate_arr;

        $data['month_use_time'] = $month_use_time;
        $data['max_use_time'] = $max_use_time;
        $data['min_use_time'] = $min_use_time;
        $data['account_money'] = bcdiv($account_money,100,2);
        $data['money_range'] = $money_range;
        $data['position'] = config('month_position');

        ajaxmsg('按月配资',1,$data);

    }

    /*
     * 实盘交易平台操盘协议
     */
    public function protocol(){

        $res=MID;
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

        ajaxmsg('实盘交易平台操盘协议',1,$info);

    }

}