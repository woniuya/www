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

namespace app\cms\admin;

use app\admin\controller\Admin;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\cms\admin
 */
class Index extends Admin
{
    /**
     * 首页
     * @author 路人甲乙
     * @return mixed
     */
    public function index()
    {
        $this->assign('document', Db::name('cms_document')->where('trash', 0)->count());
        $this->assign('column', Db::name('cms_column')->count());
        $this->assign('page', Db::name('cms_page')->count());
        $this->assign('model', Db::name('cms_model')->count());
        $this->assign('page_title', '仪表盘');
        return $this->fetch(); // 渲染模板
    }
}