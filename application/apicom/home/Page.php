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

use app\apicom\model\Page as PageModel;

/**
 * 前台单页控制器
 * @package app\cms\admin
 */
class Page extends Common
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 单页详情
     * @param null $id 单页id
     * @author 路人甲乙
     * @return mixed
     */
    public function detail($id = null)
    {

        /**
         * know my page id
         * get nav link id from cms_menu throw page id
         */
        $current_nav = db("cms_nav")->where(['tag' => 'cms_nav'])->find();
        $activeMenu = db("cms_menu")->where(["page" => $id, "nid" => $current_nav['id']])->find();

        $info = PageModel::where('status', 1)->find($id);
        $info['url']  = url('cms/page/detail', ['id' => $info['id']]);
        $info['tags'] = explode(',', $info['keywords']);
        $info['create_time'] = getTimeFormt($info['create_time'],1);
        //$info['content'] = htmlspecialchars_decode($info['content']);
        // 更新阅读量
        PageModel::where('id', $id)->setInc('view');
		ajaxmsg('获取成功',1,$info);

    }
    public function getPageList(){

        $page = intval($this->request->param("page"));
        $page = $page ? $page : 1;
        $offset = $page;

        $page_list = new PageModel();
        $list = $page_list->getTitleList($offset);
		foreach ($list as $key=>$val){
            $list[$key]['create_time'] = format_time($val['create_time']);
			//$list[$key]['content'] = htmlspecialchars($val['content']);
        }
        ajaxmsg('获取成功',1,$list);
    }
}