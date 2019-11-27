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
namespace app\stock\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\stock\model\Borrow;
use app\market\model\Position;
use app\market\model\StockSubAccount;
use app\stock\model\Subaccount;
use app\stock\model\SubMoneyRecord;
use app\market\model\Grid;
use think\Db;
class Operate extends Admin{
    /*
     * 持仓查询
     */
    public function index(){
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='p.id desc';
        }
        $position= new Position();
        // 读取数据
        $data=$position->get_position();
        foreach ($data as $v){
            Db::name('stock_position')->where(['id'=>$v['id']])->update($v);
        }
        //$data_list = Db::name('stock_position')->where($map)->where(['buying'=>0])->order($order)->paginate($listRows = 20);
        $data_list = Db::view('stock_position p')
            ->view('stock_subaccount s','sub_account','s.id=p.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where($map)
            ->where(['p.buying'=>0])
            ->order($order)
            ->paginate($listRows);

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
        //自定义按钮（风控设置）
        $btn_risk = [
            'title' => '风控设置',
            'icon'  => 'fa fa-fw fa-balance-scale',
            'href'  => url('/stock/subaccount/risk', ['id' => '__sub_id__'])
        ];
        $btn_close = [
            'title' => '强制卖出',
            'icon'  => 'fa fa-fw fa-trash-o',
            'class' => 'btn btn-xs btn-default ajax-get confirm',
            'data-title' => '真的要卖出平仓吗？',
            'data-tips' => '请确认此股票已到了平仓线！',
            'href'  => url('close', ['id' => '__sub_id__','code'=>'__gupiao_code__','count'=>'__canbuy_count__'])
        ];
        return ZBuilder::make('table')
            ->setPageTitle('股票持仓') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_position') // 设置数据表名
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addRightButton('risk', $btn_risk) // 添加子账户风控设置按钮
            ->addRightButton('close', $btn_close) // 强制卖出
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
//                ['soruce', '证券来源'],
//                ['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                //['count', '证券数量'],
                //['stock_count', '实际持仓数量'],
                //['canbuy_count', '可卖数量'],
                ['stock_count', '持仓数量'],
                ['buy_average_price', '买入均价'],
                ['now_price', '当前价'],
                ['market_value', '最新市值'],
                ['ck_profit', '参考浮动盈亏'],
                ['profit_rate', '盈亏比例'],
                ['jiyisuo', '交易所'],
                ['right_button', '操作', 'btn'],
            ])

            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function index_export(){
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='p.id desc';
        }

        $xlsData  = Db::view('stock_position p')
            ->view('stock_subaccount s','sub_account','s.id=p.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where(['p.buying'=>0])
            ->where($map)
            ->order($order)
            ->paginate($listRows);

        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        // $status_arr=['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束'];
        /*   foreach ($xlsData as $k=>$v){
               //$v['type']=$type_arr[$v['type']];
               $v['status']=$status_arr[$v['status']];
               $v['verify_time']=date('Y-m-d h:i',$v['verify_time']);
               $v['end_time']=date('Y-m-d h:i',$v['end_time']);

           }*/
        $title="持仓查询";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券代码','证券名称','持仓数量','买入均价','当前价','最新市值','参考浮动盈亏','盈亏比例','交易所');
        $fields = array('sub_id','mobile','sub_account','lid','gupiao_code','gupiao_name','stock_count','buy_average_price','now_price','market_value','ck_profit','profit_rate','jiyisuo');
        export($arrHeader,$fields,$xlsData,$title);
    }
    /*
     * 资金流水
     */
    public function money_record()
    {
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($order)){
            $order='d.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $model=new SubMoneyRecord();
        $data_list=$model->get_record($map,$order,$listRows);
        $page = $data_list->render();
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
        return ZBuilder::make('table')
            ->setPageTitle('资金流水') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_submoney_record') // 设置数据表名
            ->addFilter('sub_id') // 添加筛选
            ->setSearch([ 'sub_id' => '子账户ID']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['sub_account', '子账号'],
                ['affect', '影响金额(元)'],
                ['account','账户余额(元)'],
                ['type', '资金类型'],
                ['info', '详情'],
                ['create_time', '变动日期','datetime'],
            ])
            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    /*
     * 资金流水导出excel表
     *
     */
    public function money_record_export(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($order)){
            $order='d.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $model=new SubMoneyRecord();
        $xlsData=$model->get_record($map,$order,$listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['create_time']=date('Y-m-d h:i',$value['create_time']);
        };
        $title="资金流水";
        $arrHeader = array('子账户ID','子账户','影响金额','账户余额','资金类型','详情','变动日期');
        $fields = array('sub_id','sub_account','affect','account','type','info','create_time');
        export($arrHeader,$fields,$xlsData,$title);
    }

    /*
     * 强制平仓.
     */
    public function close(){
        if(!yan_time()){$this->error('非交易时间');};
        $req=request();
        $id=$req::instance()->param('id');//子账户id
        $code=$req::instance()->param('code');
        $count = $req::instance()->param('count');
        if($count==0){
            $this->error("可卖数量为零，委托失败");
        }
        $trust_info=Db::name("stock_trust")
            ->where(['sub_id'=>$id])
            ->where(['gupiao_code'=>$code])
            ->where('add_time','>=',mktime(9,30,0))
            ->find();
        if(!empty($trust_info)){
            $this->error("用户已委托卖出此股票，不能重复委托");
        }
        $borrow=new Borrow();
        $res=$borrow->savesell($id,$code,$count,$sys=1);
        if(isset($res['status'])&&isset($res['status'])===1){
            $this->success($res['message']);
        }else{
            $this->error($res['message']);
        }
    }

    /*
     * 历史成交
     */
    public function history_deal(){
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
            ->view('member m','mobile','m.id=s.uid','left')
            ->where('d.status','<>','0')
            ->where($map)
            ->order($order)->paginate($listRows);

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
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
                //['soruce', '证券来源'],
                //['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['deal_date', '成交日期','date'],
                ['deal_time', '成交时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['deal_no', '成交编号'],
                ['deal_price', '成交价格'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['flag2', '买卖标志'],
                ['status', '状态说明'],
            ])
            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function history_deal_export(){
        // 获取查询条件
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='d.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;

        $xlsData  = Db::view('stock_deal_stock d')
            ->view('stock_subaccount s','sub_account','s.id=d.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where('d.status','<>','0')
            ->where($map)
            ->order($order)->paginate($listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['deal_date']=date('Y-m-d',$value['deal_date']);
        };
        $title="历史成交";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券代码','证券名称','成交日期','成交时间','委托编号','委托价格','委托数量','成交编号','成交价格','成交数量','成交金额','买卖标志','状态说明');
        $fields = array('sub_id','mobile','sub_account','lid','gupiao_code','gupiao_name','deal_date','deal_time','trust_no','trust_price','trust_count','deal_no','deal_price','volume','amount','flag2','status');
        export($arrHeader,$fields,$xlsData,$title);
    }
    /*
     * 历史委托
     */
    public function history_trust(){
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;

        if(empty($map['trust_date'][1][0])){
            $beginday=date('Ymd',time()-2592000);//30天前
        }else{
            $beginday=date('Ymd',strtotime($map['trust_date'][1][0]));
        }
        if(empty($map['trust_date'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['trust_date'][1][1]));
        }
        $data_list = Db::view('stock_trust t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where($map)
            ->order($order)
            ->paginate($listRows);
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
            ->setPageTitle('历史委托') // 设置页面标题
            ->setTableName('stock_trust') // 设置数据表名
            ->addTimeFilter('trust_date', [$beginday, $endday]) // 添加时间段筛选
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
                //['soruce', '证券来源'],
                //['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['trust_date', '委托日期','date'],
                ['trust_time', '委托时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['flag2', '买卖标志'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['cancel_order_count', '撤单数量'],
                ['status', '状态说明'],
            ])
            ->hideCheckbox()
            ->addTopButton('custem',$btn_excel)
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function history_trust_export(){
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        $xlsData = Db::view('stock_trust t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where($map)
            ->order($order)
            ->paginate($listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['trust_date']=date('Y-m-d',$value['trust_date']);
        };
        $title="历史委托";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券代码','证券名称','委托日期','委托时间','委托编号','委托价格','委托数量
','买卖标志','成交数量','成交金额','撤单数量','状态说明');
        $fields = array
        ('sub_id','mobile','sub_account','lid','gupiao_code','gupiao_name','trust_date','trust_time','trust_no','trust_price','trust_count','flag2','vol
ume','amount','cancel_order_count','status');
        export($arrHeader,$fields,$xlsData,$title);
    }

    /*
     * 当日委托
     */
    public function trust(){
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        // 读取数据
        $time=strtotime(date('y-m-d',time()));
        $data_list = Db::view('stock_trust t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where(['trust_date'=>$time])
            ->where($map)
            ->order($order)
            ->paginate($listRows);
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
            ->setPageTitle('当日委托') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_trust') // 设置数据表名
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
                //['soruce', '证券来源'],
                //['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['trust_date', '委托日期','date'],
                ['trust_time', '委托时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['flag2', '买卖标志'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['cancel_order_count', '撤单数量'],
                ['status', '状态说明'],
            ])

            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function trust_export(){
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        $time=strtotime(date('y-m-d',time()));
        $xlsData = Db::view('stock_trust t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where(['trust_date'=>$time])
            ->where($map)
            ->order($order)->paginate($listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['trust_date']=date('Y-m-d',$value['trust_date']);
        };

        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        // $status_arr=['-1'=>'待审核','0'=>'未通过','1'=>'操盘中','2'=>'已结束'];
        /*   foreach ($xlsData as $k=>$v){
               //$v['type']=$type_arr[$v['type']];
               $v['status']=$status_arr[$v['status']];
               $v['verify_time']=date('Y-m-d h:i',$v['verify_time']);
               $v['end_time']=date('Y-m-d h:i',$v['end_time']);

           }*/
        $title="当日委托";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券来源','证券账户','证券代码','证券名称','委托日期','委托时间','委托编号','委托价格','委托数量','买卖标志','成交数量','成交金额','撤单数量','状态说明');
        $fields = array('sub_id','mobile','sub_account','lid','soruce','login_name','gupiao_code','gupiao_name','trust_date','trust_time','trust_no','trust_price','trust_count','flag2','volume','amount','cancel_order_count','status');
        export($arrHeader,$fields,$xlsData,$title);
    }
    /*
     * 实盘暂时未成交委托
     */
    public function temp(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $data_list = Db::view('stock_temp t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where("trust_date",">=",time()-36000)
            ->where(["deal_no"=>null])
            ->where($map)
            ->order($order)
            ->paginate($listRows);
        // 分页数据
        $page = $data_list->render();
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
        $btn_demp_edit = [
            'title' => '',
            'icon'  => 'fa fa-fw fa-hand-o-right',
            'class' => 'btn btn-xs btn-default ajax-get confirm',
            'href'  => url('temp_edit', ['id' => '__id__']),
            'data-title' => '再次提交',
            'data-tips' => '您确定需要再次提交实盘交易吗？'
        ];
        return ZBuilder::make('table')
            ->setPageTitle('当日暂时未成交委托') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_temp') // 设置数据表名
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
                //['soruce', '证券来源'],
                //['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['trust_date', '委托日期','date'],
                ['trust_time', '委托时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['cancel_order_count', '撤单数量'],
                ['status', '状态说明'],
                ['right_button', '操作', 'btn']
            ])
            ->hideCheckbox()
            ->addRightButtons('delete') // 批量添加右侧按钮
            ->addRightButton('custom', $btn_demp_edit) // 添加再提交按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPageTips('如果委托一直未成交请检查确认并手动交易', 'danger')
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function temp_export(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $xlsData = Db::view('stock_temp t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where("trust_date",">=",time()-36000)
            ->where(["deal_no"=>null])
            ->where($map)
            ->order($order)
            ->paginate($listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['trust_date']=date('Y-m-d',$value['trust_date']);
        };
        $title="当日暂时未成交委托";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券代码','证券名称','委托日期','委托时间','委托编号','委托价格','委托数量','成交数量','成交金额','撤单数量','状态说明');
        $fields = array
        ('sub_id','mobile','sub_account','lid','gupiao_code','gupiao_name','trust_date','trust_time','trust_no','trust_price','trust_count','volume','amount','cancel_order_count','status');
        export($arrHeader,$fields,$xlsData,$title);
    }
    //将委托再次提交实盘交易
    public function temp_edit(){
        $res=request();
        $id=intval($res->param('id'));
        if (empty($id)) {$this->error('参数错误',url('temp'));}
        $ret=StockSubAccount::checkGreenSparrow();
        if($ret===false){
            echo "系统出错";
            exit;
        }
        if($ret['status']==0){
            echo $ret['message'];
            exit;
        }
        $t_res = Db::name('stock_temp')
            ->where(['id'=>$id])
            ->find();
        $time=strtotime(date('Y-m-d',time()));
        if($t_res['add_time']<$time){
            $this->error('此委托已过再次委托时限',url('temp'));
        }
        if(time()>=mktime()){

        }
        $etype=Borrow::market_type($t_res['gupiao_code']);
        if($etype==5){$this->error('请检查卖出的股票代码是否错误',url('temp'));}
        $otype=3;
        //flag1==1 卖出
        if($t_res['flag1']===1){
            $otype=2;//1买入 2卖出
        }elseif($t_res['flag1']===0){
            $otype=1;//1买入 2卖出
        }else{
            $this->error('委托类型出错',url('temp'));
        }
        if($otype===1){
            $money=$t_res['trust_count']*$t_res['trust_price'];
            $m_res=[];
            if(config('web_real_api')==1){
                $m_res=gs('queryTradeData',[$t_res['broker_id'],1]);
            }
            if(config('web_real_api')==2){
                $res=json_decode(Grid::grid_funds($t_res['broker_id']),true);
                $m_res['1']['1']=null;
                $m_res['1']['2']=$res["TotalAvailableAmount"];
                $m_res['1']['3']=null;
                $m_res['1']['4']=$res["TotalAvailableAmount"];
                $m_res['1']['5']=$res["TotalMarketValue"];
                $m_res['1']['6']=$res["TotalAssets"];
                $m_res['1']['7']=null;
            }

            if($m_res[1][2]<$money){
                $this->error('可用余额不足',url('temp'));
            }
        }
        $trade_id=$t_res['broker_id'];
        $data=[];
        if(config('web_real_api')==1) {
            $ptype=5;//5市价委托(上海五档即成剩撤/ 深圳五档即成剩撤)
            $data = gs('commitOrder', [$trade_id,$t_res['gupiao_code'],$t_res['trust_count'],$etype,$otype,$ptype,$t_res['trust_price']]);
        }
        if(config('web_real_api')==2){
            if($otype==2){
                $otype=1;//0->买入 1->卖出 2->融资买入 3->融券卖出 4->买券还券 5->卖券还款 6->现券还券 7->担保品买入 8->担保品卖出
            }else{
                $otype=0;
            }
            $ptype=4;//4市价委托(上海五档即成剩撤/ 深圳五档即成剩撤)
            $data=Grid::grid_order($otype,$ptype,$t_res['gupiao_code'],$t_res['trust_price'],$t_res['trust_count'],$trade_id);
        }

        if($data){
            $this->success('提交成功!',url('temp'));
        }else{
            $this->error('提交失败!',url('temp'));
        }
    }

    /*
     * 当日成交
     */
    public function deal(){
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='st.id desc';
        }
        // 读取数据
        $time=strtotime(date('y-m-d',time()));
        $data_list = Db::view('stock_deal_stock st')
            ->view('stock_subaccount s','sub_account','s.id=st.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where('st.status','<>','0')
            ->where(['deal_date'=>$time])
            ->where($map)
            ->order($order)->paginate($listRows = 20);
        // 分页数据
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

        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setPageTitle('当日成交') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_deal_stock') // 设置数据表名
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
                //['soruce', '证券来源'],
                //['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['deal_date', '成交日期','date'],
                ['deal_time', '成交时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['deal_no', '成交编号'],
                ['deal_price', '成交价格'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['flag2', '买卖标志'],
                ['status', '状态说明'],
            ])

            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function deal_export(){
        // 获取查询条件
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='st.id desc';
        }
        $time=strtotime(date('y-m-d',time()));
        $xlsData = Db::view('stock_deal_stock st')
            ->view('stock_subaccount s','sub_account','s.id=st.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where('st.status','<>','0')
            ->where(['deal_date'=>$time])
            ->where($map)
            ->order($order)->paginate($listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['deal_date']=date('Y-m-d',$value['deal_date']);
        };
        $title="当日成交";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券代码','证券名称','成交日期','成交时间','委托编号','委托价格','委托数量','成交编号','成交价格','成交数量','成交金额','买卖标志','状态说明');
        $fields = array('sub_id','mobile','sub_account','lid','gupiao_code','gupiao_name','deal_date','deal_time','trust_no','trust_price','trust_count','deal_no','deal_price','volume','amount','flag2','status');
        export($arrHeader,$fields,$xlsData,$title);
    }
    /*
     * 撤单查询
     */
    public function cancel_order(){
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $data_list = Db::view('stock_trust t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where($map)
            ->where('t.status','=','已撤')
            ->order($order)->paginate($listRows);
        // 分页数据
        $page = $data_list->render();
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
        $btn_close = [
            'title' => '撤单',
            'icon'  => 'fa fa-fw fa-trash-o',
            'class' => 'btn btn-xs btn-default ajax-get confirm',
            'data-title' => '真的要撤单吗？',
            'data-tips' => '请确认此股票要撤单吗！',
            'href'  => url('cancel_order_c', ['id' => '__sub_id__','trust_no'=>'__trust_no__'])
        ];
        return ZBuilder::make('table')
            ->setPageTitle('撤单查询') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_trust') // 设置数据表名
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            //->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('cancel_order_c', $btn_close)
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
                //['soruce', '证券来源'],
                //['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['trust_date', '委托日期','date'],
                ['trust_time', '委托时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['cancel_order_count', '撤单数量'],
                ['status', '状态说明'],
                ['right_button', '操作', 'btn'],
            ])
            ->hideCheckbox()
            ->replaceRightButton(['status' => '已撤'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>','cancel_order_c')
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    /*
 * 撤销委托单
 * $trade_id 交易券商id号
 * $orderid为在券商服务器上的委托编号(支持批量，分行;间隔，批量时必须是同一个交易所的委托)
 * $type为交易所类型，1深圳交易所 2上海交易所
 */
    public function cancel_order_c()
    {
        if (!yan_time()) {
            $this->error('非交易时间!',url('cancel_order'));
        };
        $req = request();
        $id = $req::instance()->param('id');//子账号id
        $trust_no = $req::instance()->param('trust_no');
        $submodel = new StockSubAccount();
        $res = $submodel->get_account_by_id($id);
        if (empty($res['account_id'])) {
            $this->error('不存在的子账号!',url('cancel_order'));
        }
//        if ($res['uid'] != MID) {
//            return json(['status' => 0, 'message' => '登录超时请重新登录']);
//        }
        $subaccount=new SubAccountMoney();
        Db::startTrans();
        $yes=false;
        $tempinfo=Db::name('stock_temp')->where(['trust_no'=>$trust_no])->lock(true)->find();
        if(!empty($tempinfo)){
            switch (substr($tempinfo['gupiao_code'], 0, 1)) {
                case '0':  $d = 1;  break;
                case '3':  $d = 1;  break;
                case '1':  $d = 1;  break;
                case '2':  $d = 1;  break;
                case '6':  $d = 2;  break;
                case '5':  $d = 2;  break;
                case '9':  $d = 2;  break;
                default:
                    Db::rollback();
                    $this->error('股票代码不对，撤单失败!',url('cancel_order'));
                    break;
            }
            $type = $d;
        }else{
            Db::rollback();
            $this->error('没找到对应委托，撤单失败!',url('cancel_order'));
        }
        $affect_money=Db::name('stock_delivery_order')->where(['trust_no'=>$trust_no])->value('liquidation_amount');
        if($tempinfo['deal_no']==null){
            $trust['status']= "已撤";
            $trust['cancel_order_flag']= "1";
            $trust['cancel_order_count']= $tempinfo['trust_count'];
            $trust_res=Db::name('stock_trust')->where(['trust_no'=>$trust_no])->update($trust);
            $deal_res=Db::name('stock_deal_stock')->where(['trust_no'=>$trust_no])->delete();
            $delivery_res=Db::name('stock_delivery_order')->where(['trust_no'=>$trust_no])->delete();
            $ret=Db::name('stock_temp')->where(['trust_no'=>$trust_no])->delete();
            $position=Db::name('stock_position')->where(['sub_id'=>$id])->where(['gupiao_code'=>$tempinfo['gupiao_code']])->find();
            $subm_res=false;
            $position_in=false;
            if($tempinfo['flag2']=='买入委托'){
                //解冻并转入子账户可用余额
                $subm_res=$subaccount->up_moneylog($id,$affect_money*100,8);
                $position_in=true;
            }elseif($tempinfo['flag2']=='卖出委托'){
                $position['canbuy_count']=$position['canbuy_count']+$tempinfo['trust_count'];
                $position_in=Db::name('stock_position')->where(['sub_id'=>$id])->where(['gupiao_code'=>$tempinfo['gupiao_code']])->update($position);
                $subm_res=$subaccount->up_moneylog($id,$affect_money*100,9);
            }
            if($trust_res&&$deal_res&&$delivery_res&&$ret&&$subm_res&&$position_in){
                $yes=true;
            }else{
                Db::rollback();
                $num=$trust_res.$deal_res.$delivery_res.$ret.$subm_res.$position_in;
                $this->error('撤单失败!',url('cancel_order'));
            }
        }
        $broker = $submodel->get_broker($res['account_id']);
        $trade_id = $broker['id'];
        $res = Db::name('admin_plugin')->where(['name' => "GreenSparrow"])->find();
        if (!empty($res)&&$yes&&$broker['broker']!=-1) {
            $day_re=[];
            if(config('web_real_api')==1) {
                $day_re = gs('queryTradeData', [$broker['id'], 3]);
            }
            if(config('web_real_api')==2){
                $day_re =Grid::grid_category_trust($broker['id']);
            }
            unset($day_re[0]);
            $orderid="";
            foreach ($day_re as $k=>$v){
                if($v[1]==$tempinfo['gupiao_code']&&$v[8]==$tempinfo['trust_count']&&$v[3]==$tempinfo['flag1']){
                    $orderid=$v[9];//得到实盘委托编号
                }
            }
            $data = [];
            if(!empty($orderid)){
                if (config('web_real_api') == 1) {
                    $data = gs('cancelOrder', [$trade_id, $orderid, $type]);
                }
                if (config('web_real_api') == 2) {
                    //将交易所类型转换为网格模式
                    if($type=1){
                        $type=0;
                    }else{
                        $type=1;
                    }
                    $data = grid::grid_cancel($type, $orderid, $trade_id);
                }
            }
            if($data){
                Db::commit();
                $this->success('撤单成功!',url('cancel_order'));
            }else{

            }

        } elseif($yes) {
            if($broker['broker']!=-1){
                Db::rollback();
                $this->error('请安装股票实盘交易插件!',url('cancel_order'));
            }else{
                Db::commit();
                $this->success('撤单成功!',url('cancel_order'));
            }
        }
        Db::rollback();
        $this->error('撤单失败!',url('cancel_order'));
    }
    public function cancel_order_export(){
        $map = $this->getMap();
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='t.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $xlsData = Db::view('stock_trust t')
            ->view('stock_subaccount s','sub_account','s.id=t.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where($map)
            ->where('t.status','=','已撤')
            ->order($order)->paginate($listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['trust_date']=date('Y-m-d',$value['trust_date']);
        };
        $title="撤单查询";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券代码','证券名称','委托日期','委托时间','委托编号','委托价格','委托数量','成交数量','成交金额','撤单数量','状态说明');
        $fields = array
        ('sub_id','mobile','sub_account','lid','gupiao_code','gupiao_name','trust_date','trust_time','trust_no','trust_price','trust_count','volume','amount','cancel_order_count','status');
        export($arrHeader,$fields,$xlsData,$title);
    }
    /*
     * 交割单
     */
    public function delivery(){
        $map = $this->getMap();
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
        $data_list = Db::view('stock_delivery_order d')
            ->view('stock_subaccount s','sub_account','s.id=d.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where(['d.status'=>1])
            ->where($map)
            ->order($order)
            ->paginate($listRows);
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
            ->setPageTitle('交割单') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_delivery_order') // 设置数据表名
            ->addTimeFilter('deal_date', [$beginday, $endday]) // 添加时间段筛选
            ->addOrder('sub_id,gupiao_name') // 添加排序
            ->addFilter('gupiao_code') // 添加筛选
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['sub_id', '子账户ID'],
                ['mobile', '手机号'],
                ['sub_account', '子账户'],
                ['lid', '交易账户名'],
//                ['soruce', '证券来源'],
//                ['login_name', '证券账户'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['deal_date', '成交日期','date'],
                ['business_name', '业务名称'],
                ['deal_price', '成交价格'],
                ['volume', '成交数量'],
                ['amount', '剩余数量'],
                ['residual_quantity', '成交金额'],
                ['liquidation_amount', '清算金额'],
                ['residual_amount', '剩余金额'],
                ['stamp_duty', '印花税'],
                ['transfer_fee', '过户费'],
                ['commission', '净佣金'],
                ['trust_no', '委托编号'],
                ['deal_no', '成交编号'],
            ])

            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }

    public function delivery_export(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($order)){
            $order='d.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $xlsData = Db::view('stock_delivery_order d')
            ->view('stock_subaccount s','sub_account','s.id=d.sub_id','left')
            ->view('member m','mobile','m.id=s.uid','left')
            ->where(['d.status'=>1])
            ->where($map)
            ->order($order)
            ->paginate($listRows);
        foreach($xlsData as $key=>$value){
            $xlsData->items->items[$key]['deal_date']=date('Y-m-d',$value['deal_date']);
        };
        $title="交割单";
        $arrHeader = array('子账户ID','手机号','子账户','交易账户名','证券代码','证券名称','成交日期','业务名称','成交价格','成交数量','剩余数量','成交金额','清算金额','剩余金额','印花税','过户费','净佣金','委托编号','成交编号');
        $fields = array('sub_id','mobile','sub_account','lid','gupiao_code','gupiao_name','deal_date','business_name','deal_price','volume','amount','residual_quantity','liquidation_amount','residual_amount','stamp_duty','transfer_fee','commission','trust_no','deal_no');
        export($arrHeader,$fields,$xlsData,$title);
    }

    /*
     * 资金明细
     */
    public function capital_details(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($order)){
            $order='s.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $data_list =Subaccount::getSubaccountMoney($map,$order,$listRows);
        $page = $data_list->render();
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
        return ZBuilder::make('table')
            ->setPageTitle('资金明细') // 设置页面标题
            ->addTopButton('custem',$btn_excel)
            ->setTableName('stock_list') // 设置数据表名
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addRightButton('edit') // 添加编辑按钮
            ->addColumns([ // 批量添加列
                ['id', '账户ID'],
                ['sub_account', '交易子账户名'],
                ['avail', '可用余额'],
                ['available_amount', '可提现金额'],
                ['freeze_amount', '冻结资金'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
            ])
            ->hideCheckbox()
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function capital_details_export(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($order)){
            $order='s.id desc';
        }
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        $xlsData =Subaccount::getSubaccountMoney($map,$order,$listRows);
        $title="资金明细";
        $arrHeader = array('账户ID','交易子账户名','可用余额','可提现金额','冻结资金','保证金','配资金额');
        $fields = array('id','sub_account','avail','available_amount','freeze_amount','deposit_money','borrow_money');
        export($arrHeader,$fields,$xlsData,$title);
    }
}