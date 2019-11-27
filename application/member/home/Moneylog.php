<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author 伊冰华 <2851336094@qq.com>
// +----------------------------------------------------------------------
namespace app\member\home;
use app\money\model\Record;
class Moneylog extends Common
{
    /**
     * 资金明细
     * @return [type] [description]
     */
    public function index(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        if(isset($_REQUEST['keyword'])){
            $searchurl2='&_search_field=type&keyword='.$map['type'][1];
            $this->assign('keyword', $_REQUEST['keyword']);
        }else{
            $searchurl2="";
            $this->assign('keyword', 0);
        }
        $this->assign('searchurl2', $searchurl2);
        if(isset($_REQUEST['type'])){
            $searchurl1='&_filter_time2=r.create_time&_filter_time_from='.$map['r.create_time'][1][0].'&_filter_time_to='.$map['r.create_time'][1][1].'&type='.$_REQUEST['type'];
            $this->assign('type', $_REQUEST['type']);
        }else{
            $this->assign('type', 0);
            $searchurl1="";
        }
        $this->assign('searchurl1', $searchurl1);
        if(isset($map['r.create_time'][1])){
            $map['r.create_time'][1][0]=strtotime($map['r.create_time'][1][0]);
            $map['r.create_time'][1][1]=strtotime($map['r.create_time'][1][1]);
        }

        $map['mid']=MID;
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = Record::getAll($map, $order);
        $this->assign('data_list', $data_list);
        $this->assign('active', 'moneylog');
        $this->assign('time_0', date('Y-m-d',time()));
        $this->assign('time_1', date('Y-m-d',time()-86400*7));
        $this->assign('time_2', date('Y-m-d',time()-86400*15));
        $this->assign('time_3', date('Y-m-d',time()-86400*30));
        $this->assign('time_4', date('Y-m-d',time()-86400*60));
        $from_time = $this->request->get("_filter_time_from") ?: "";
        $to_time = $this->request->get("_filter_time_to") ?: "";
        $this->assign("from_time", $from_time);
        $this->assign("to_time", $to_time);
        return $this->fetch(); // 渲染模板
    }
}