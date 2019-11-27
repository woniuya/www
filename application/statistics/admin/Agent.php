<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
// +----------------------------------------------------------------------
namespace app\statistics\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\stock\model\Borrow;
use think\Db;
use think\Config;
class Agent extends Admin{
    //模拟盘统计
    public function index(){

        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
//        $data_list = MemberModel::getAgentList($map,$order);
        $data_list = Db::name('member')
            ->where($map)
            ->where('agent_id <>0')
            ->order($order)
            ->paginate()
            ->each(function($item, $key){
                $info = get_agents_info($item['agent_far']);
                $item['agent_far_name'] = $info['mobile'] ?  $info['mobile'] : config('web_site_title');
                $count = Db::name('agents_back_money')->where(['mid'=>$item['id']])->sum('affect');
                $item['count_profit'] = round($count,2);
                $item['agent_back_rate'] = get_plus_rate($item['id']).'%';
                return $item;
            });
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setTableName('member')
            ->setSearch(['mobile' => '代理商手机号', 'agent_far_name'=>'所属代理','agent_id'=>'代理商等级'],'','',true) // 设置搜索框
            ->addOrder('create_time,id desc') // 添加排序
            ->hideCheckbox()
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '代理商手机号'],
                ['name', '代理商姓名'],
                ['agent_id','代理等级', 'text','',[0=>'普通会员',1=>'一级代理',2=>'二级代理',3=>'三级代理']],
                ['agent_far_name', '上级代理'],
                ['agent_back_rate', '上级返佣比例'],
                ['count_profit', '佣金总收益'],
                ['agent_pro', '代理状态', [0=>'停止', 1=>'正常']],
                ['create_time', '注册时间', 'datetime'],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板

    }
    /*
     * 实盘用户统计
     */
    public function agent_stock(){


        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='d.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        if(empty($map['deal_date'][1][0])){
            $beginday=date('Ymd',time()-2592000);//30天前
        }else{
            $beginday=date('Ymd',strtotime($map['deal_date'][1][0]));
        }
        if(empty($map['deal_date'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['deal_date'][1][1]));
        }
        $data_list = Db::view('stock_deal_stock d')
            ->view('stock_subaccount s','sub_account','s.id=d.sub_id','left')
            ->view('stock_borrow b','deposit_money,borrow_money','b.stock_subaccount_id=s.id','left')
            ->view('member m','name,mobile,agent_far','m.id=b.member_id','left')
            ->where('d.status','<>','0')
            ->where($map)
            ->where("d.soruce !='模拟账户'")
            ->order($order)
            ->paginate($listRows)
            ->each(function($item, $key){
                $info = get_agents_info($item['agent_far']);
                $item['agent_far_name'] = $info['mobile'] ?  $info['mobile'] : config('web_site_title');
                return $item;
            });

        if(empty($_SERVER["QUERY_STRING"])){
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],0,-5)."_export";
        }else{
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"],0,-5)."_export?".$_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url,'','')
        ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setPageTitle('历史成交') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_deal_stock') // 设置数据表名
            ->addTimeFilter('deal_date', [$beginday, $endday]) // 添加时间段筛选
            ->addOrder('sub_id,gupiao_code,gupiao_name') // 添加排序
            ->addFilter('gupiao_code,soruce') // 添加筛选
            ->setSearch([ 'mobile' => '手机号','agent_far_name' => '所属代理商']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['name', '所属代理商'],
                ['agent_far_name', '所属代理商'],
                ['sub_account', '子账户名称'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['gupiao_name', '证券名称'],
                ['trust_price', '成本均价'],
                ['gupiao_code', '证券代码'],
                ['deal_price', '平仓价'],
                ['amount', '成交金额'],
                ['volume', '卖出数量'],
                ['deal_date', '卖出时间','date'],
            ])
            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();

    }
    /*
    * 实盘用户统计
    */
    public function agent_count(){

        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='d.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        if(empty($map['deal_date'][1][0])){
            $beginday=date('Ymd',time()-2592000);//30天前
        }else{
            $beginday=date('Ymd',strtotime($map['deal_date'][1][0]));
        }
        if(empty($map['deal_date'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['deal_date'][1][1]));
        }
        $data_list = Db::view('stock_deal_stock d')
            ->view('stock_subaccount s','sub_account','s.id=d.sub_id','left')
            ->view('stock_borrow b','deposit_money,borrow_money','b.stock_subaccount_id=s.id','left')
            ->view('member m','name,mobile,agent_far','m.id=b.member_id','left')
            ->where('d.status','<>','0')
            ->where($map)
            ->where("d.soruce ='模拟账户'")
            ->order($order)
            ->paginate($listRows)
            ->each(function($item, $key){
                $info = get_agents_info($item['agent_far']);
                $item['agent_far_name'] = $info['mobile'] ?  $info['mobile'] : config('web_site_title');
                return $item;
            });

        if(empty($_SERVER["QUERY_STRING"])){
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],0,-5)."_export";
        }else{
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"],0,-5)."_export?".$_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url,'','')
        ];
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setPageTitle('历史成交') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_deal_stock') // 设置数据表名
            ->addTimeFilter('deal_date', [$beginday, $endday]) // 添加时间段筛选
            ->addOrder('sub_id,gupiao_code,gupiao_name') // 添加排序
            ->addFilter('gupiao_code,soruce') // 添加筛选
            ->setSearch([ 'mobile' => '手机号','agent_far_name' => '所属代理商']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['name', '所属代理商'],
                ['agent_far_name', '所属代理商'],
                ['sub_account', '子账户名称'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['gupiao_name', '证券名称'],
                ['trust_price', '成本均价'],
                ['gupiao_code', '证券代码'],
                ['deal_price', '平仓价'],
                ['amount', '成交金额'],
                ['volume', '卖出数量'],
                ['deal_date', '卖出时间','date'],
            ])
            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();

    }
}