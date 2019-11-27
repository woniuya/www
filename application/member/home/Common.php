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

use app\index\controller\Home;
use think\Db;
use util\Tree;

/**
 * 前台公共控制器
 * @package app\member\admin
 */
class Common extends Home
{
    /**
     * 初始化方法
     * @author 张继立 <404851763@qq.com>
     */
    protected function _initialize()
    {
        parent::_initialize();

        defined('MID') or define('MID', $this->isLogin());

		$user = get_agents_info(MID);
		$agent_id = $user['agent_id'];

		 $this->assign('isAgent', $agent_id);
        $this->assign('member_auth', session('member_auth'));
        $this->assign('mockset', config('web_mock_set'));
        $this->assign('path_info', $_SERVER['PATH_INFO']);
    }

    /**
     * 判断是否登录
     * @return boolean [description]
     */
    protected  function isLogin()
    {
    	// 判断是否登录
        if ($mid = is_member_signin()) {
            // 已登录
            return $mid;
        } else {
            // 未登录
            $this->redirect('/login');
        }
    }



    
}