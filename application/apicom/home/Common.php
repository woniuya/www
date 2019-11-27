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
use think\Db;
use util\Tree;
use app\apicom\model\JWT;
use think\request;

/**
 * 前台公共控制器
 * @package app\apicom\admin
 */
class Common extends Home
{
    /**
     * 初始化方法
     * @author 路人甲乙
     */
    protected function _initialize()
    {
	
        parent::_initialize();
		defined('MID') or define('MID', $this->isLogin());
		
    }

    /**
     * 获取导航
     * @author 路人甲乙
     */
	/**
     * 判断是否登录
     * @return boolean [description]
     */
    protected  function isLogin()
    {
		 //$token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjc4OSwidXNlcm5hbWUiOiJyanM3ODkifQ.ytS-O9sN8X9tAy71C7X9u9Ay4nobv1NblgjsaB8bzNM";
		 $req=request();
		 $token = $this->request->param("token");
		if($token){
			$decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256')); 
			$doHost = $_SERVER['HTTP_HOST'];
			if($doHost == $decoded->doHost){
				return $decoded->uid; 
			}else{
				return 0;
			} 
		}else{
			return 0;
		}
    }
	
}