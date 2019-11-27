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
	class Trial extends Home
	{
		public function index()
		{
			$this->assign('title', '免费体验-股票配资');
            $mid = is_member_signin();
            if(!$mid){
                $mid=null;
                //$this->error('请登陆后进行申请', URL('/login'));
            }
            $account = Db::name('money')->where('mid',$mid)->value('account');

			$setting = explode('|',config('trial_set'));
			$deposit = $setting[0];
			$gainMoney = $setting[1];
			$duration = $setting[2];
			$this->assign('duration',$duration);
			$this->assign('deposit',$deposit);
			$this->assign('gainMoney',$gainMoney);
            $this->assign('account',$account/100);
			return $this->fetch();
		} 
	}
?>