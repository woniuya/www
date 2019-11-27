<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author menghui
// +----------------------------------------------------------------------

namespace app\member\home;

use app\member\model\MemberMessage as Msg;

class Message extends Common
{
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
        $data_list = Msg::getAll($map, $order, 12);
        $this->assign('data_list', $data_list);
        $this->assign("status", $status);
        $this->assign('active', 'message');

        return $this->fetch();
    }

    /**
     * 阅读消息
     */

    public function read()
    {
        $map = [];
        $message_id = $this->request->get("id/d");
        $map['id'] = $message_id;

        $messageModel = new Msg;
        $result = $messageModel
            ->where('mid', 'eq', MID)
            ->where($map)
            ->update(['status' => 1]);

        if ($result === 1) {
            $this->success("已阅读");
        } else {
            $this->error("请求数据有误");
        }
    }

}