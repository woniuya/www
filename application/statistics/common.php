<?php
use think\Db;

/*
* 邀请用户个数统计
*/
if (!function_exists('get_users_m')) {
    function get_users_m($mid)
    {
        $count_m = Db::name('member_invitation_relation')->where('mid', $mid)->count();
        $count_m = $count_m ? $count_m : 0;
        return $count_m;
    }
}
/*
* 获取用户返佣收益
*/
if (!function_exists('agents_back_money')) {
    function get_back_money($mid)
    {
        $back_money = Db::name('agents_back_money')->where('mid', $mid)->sum('affect');
        $back_money = $back_money ? $back_money : 0.00;
        return round($back_money,2);
    }
}
/*
*获取用户盈利
*/
if (!function_exists('agents_profit_money')) {
    function agents_profit_money($mid)
    {
        $far_user =  get_agents_info($mid);
        $arr = getSonAgentArr($mid);
        $arr_str= implode(',',$arr);
        if($arr_str !='') $map =  "affect_mid in ({$arr_str}) and mid = {$far_user['agent_far']} ";
        else return 0;

        $back_money = Db::name('agents_back_money')
            ->where($map)
            ->sum('affect');

        $back_money = $back_money ? $back_money : 0.00;
        return round($back_money,2);
    }
}
/*
* 返回当前用户返佣比例
*/
if (!function_exists('agents_back_rate')) {
    function agents_back_rate($mid)
    {
        $user =  get_agents_info($mid);
        if($user['agent_rate']){
            $rate = $user['agent_rate'];
        }else{
            if($user['agent_id'])  $rate = config('agent_back_rate');
            else $rate = config('member_back_rate');
        }
        return $rate;
    }
}
/*
 * 获取当前用户返佣比例
 */
if (!function_exists('get_plus_rate')) {
    function get_plus_rate($mid)
    {
        $user = get_agents_info($mid);
        if($user['agent_id'] ==1){
            $rate = agents_back_rate($mid);
        }elseif($user['agent_id'] ==2){
            $agent_1 = get_agents_info($user['agent_far']);
            $agent_1_rate =  $agent_1['agent_rate'] ? $agent_1['agent_rate'] : config('agent_back_rate');
            $rate = $user['agent_rate'] * $agent_1_rate / 100;
        }elseif($user['agent_id'] ==3){
            $agent_1 = get_agents_info($user['agent_far']);
            $agent_1_rate =  $agent_1['agent_rate'] ? $agent_1['agent_rate'] : config('agent_back_rate');
            $agent_2 = get_agents_info($agent_1['agent_far']);
            $rate = $user['agent_rate'] * $agent_2['agent_rate'] * $agent_1_rate / 10000;
        }else{
            $rate = config('member_back_rate');
        }

        return $rate;
    }
}
/*
* 获得当前用户返佣来源
*/
if (!function_exists('agents_back_come')) {
    function agents_back_come($mid)
    {
        $user = Db::name('member')->field('id,mobile')->where('id', $mid)->find();
        $note = '用户'.$user['mobile'].'名下';
        return $note;

    }
}
/*
* 获取当前用户代理类型 代理级别
*/
if (!function_exists('get_agents_level')) {
    function get_agents_level($mid)
    {
        $user = Db::name('member')->field('id,agent_id')->where('id', $mid)->find();
        return $user['agent_id'];
    }
}
/*
* 返回代理商信息
*/
if (!function_exists('get_agents_info')) {
    function get_agents_info($mid)
    {
        $user = Db::name('member')->field('id,agent_id,agent_pro,agent_far,agent_rate,mobile')->where('id', $mid)->find();
        return $user;
    }
}

/*
* 返回返佣截止时间
*/
if (!function_exists('getEndBack')) {
    function getEndBack($time)
    {
        $time_n = time();
        $rate_time = config('member_back_time') ? config('member_back_time') : 0;
        $agent_time = $time + ($rate_time * 86400 * 30);
        if($agent_time > $time_n){
            $end_time = getTimeFormt($agent_time,5);
        }else{
            $end_time = '已到期';
        }
        return $end_time;
    }
}

/**
 * 时间格式转换
 */

if (!function_exists('getTimeFormt')) {

    function getTimeFormt($time, $type = 0)
    {
        if ($type == 0) $f = "m-d H:i";
        else if ($type == 1) $f = "Y-m-d H:i";
        else if ($type == 3) $f = "m-d H:i";
        else if ($type == 4) $f = "H:i:s";
        else if ($type == 5) $f = "Y-m-d";
        else if ($type == 6) $f = "H:i:s";
        return date($f, $time);
    }
}

//调取所有下级树 20 1 2 3
if (!function_exists('getSonAgentArr')) {
    function getSonAgentArr($mid)
    {
        $user = Db::name('member')->field('id,agent_id')->where('agent_far', $mid)->select();
        $arr = array();
        if (!empty($user)){
            foreach ($user as $k=>$v){
                if($v['id']) array_push($arr,$v['id']);
                $user_info = getSonAgentArr2($v['id']);
                foreach ($user_info as $key=>$val){
                    if($val['id']) array_push($arr,$val['id']);
                }
            }
        }
        array_push($arr,$mid);
        return $arr;
    }
}
//调取所有下级树 20 1 2 3
if (!function_exists('getSonAgentArr2')) {
    function getSonAgentArr2($mid)
    {
        $arr = array();
        $user = Db::name('member')->field('id,agent_id')->where('agent_far', $mid)->select();
        return $user;
    }
}

?>