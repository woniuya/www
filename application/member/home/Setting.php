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
use app\member\model\Member as MmeberModel;

class Setting extends Common
{

	/**
	 * 个人资料
	 * @return [type] [description]
	 */
	public function index()
	{
		return $this->fetch();
	}

	/**
	 * 设置登录密码
	 */
	public  function setPasswd()
	{
		return $this->fetch();
	}

	/**
	 * 实名认证
	 * @return [type] [description]
	 */
	public function idAuth()
	{
		return $this->fetch();
	}

	/**
	 * 重置手机号
	 * @return [type] [description]
	 */
	public function resetMobile()
	{
		return $this->fetch();
	}

	/**
	 * 修改支付密码
	 * @return [type] [description]
	 */
	public function resetPaywd()
	{
		return $this->fetch();
	}

	/**
	 * 忘记支付密码 找回
	 * @return [type] [description]
	 */
	public function backPaywd()
	{
		return $this->fetch();	
	}
}


?>