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
use app\money\model\Record;
use think\db;
use think\Request;

class Moneylog extends Common
{
    /**
     * 资金明细
     * @return [type] [description]
     */
    public function index(){
        // $post=$this->request->get();
        if(!MID) ajaxmsg('登陆后才能进行查询',0);

        // 获取查询条件
        $map = $this->getMap();

        if(isset($_REQUEST['keyword'])){
            $searchurl2='&_search_field=type&keyword='.$map['type'][1];
            // $this->assign('keyword', $_REQUEST['keyword']);
        }else{
            $searchurl2="";
//            $this->assign('keyword', 0);
        }
        //  $this->assign('searchurl2', $searchurl2);
        if(isset($_REQUEST['type'])){
            $searchurl1='&_filter_time2=r.create_time&_filter_time_from='.$map['r.create_time'][1][0].'&_filter_time_to='.$map['r.create_time'][1][1].'&type='.$_REQUEST['type'];
//            $this->assign('type', $_REQUEST['type']);
        }else{
            // $this->assign('type', 0);
            $searchurl1="";
        }
        //$this->assign('searchurl1', $searchurl1);
        if(isset($map['r.create_time'][1])){
            $map['r.create_time'][1][0]=strtotime($map['r.create_time'][1][0]);
            $map['r.create_time'][1][1]=strtotime($map['r.create_time'][1][1]);
        }
        $map['mid']=MID;
        //$order = $this->getOrder();
        $order = 'r.id desc';
        $page = intval($this->request->param("page"));
        $page = $page ? $page : 1;
        $offset = $page;
        // 数据列表
        // $data_list = Record::getAll($map, $order);
        $data_list = Db::name('money_record')
            ->alias('r')
            ->where($map)
            ->field('r.*')
            ->order($order)
            ->page($offset,10)
            ->select();

        foreach ($data_list as $k => $v) {
            $data_list[$k]['happend_time'] = getTimeFormt($v['create_time'],4);
            $data_list[$k]['happend_date'] = getTimeFormt($v['create_time'],5);
            $data_list[$k]['type_name'] = getTypeNameForMoney($v['type']);
            $data_list[$k]['money'] = bcdiv($v['money'],100,2);
            $data_list[$k]['affect'] = bcdiv($v['affect'],100,2);
            $data_list[$k]['account'] = bcdiv($v['account'],100,2);
        }

        ajaxmsg('数据列表',1,$data_list);
    }
}