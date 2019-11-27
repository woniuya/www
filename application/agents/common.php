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

namespace app\agents\home;
use app\index\controller\Home;
use think\Db;
use util\Tree;
class Common extends Home{
    /**
     * 初始化方法
     */
    protected function _initialize(){
        parent::_initialize();
        defined('MID') or define('MID',$this->isLogin() );
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
            $this->redirect('member/publics/login');
        }
    }
}