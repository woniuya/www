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

namespace plugins\Statistics;

use app\common\controller\Plugin;
use think\Db;
use app\user\model\User as UserModel;
use think\Config;
/**
 * 系统环境信息插件
 * @package plugins\DevTeam
 * @author 路人甲乙
 */
class Statistics extends Plugin
{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
        'name'        => 'Statistics',
        // 插件标题[必填]
        'title'       => '后台配资统计',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'Statistics.ming.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-users',
        // 插件描述[选填]
        'description' => '在后台首页显示配资统计',
        // 插件作者[必填]
        'author'      => '路人甲乙',
        // 作者主页[选填]
        'author_url'  => 'http://www.lurenjiayi.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.0',
        // 是否有后台管理功能[选填]
        'admin'       => '0',
    ];

    /**
     * @var array 插件钩子
     */
    public $hooks = [
        'admin_index'
    ];

    /**
     * 后台首页钩子
     * @author 路人甲乙
     */
    public function adminIndex()
    {
        $config = $this->getConfigValue();
        $p_total=$trust_day=$num_u_day=$trade_day=$num_a_day=$win=0;
        if ($config['display']) {
            //累计总操盘资金
            $total_operate_account = Db::name('stock_borrow')
                ->where('status>0')
                ->sum('deposit_money+borrow_money');
            $total_operate_account = sprintf("%.2f",($total_operate_account/1000000));

            //累计总股票持仓
            $res = Db::name('stock_position')
                ->where('stock_count','>',0)
                ->select();
            foreach ($res as $k=>$v){
                $p_total+=$v['stock_count']*$v['now_price'];
            }
            $p_total = sprintf("%.2f",($p_total/10000));
            $time=strtotime(date('Y-m-d',time()));
            //当日成交总额
            $trade_day = Db::name('stock_deal_stock')
                ->where('deal_date','>=',$time)
                ->sum('amount');
            //当日成交次数
            $num_a_day =Db::name('stock_deal_stock')
                ->where('deal_date','>=',$time)
                ->count();
            //累计赚取收益
            $ck_profit = Db::name('stock_subaccount_money')->alias('a')
                ->join('stock_subaccount s','s.id=a.stock_subaccount_id','LEFT')
                ->join('member m','m.id=s.uid','LEFT')
                ->where(['m.status'=>1,'a.return_money'=>['>',0]])
                ->sum('a.return_money');
            $ck_profit = sprintf("%.2f",($ck_profit/1000000));

            //累计充值总额
            $r_total = Db::name('money_recharge')
                ->where(['status'=>1])
                ->sum('money');
            $r_total = sprintf("%.2f",($r_total/1000000));
            //总用户数
            $member_count = Db::name('member')
                ->where(['status'=>1])
                ->count();
            /*$yestoday_register = Db::name('member')->query("select count(*) as counts from `lmq_member` where to_days(now()) <= 1 + to_days(`create_time`)");
            $yestoday_register =  $yestoday_register[0]['counts'];
            $this->assign('yestoday_register',$yestoday_register);*/
            $beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
            $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
            $startToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            $begin7day=mktime(0,0,0,date('m'),date('d')-7,date('Y'));
            $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
            $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
            $Yesterday  = Db::name('member')
                ->where('create_time','>=',$beginYesterday)
                ->where('create_time','<=',$endYesterday)
                ->count();
            $new7day  = Db::name('member')
                ->where('create_time','>=',$begin7day)
                ->where('create_time','<=',$endToday)
                ->count();
            $ThisMonth = Db::name('member')
                ->where('create_time','>=',$beginThismonth)
                ->where('create_time','<=',$endThismonth)
                ->count();
            //累计配资人数

            $borrow_count =Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where status=1 GROUP BY member_id ) aa');
            $borrow_count =  $borrow_count[0]['nums'];
            //占比
          // $borrow_proportion = sprintf("%01.2f",($borrow_count/$member_count)*100).'%';

            //盈亏总额
            $win = Db::name('stock_position')
                ->where('stock_count','>',0)
                ->sum('ck_profit');
            //总配资单数
            $stock_nums = Db::name('stock_borrow')
                ->where('status=1')
                ->count('id');
            //配资总额
            $stock_money = Db::name('stock_borrow')
                ->where(['status'=>['>',0]])
                ->sum('borrow_money');
            $stock_money =  sprintf("%.2f",($stock_money/1000000));

            //免息体验数
            $free_counts = Db::name('stock_borrow')
                ->where(['status'=>1,'type'=>5])
                ->count();
            //按天配资数
            $day_counts = Db::name('stock_borrow')
                ->where(['status'=>1,'type'=>1])
                ->count();
            //免费 体验
            $experience_counts = Db::name('stock_borrow')
                ->where(['status'=>1,'type'=>4])
                ->count();
            //模拟操盘
            $simulation_counts = Db::name('stock_borrow')
                ->where(['status'=>1,'type'=>6])
                ->count();
            //按周配资数
            $week_counts = Db::name('stock_borrow')
                ->where(['status'=>1,'type'=>2])
                ->count();
            //按月配资数
            $month_counts = Db::name('stock_borrow')
                ->where(['status'=>1,'type'=>3])
                ->count();
            //昨日登录用户数
            $Yesterday_login_count  = Db::name('member')
                ->where('last_login_time','>=',$beginYesterday)
                ->where('last_login_time','<=',$endYesterday)
                ->count();
            //昨日登录数占比
            //$yesterday_login_proportion =  sprintf("%01.2f",($Yesterday_login_count/$member_count)*100).'%';

            //7天内登录用户数
            $new7day_login_count  = Db::name('member')
                ->where('last_login_time','>=',$begin7day)
                ->where('last_login_time','<=',$endToday)
                ->count();
            //七天内登录数占比
            //$new7day_login_proportion = sprintf("%01.2f",($new7day_login_count/$member_count)*100).'%';

            // 代理商总数
            $agent_counts = Db::name('admin_user')->where('role','in',[2,3,4])->count();
            //1级
            $agent_one_counts = Db::name('admin_user')
                ->where(['status'=>1,'role'=>2])
                ->count();
            //2级
            $agent_two_counts = Db::name('admin_user')
                ->where(['status'=>1,'role'=>3])
                ->count();
            //3ji
            $agent_tree_counts = Db::name('admin_user')
                ->where(['status'=>1,'role'=>4])
                ->count();
            //$this->assign('yesterday_login_proportion',$yesterday_login_proportion);//
            //$this->assign('new7day_login_proportion',$new7day_login_proportion);//
            $this->assign('new7day_login_count',$new7day_login_count);//
            $this->assign('Yesterday_login_count',$Yesterday_login_count);//
            $this->assign('free_counts',$free_counts);//免息体验数
            $this->assign('day_counts',$day_counts);  //按天配资数
            $this->assign('week_counts',$week_counts);//按周配资数
            $this->assign('month_counts',$month_counts);//按月配资数
            //$this->assign('borrow_proportion',$borrow_proportion); //占比
            $this->assign('stock_money',$stock_money);
            $this->assign('win',$win);
            $this->assign('agent_one_counts',$agent_one_counts);
            $this->assign('agent_two_counts',$agent_two_counts);
            $this->assign('agent_tree_counts',$agent_tree_counts);
            $this->assign('borrow_count',$borrow_count);
            $this->assign('new7day',$new7day);
            $this->assign('ThisMonth',$ThisMonth);
            $this->assign('Yesterday',$Yesterday);
            $this->assign('member_count',$member_count);
            $this->assign('r_total',$r_total);
            $this->assign('ck_profit',$ck_profit);
            $this->assign('agent_counts',$agent_counts);
            $this->assign('num_a_day',$num_a_day);
            $this->assign('simulation_counts',$simulation_counts);
            $this->assign('trade_day',$trade_day);
            $this->assign('experience_counts',$experience_counts);
            $this->assign('p_total',$p_total);
            $this->assign('stock_nums',$stock_nums); //配资总单数
            $this->assign('total_operate_account',$total_operate_account); //累计总操盘资金
            $this->fetch('widget', $config);
        }
    }

    /**
     * 安装方法
     * @author 路人甲乙
     * @return bool
     */
    public function install(){
        return true;
    }

    /**
     * 卸载方法必
     * @author 路人甲乙
     * @return bool
     */
    public function uninstall(){
        return true;
    }
}