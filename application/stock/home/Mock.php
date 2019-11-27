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
	use think\Db;
	use think\Hook;
	use app\index\controller\Home;
	/**
	 * 免费体验控制器
	 * @package app\stock\home
	 */
	class Mock extends Home
	{
		public function index()
		{
		    $money=config('web_site_mock');
			$this->assign('money', $money);
			$this->assign('title', '模拟操盘-股票配资');
			return $this->fetch();
		} 
	}
?>