<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author gs101
// +----------------------------------------------------------------------
namespace app\statistics\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\stock\model\Borrow;
use think\Db;
use think\Config;
class Mock extends Admin{
    //模拟盘统计
    public function index(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $map = $this->getMap();
        $map['sb.status']=2;
        $order = $this->getOrder();
        $info=Borrow::get_end_mock($map,$order);
        if(empty($_SERVER["QUERY_STRING"])){
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],0,-
                5)."_export";
        }else{
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"],0,-
                5)."_export?".$_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url,'','')
        ];

        return ZBuilder::make('table')
            ->addColumns([ // 批量添加列
                ['mobile', '会员手机号'],
                ['name', '会员名'],
                ['type','配资类型'],
                ['deposit_money','保证金','callback','money_convert'],
                ['borrow_money','配资金额','callback','money_convert'],
                ['init_money','总操盘金额','callback','money_convert'],
                ['return_money','盈亏金额','callback','money_convert'],
                ['return_rate','盈亏比例'],
                ['borrow_duration', '使用时长'],
                ['create_time','申请时间','datetime'],
                ['end_time','终止时间','datetime'],
                ['status', '状态','status', '',['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束']],
            ])

            ->setSearch(['m.mobile' => '会员手机号', 'm.name' => '用户名']) // 设置搜索参数
            ->addTopButton('custem',$btn_excel)
            ->addOrder('return_money,return_rate') // 添加排序
            ->hideCheckbox()
            ->setRowList($info)
            ->fetch();

    }
    public function index_export(){
        // 获取查询条件
        $map = $this->getMap();
        $map['sb.status']=2;
        $order = $this->getOrder();
        $xlsData=Borrow::get_end_mock($map,$order);
        $status_arr = ['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束'];
        foreach ($xlsData as $k=>$v){
            $v['status'] = $status_arr[$v['status']];
            $v['end_times'] = date('Y-m-d H:i',$v['end_time']);
            $v['create_times']=date('Y-m-d H:i',$v['create_time']);
        }

        $title="资金变动记录";
        $arrHeader = array('会员手机号','会员名','配资类型','保证金','配资金额','总操盘金额','盈亏金额','盈亏比例','使用时长','申请时间','终止时间','状态');
        $fields = array('mobile','name','type','deposit_money','borrow_money','init_money','return_money','return_rate','borrow_duration','create_times','end_times','status');
        export($arrHeader,$fields,$xlsData,$title);

    }
    public function  mock_in_use(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $map = $this->getMap();
        $map['sb.status']=1;
        $order = $this->getOrder();
        $info=Borrow::get_end_mock($map,$order);
        if(empty($_SERVER["QUERY_STRING"])){
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],0,-
                5)."_export";
        }else{
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"],0,-
                5)."_export?".$_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url,'','')
        ];

        return ZBuilder::make('table')
            ->addColumns([ // 批量添加列
                ['mobile', '会员手机号'],
                ['name', '会员名'],
                ['type','配资类型'],
                ['deposit_money','保证金','callback','money_convert'],
                ['borrow_money','配资金额','callback','money_convert'],
                ['init_money','总操盘金额','callback','money_convert'],
                ['return_money','盈亏金额','callback','money_convert'],
                ['return_rate','盈亏比例'],
                ['borrow_duration', '操盘期限'],
                ['create_time','申请时间','datetime'],
                ['end_time','终止时间','datetime'],
                ['status', '状态','status', '',['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束']],
            ])
            ->setSearch(['m.mobile' => '会员手机号', 'm.name' => '用户名']) // 设置搜索参数
            ->addTopButton('custem',$btn_excel)
            ->addOrder('return_money,return_rate') // 添加排序
            ->hideCheckbox()
            ->setRowList($info)
            ->fetch();
    }
    public function mock_in_use_export(){
        // 获取查询条件
        $map = $this->getMap();
        $map['sb.status']=1;
        $order = $this->getOrder();
        $xlsData=Borrow::get_end_mock($map,$order);
        $status_arr = ['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束'];
        foreach ($xlsData as $k=>$v){
            $v['status'] = $status_arr[$v['status']];
            $v['end_times'] = date('Y-m-d H:i',$v['end_time']);
            $v['create_times']=date('Y-m-d H:i',$v['create_time']);
        }

        $title="未结束的模拟盘";
        $arrHeader = array('会员手机号','会员名','配资类型','保证金','配资金额','总操盘金额','盈亏金额','盈亏比例','操盘期限','申请时间','终止时间','状态');
        $fields = array('mobile','name','type','deposit_money','borrow_money','init_money','return_money','return_rate','borrow_duration','create_times','end_times','status');
        export($arrHeader,$fields,$xlsData,$title);

    }

}