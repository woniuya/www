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

use app\member\model\MemberMessage as Msg;
use think\Db;

class Message extends Common
{

    /**
     * 初始化方法
     * @author 路人甲乙
     */
    protected function _initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问',0);
        }

        $req=request();
        $token = $this->request->param("token");

        defined('MID') or define('MID', isLogin($token));

        if(!MID) ajaxmsg('登陆后才能操作',0);
    }

    /**
     * 消息首页
     */
    public function index()
    {

        $status = $this->request->get('t');
        $map = [];
        if (isset($status)) $map['mm.status'] = $status;
        $map['mm.mid'] = MID;
        $order = $this->getOrder();
        empty($order) && $order = 'mm.id desc';
        // 数据列表
        $data= Msg::getAll($map, $order, 12);
        $data->each(function($item, $key){
            $item->create_time =getTimeFormt($item->create_time,7);
        });
        ajaxmsg('获取成功',1,$data);
    }

    /**
     * 阅读消息
     */

    public function read()
    {
        $map = [];
        $message_id = $this->request->post("id");
        $map['id'] = $message_id;
        $map['mid'] = MID;

        $messageModel = new Msg;
        $result = $messageModel
            ->where($map)
            ->update(['status' => 1]);
        if ($result == 1) {
            ajaxmsg('设置为阅读',1);
        } else {
            ajaxmsg('请求数据有误',0);
        }
    }
    /**
     * 批量阅读未读消息
     */
    public function readall(){
        $map = [];
        $mid = MID;
        if(!$mid) ajaxmsg('登陆后才能操作',0);
        $map['mid'] = $mid;
        $messageModel = new Msg;
        $result = $messageModel
            ->where($map)
            ->update(['status' => 1]);
            ajaxmsg('设置为阅读',1);
    }
    /**
     * 站内信页面
     * 显示：最新网站公告
     * 显示：最新系统通知
     */
    public function messageList(){
        $gao = Db::name('cms_document')
            ->alias('c')
            ->join('cms_document_news d','c.id = d.aid')
            ->where('c.cid', 2)
            ->order('c.id desc')
            ->find();

        $messgae = Db::name('member_message')
            ->alias('m')
            ->where('m.mid', 'eq' ,MID)
            ->order('m.id desc')
            ->find();
        //判空
        if(!empty($gao)){
            $gao['create_time'] = getTimeFormt($gao['create_time'],1);
            $data['ggao'] = $gao;
        }

        if(!empty($messgae)){
            $messgae['create_time'] = getTimeFormt($messgae['create_time'],1);
            $data['messgae'] = $messgae;
        }

        // $data['ggao'] = $gao;
        //$data['messa'] = $messgae;

        ajaxmsg('获取成功',1,$data);
    }
}