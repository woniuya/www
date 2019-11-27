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

namespace app\index\controller;

use app\member\model\Member;
use app\member\model\Bank as BankModel;
use think\helper\Hash;
use app\common\controller\Common;
use think\Db;
use util\Tree;
use think\request;
/**
 * 前台公共控制器
 * @package app\index\controller
 */
class Home extends Common
{
    /**
     * 初始化方法
     * @author 路人甲乙
     */
    protected function _initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            $this->error('站点已经关闭，请稍后访问~');
        }
        $logo_attach_id = config('web_site_front_end_logo');
        $logoPaths = Db::name('admin_attachment')->where('id',$logo_attach_id)->value('path');

        $this->assign('logoPaths',$logoPaths);
        // 获取菜单
        $this->getNav();
        // 获取客服
        //虚拟累计配资人数

        $web_virtual_borrow_num = config('web_virtual_borrow_num');
        $borrow_count =Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow`  GROUP BY member_id ) aa');
        $borrow_count =  $borrow_count[0]['nums']+$web_virtual_borrow_num;
        $this->assign('borrow_count',$borrow_count);

        //虚拟累计操盘资金
        $web_stock_operation_amount = config('web_stock_operation_amount');
        $operate_account = Db::name('money')->where('status=1')->sum('operate_account');
        $operate_account = money_convert($operate_account)+$web_stock_operation_amount;
        $this->assign('operate_account',$operate_account);

        $this->assign('support', $this->getSupport());
        // 用户是否登录
        $this->assign("mid", is_member_signin());
        $this->assign('member_auth', session('member_auth'));

        //用户站内信息
        $map['mid'] = is_member_signin();
        // 数据列表
        $msg_num = Db::name('member_message')->where($map)->count();
        $unread_num = Db::name('member_message')->where(['mid'=>is_member_signin(),'status'=>0])->count();
        $this->assign('msg_num', $msg_num);
        $this->assign('unread_num', $unread_num);
        $web_site_front_end_logo = get_file_path(config('web_site_front_end_logo'));
        $this->assign('web_site_front_end_logo', $web_site_front_end_logo);

//      获取用户认证信息
        $datalist=member::getMemberInfoByID($map['mid']);
        $this->assign("is_verified", $datalist['id_auth']);
        $this->assign("head_img", $datalist['head_img']); //用户头像
        (Hash::check((string)substr($datalist['mobile'], -6), $datalist['paywd']))?$this->assign('is_setpaywd', 0):$this->assign('is_setpaywd', 1);
        $banks = BankModel::getBank($map['mid']);
        empty($banks)?$this->assign('isset_banks', 0):$this->assign('isset_banks', 1);
    }

    /**
     * 获取导航
     * @author 路人甲乙
     */
    private function getNav()
    {
        $list_nav = Db::name('cms_nav')->where('status', 1)->column('id,tag');

        foreach ($list_nav as $id => $tag) {
            $data_list = Db::view('cms_menu', true)
                ->view('cms_column', ['name' => 'column_name'], 'cms_menu.column=cms_column.id', 'left')
                ->view('cms_page', ['title' => 'page_title'], 'cms_menu.page=cms_page.id', 'left')
                ->where('cms_menu.nid', $id)
                ->where('cms_menu.status', 1)
                ->order('cms_menu.sort,cms_menu.pid,cms_menu.id')
                ->select();

            foreach ($data_list as &$item) {
                if ($item['type'] == 0) { // 栏目链接
                    $item['title'] = $item['column_name'];
                    $item['url'] = url('cms/column/index', ['id' => $item['column']], false);
                } elseif ($item['type'] == 1) { // 单页链接
                    $item['title'] = $item['page_title'];
                    $item['url'] = url('cms/page/detail', ['id' => $item['page']], false);
                } else {
                    if ($item['url'] != '#' && substr($item['url'], 0, 4) != 'http') {
                        $item['url'] = url($item['url'],'' ,false);
                    }
                }
            }
            $this->assign($tag, Tree::toLayer($data_list));
        }
    }

    /**
     * 获取在线客服
     * @author 路人甲乙
     */
    private function getSupport()
    {
        return Db::name('cms_support')->where('status', 1)->order('sort')->select();
    }
}
