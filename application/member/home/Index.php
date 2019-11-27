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

namespace app\member\home;
use app\money\model\Money as Money;
use app\stock\model\Borrow as Borrow;
/*
 * 前台首页控制器
 * @package app\member\home
 */
class Index extends Common
{
   /**
    * 首页
    * @return [type] [description]
    */
    public function index()
    {
    	$money = Money::getMoney(MID);
    	//$money = Db('money')->where(['mid'=>MID])->find();
    	$this->assign('money', $money);
    	$this->assign('member_auth', session('member_auth'));

    	$borrow = Borrow::getBorrowinfo(MID, 3);
    	$this->assign('borrow', $borrow);
        $this->assign('active', 'index');
        return $this->fetch(); // 渲染模板
    }
}