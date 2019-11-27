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

namespace app\agents\admin;
use app\admin\controller\Admin;
use think\Db;
class Index extends Admin{
	 /**
     * 首页
     * @author 路人甲乙
     * @return mixed
     */
    public function index()
    {
		 return $this->fetch(); // 渲染模板
	}
	 /**
     * 邀请记录
     * @author 路人甲乙
     * @return mixed
     */
    public function invite()
    {
		 return $this->fetch(); // 渲染模板
	}
	 /**
     * 提现激励
     * @author 路人甲乙
     * @return mixed
     */
    public function withdraw()
    {
		 return $this->fetch(); // 渲染模板
	}
}