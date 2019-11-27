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

namespace app\cms\home;

use app\cms\model\Page as PageModel;

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
        // 更新阅读量
        PageModel::where('id', $id)->setInc('view');

        $android_download_page = url('android_qrcode');
        $this->assign('page_info', $info);
        $this->assign('android_download_page', $android_download_page);
        $this->assign('activeMenuId', $activeMenu['id']);

        return $this->fetch($info['template']); // 渲染模板
    }

    public function mobileDownload(){
        $this->assign("inWechat", $this->isWechat());
        return $this->fetch('download/mobile');
    }

    protected function isWechat()
    {
        if( !preg_match('/MicroMessenger/i', strtolower($_SERVER['HTTP_USER_AGENT'])) ) {
            return 0;
        }
        return 1;
    }

    public function android_qrcode()
    {
        $url = url('/androiddownload');
        plugin_action('Qrcode/Qrcode/generate', [$url, APP_PATH.'qrdownload.png']);
    }
}