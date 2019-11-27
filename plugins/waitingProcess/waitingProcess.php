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

namespace plugins\waitingProcess;

use app\common\controller\Plugin;
use think\Db;
use app\user\model\User as UserModel;
use think\Config;

/**
 * 系统环境信息插件
 * @package plugins\DevTeam
 * @author 路人甲乙
 */
class waitingProcess extends Plugin
{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
        'name'        => 'waitingProcess',
        // 插件标题[必填]
        'title'       => '后台首页代办事项',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'waitingProcess.ming.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-users',
        // 插件描述[选填]
        'description' => '在后台首页显示代办事项',
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
        if ($config['display']) {
            //线下充值待处理
            $transfer_nodo = Db::name('money_recharge')
                ->where(['status'=>0,'type'=>'transfer'])
                ->count();
            //实名认证待处理
            $idAuth_noDo = Db::name('member')
            ->where(['id_auth'=>0,'id_card'=>['<>',''],'name'=>['<>','']])
                ->count();
            //配资申请
            $borrow_apply=  Db::name('stock_borrow')
                ->where('status<0')
                ->count();
            //提现待处理
            $withdraw_noDo = Db::name('money_withdraw')
                ->where(['status'=>0])
                ->count();
            //免息体验申请
            $experience_apply=  Db::name('stock_borrow')
                ->where('type=5')
                ->where('status<0')
                ->count();
            //扩大配资申请
            $expansion_Allocation_apply_count =  Db::name('stock_addfinancing')
                ->where('status=0')
                ->count();
            //续期申请
            $renewal_apply_count =  Db::name('stock_renewal')
                ->where('status=0 and type=1')
                ->count();
            //提取盈利申请
            $drawprofit_apply_count =  Db::name('stock_drawprofit')
                ->where('status=0')
                ->count();
            //追加保证金申请
            $addmoney_apply_count =  Db::name('stock_addmoney')
                ->where('status=0')
                ->count();
            //提前终止申请
            $advance_termination_apply_count =  Db::name('stock_renewal')
                ->where('status=0 and type=2')
                ->count();
            //即将到期配资
           /* $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
            $begin3day=mktime(0,0,0,date('m'),date('d')-3,date('Y'));
            $soon_expire_stock_count =  Db::name('stock_borrow')
                ->where("end_time>=$begin3day and end_time<=$endYesterday")
                ->count();*/
            $tis = 3*86400+time();
            $maps['end_time']=['>',time()];
            $maps['type']=['in',[1,2,3]];
            $maps['status']=1;
            $datas =  Db::name('stock_borrow b',true)
                ->where($maps)
                ->where('b.end_time','<=',$tis)
                ->order('b.id desc')
                ->select();

            $soon_expire_stock_count = count($datas);
            $expire_stock_count =  Db::name('stock_borrow')
                ->where("end_time<=".time()." and status=1")
                ->count();
            $this->assign('expire_stock_count',$expire_stock_count);
            $this->assign('soon_expire_stock_count',$soon_expire_stock_count);
            $this->assign('advance_termination_apply_count',$advance_termination_apply_count);
            $this->assign('addmoney_apply_count',$addmoney_apply_count);
            $this->assign('drawprofit_apply_count',$drawprofit_apply_count);
            $this->assign('renewal_apply_count',$renewal_apply_count);
            $this->assign('expansion_Allocation_apply_count',$expansion_Allocation_apply_count);
            $this->assign('experience_apply',$experience_apply);
            $this->assign('withdraw_noDo',$withdraw_noDo);
            $this->assign('borrow_apply',$borrow_apply);
            $this->assign('idAuth_noDo',$idAuth_noDo);
            $this->assign('transfer_nodo',$transfer_nodo);
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