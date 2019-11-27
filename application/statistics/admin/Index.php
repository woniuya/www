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
use app\money\model\Record;
use think\Db;
use think\Config;
use app\market\model\Position;
class Index extends Admin{
    //注册会员统计
    public function index(){
        $req=request();
            $map=$req::instance()->param('map');
            if($map==="888"){
            //注册会员总数
            $total=$day=$trade = 0;
            $total = Db::name('member')
                ->where(['is_del'=>0])
                ->count();
            $time=strtotime(date('Y-m-d',time()));
            //当日注册会员
            $day = Db::name('member')
                ->where(['is_del'=>0])
                ->where('create_time','>',$time)
                ->count();
            //当日交易会员数
            $trade = Db::name('stock_trust')
                ->where('trust_date','>=',$time)
                ->group('sub_id')
                ->count();
             //累计配资总数
                $borrow_count =Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where status=1 GROUP BY member_id ) aa');
                $borrow_count =  $borrow_count[0]['nums'];
             // 代理商总数
           $agent_counts = Db::name('admin_user')->where('role','in',[2,3,4])->count();

                //1级
                $agent_one_counts = Db::name('admin_user')
                    ->where(['status'=>1,'role'=>2])
                    ->count();
                //2级
                $agent_two_counts = Db::name('admin_user')
                    ->where(['status'=>1,'role'=>3])
                    ->count();
                //3ji
                $agent_tree_counts = Db::name('admin_user')
                    ->where(['status'=>1,'role'=>4])
                    ->count();
                // 代理商总数
                $agent_counts = Db::name('admin_user')->where('role','in',[2,3,4])->count();

            return ZBuilder::make('table')
                ->setTemplate('registered')
                ->assign(['total' => $total, 'day' => $day,'trade'=>$trade,'borrow_count'=>$borrow_count,'agent_counts'=>$agent_counts,'agent_one_counts'=>$agent_one_counts,'agent_two_counts'=>$agent_two_counts,'agent_tree_counts'=>$agent_tree_counts,'agent_counts'=>$agent_counts])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('index','map=888').'" width="95%" height="550px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }

    //资金变动记录
    public function  capital(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();

        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        if(empty($_GET['list_rows'])){
            $listRows = 10;
        }else{
            $listRows=$_GET['list_rows'];
        }
        // 数据列表
        $data_list = Record::getAll($map, $order,$listRows);

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


        // 分页数据
        return ZBuilder::make('table')
            ->setSearch(['r.mid'=>'用户ID','m.name' => '姓名', 'm.mobile' => '手机号']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['type', '类型'],
                ['affect', '变动资金'],
                ['account', '账户余额'],
                ['create_time', '时间', 'datetime'],
                ['info', '信息'],
            ])
            ->hideCheckbox()
            ->setTableName('money')
            ->addTopButton('custem',$btn_excel)
            ->addOrder('id,account,affect,create_time')
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }

    public function capital_export(){
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = Record::getAll($map, $order);

        foreach ($xlsData as $k=>$v){
            $v['create_times']=date('Y-m-d H:i',$v['create_time']);
        }

        $title="资金变动记录";
        $arrHeader = array('ID','手机号','姓名','类型','变动资金','账户余额','时间','信息');
        $fields = array('id','mobile','name','type','affect','account','create_times','info');
        export($arrHeader,$fields,$xlsData,$title);

    }

    //充值提现统计
    public function  recharge(){
        $req=request();
        $map=$req::instance()->param('map');//子账户id
        if($map==="888"){
            $r_total=$r_day=$w_total=$w_day=$m_total=$a_total=0;
            //累计充值总额
            $r_total = Db::name('money_recharge')
                ->where(['status'=>1])
                ->sum('money');
            $r_total = money_convert($r_total);

            $time=strtotime(date('Y-m-d',time()));
            //当日充值金额
            $r_day = Db::name('money_recharge')
                ->where(['status'=>1])
                ->where('create_time','>',$time)
                ->sum('money');
            $r_day = money_convert($r_day);
            //累计提现总额
            $w_total = Db::name('money_withdraw')
                ->where(['status'=>1])
                ->sum('money');
            $w_total = money_convert($w_total);
            //当日提现金额
            $w_day = Db::name('money_withdraw')
                ->where(['status'=>1])
                ->where('create_time','>',$time)
                ->sum('money');
            $w_day = money_convert($w_day);
            //会员提现总额
            $m_total= Db::name('money_withdraw')
                ->where('status=1')
                ->sum('money');
            $m_total = money_convert($m_total);
            //代理商提现总额
            $a_total=Db::name('agent_withdraw')
                ->where('status=1')
                ->sum('money');
            $a_total = money_convert($a_total);
            return ZBuilder::make('table')
                ->setTemplate('recharge')
                ->assign(['r_total' => $r_total, 'r_day' => $r_day,'w_total'=>$w_total,'w_day'=>$w_day,'m_total'=>$m_total,'a_total'=>$a_total])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('recharge','map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }
    //股票交易统计
    public function  trade(){
        $req=request();
        $map=$req::instance()->param('map');//子账户id
        if($map==="888"){
            $p_total=$trust_day=$num_u_day=$trade_day=$num_a_day=$win=0;
            //持仓总额
            $res = Db::name('stock_position')
                ->where('stock_count','>',0)
                ->where(['buying'=>0])
                ->select();
            foreach ($res as $k=>$v){
                $p_total+=$v['stock_count']*$v['now_price'];
            }
            $p_total = money_convert($p_total);
            $time=strtotime(date('Y-m-d',time()));
            //当日委托总额
            $trust_day = Db::name('stock_trust')
                ->where('trust_date','>=',$time)
                ->sum('amount');
            $trust_day = money_convert($trust_day);
            //当日委托次数
            $num_u_day =Db::name('stock_trust')
                ->where('trust_date','>=',$time)
                ->count();

            //当日成交总额
            $trade_day = Db::name('stock_deal_stock')
                ->where('deal_date','>=',$time)
                ->where('status','<>','0')
                ->sum('amount');
            $trade_day = money_convert($trade_day);
            //当日成交次数
            $num_a_day =Db::name('stock_deal_stock')
                ->where('deal_date','>=',$time)
                ->where('status','<>','0')
                ->count();

            //盈亏总额
            $win = Db::name('stock_position')
                ->where('stock_count','>',0)
                ->where(['buying'=>0])
                ->sum('ck_profit');
            $win = money_convert($win);
            return ZBuilder::make('table')
                ->setTemplate('trade')
                ->assign(['p_total' => $p_total, 'trust_day' => $trust_day,'num_u_day'=>$num_u_day,'trade_day'=>$trade_day,'num_a_day'=>$num_a_day,'win'=>$win])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('trade', 'map=888').'" width="100%" height="800px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }
    }
    //网站费用统计
    public function  cost(){
        $req=request();
        $map=$req::instance()->param('map');//子账户id
        if($map==="888"){
        $trade_fee=$defer=$defer_y_day=$defer_s_day=$agent_commission=$frozen_deposit=$add_deposit=0;
            //已收交易综合费
            $trade_fee = Db::name('stock_borrow')
                ->where('status','>=',1)
                ->sum('borrow_interest');
            $trade_fee = money_convert($trade_fee);
            //已收递延费
            $defer = Db::name('stock_detail')->where(['status'=>'1'])->sum('receive_interest');
            //代理商返佣
        //    $agent_commission = 0;//暂时留空 后续处理

            $agent_commission =  Db::name('admin_user')
                    ->alias('a')
                    ->join('agent_money_record w','a.id = w.uid','RIGHT')
                    ->where('w.type=1')
                    ->sum('affect');
            $agent_commission=money_convert($agent_commission);
            //保证金总额
            $frozen_deposit = Db::name('stock_borrow')
                ->where(['status'=>1])
                ->sum('deposit_money');
            $frozen_deposit=money_convert($frozen_deposit);
            //追加保证金总额
            $add_deposit = Db::name('stock_addmoney')
                ->where(['status'=>1])
                ->sum('money');
            $add_deposit = money_convert($add_deposit);
            $time=strtotime(date('Y-m-d',time()));
            //当日应收递延费

            $defer_y_arrs = Db::name('stock_borrow')->where(['type'=>'3','status'=>'1','end_time'=>['gt',time()]])->select();
            $interest=0;
            foreach($defer_y_arrs as $k=>$v){
               $arr_int[$k]= Db::name('stock_detail')->where(['borrow_id'=>$v['id'],'sort_order'=>$v['sort_order'],'deadline'=>['gt',time()]])->field('interest')->find();
               $interest +=  $arr_int[$k]['interest'];
            }
            $defer_y_day = $interest;
            //当日实收递延费
            //$defer_s_day = 0;//暂时留空 后续处理
            $startToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endToday = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
       $defer_s_day = Db::name('stock_detail')->where(['status'=>'1','repayment_time'=>[['egt',$startToday],['elt',$endToday],'and']])->sum('interest');

            return ZBuilder::make('table')
                ->setTemplate('cost')
                ->assign([
                    'trade_fee' => $trade_fee,
                    'defer' => $defer,
                    'defer_y_day'=>$interest,
                    'defer_s_day'=>$defer_s_day,
                    'agent_commission'=>$agent_commission,
                    'frozen_deposit'=>$frozen_deposit,
                    'add_deposit'=>$add_deposit,
                ])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('cost','map=888').'" width="100%" height="800px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }
    }
    //会员账户
    public function  member(){
            cookie('__forward__', $_SERVER['REQUEST_URI']);

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
            // 获取查询条件
            $map = $this->getMap();
            $order = $this->getOrder();
            empty($order) && $order = 'create_time,m.id desc';
            if(empty($_GET['list_rows'])){
                $listRows = 10;
            }else{
                $listRows=$_GET['list_rows'];
            }
            $simple = false;
            $config=[];
            $config   = array_merge(Config::get('paginate'), $config);
            $listRows = $listRows ?: $config['list_rows'];
            $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\think\\paginator\\driver\\' . ucwords($config['type']);

            $page  = isset($config['page']) ? (int) $config['page'] : call_user_func([
                $class,
                'getCurrentPage',
            ], $config['var_page']);
            $page = $page < 1 ? 1 : $page;
                $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);
            $total   = Db::table('lmq_member')
                ->alias('m')
                ->where($map)
                ->order($order)
                ->join('lmq_money o','m.id = o.mid')->count();
            $results = Db::table('lmq_member')
                ->alias('m')
                ->where($map)
                ->order($order)
                ->field('m.id as ids,m.*,o.*')
                ->join('lmq_money o','m.id = o.mid')->page($page, $listRows)->select();
             foreach ($results as $k =>$v){
                 //处理中提现金额
                 $w_money_1 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>0,'mid'=>$v['ids']])->sum('money');
                 //处理中提现手续费
                 $w_fee_1 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>0,'mid'=>$v['ids']])->sum('fee');
                 //提现成功提现金额
                 $w_money_2 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>1,'mid'=>$v['ids']])->sum('money');
                 //提现成功提现手续费
                 $w_fee_2 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>1,'mid'=>$v['ids']])->sum('fee');
                 //处理中充值金额
                 $r_money_1 = $data_list=Db::table('lmq_money_recharge')->where(['status'=>0,'mid'=>$v['ids']])->sum('money');
                 //充值成功充值金额
                 $r_money_2 = $data_list=Db::table('lmq_money_recharge')->where(['status'=>1,'mid'=>$v['ids']])->sum('money');
                 $results[$k]['w_money_1']=$w_money_1/100;
                 $results[$k]['w_fee_1']=$w_fee_1/100;
                 $results[$k]['w_money_2']=$w_money_2/100;
                 $results[$k]['w_fee_2']=$w_fee_2/100;
                 $results[$k]['r_money_1']=$r_money_1/100;
                 $results[$k]['r_money_2']=$r_money_2/100;
             }

            $data_list= $class::make($results, $listRows, $page, $total, $simple, $config);


            // 分页数据
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->setSearch(['name' => '姓名', 'mobile' => '手机号']) // 设置搜索框
                ->addColumns([ // 批量添加数据列
                    ['ids', 'ID'],
                    ['mobile', '手机号'],
                    ['name', '姓名'],
                    ['account', '可用余额','callback',function($value){return $value/100;}],
                    ['freeze', '冻结资金','callback',function($value){return $value/100;}],
                    ['operate_account', '操盘资金总额','callback',function($value){return $value/100;}],
                    ['bond_account', '保证金总额','callback',function($value){return $value/100;}],
                    ['w_money_1', '处理中提现金额'],
                    ['w_fee_1', '处理中提现手续费'],
                    ['w_money_2', '累计提现金额'],
                    ['w_fee_2', '累计提现手续费'],
                    ['r_money_1', '处理中充值金额'],
                    ['r_money_2', '累计充值金额'],
                    ['is_del', '账户状态', [0=>'正常', 1=>'注销/删除']],
                ])

                ->setTableName('member')
                ->addTopButton('custem',$btn_excel)
                ->addOrder('id,is_del')
                ->setRowList($data_list) // 设置表格数据
                ->fetch(); // 渲染模板

    }
    public function member_export(){
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'create_time,m.id desc';
        if(empty($_GET['list_rows'])){
            $listRows = 10;
        }else{
            $listRows=$_GET['list_rows'];
        }
        $simple = false;
        $config=[];
        $config   = array_merge(Config::get('paginate'), $config);
        $listRows = $listRows ?: $config['list_rows'];
        $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\think\\paginator\\driver\\' . ucwords($config['type']);
        $page  = isset($config['page']) ? (int) $config['page'] : call_user_func([
            $class,
            'getCurrentPage',
        ], $config['var_page']);
        $page = $page < 1 ? 1 : $page;
        $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);
        $total   = Db::table('lmq_member')
            ->alias('m')
            ->where($map)
            ->order($order)
            ->join('lmq_money o','m.id = o.mid')->count();
        $results = Db::table('lmq_member')
            ->alias('m')
            ->where($map)
            ->order($order)
            ->join('lmq_money o','m.id = o.mid')->page($page, $listRows)->select();
        foreach ($results as $k =>$v){
            //处理中提现金额
            $w_money_1 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>0,'mid'=>$v['id']])->sum('money');
            //处理中提现手续费
            $w_fee_1 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>0,'mid'=>$v['id']])->sum('fee');
            //提现成功提现金额
            $w_money_2 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>1,'mid'=>$v['id']])->sum('money');
            //提现成功提现手续费
            $w_fee_2 = $data_list=Db::table('lmq_money_withdraw')->where(['status'=>1,'mid'=>$v['id']])->sum('fee');
            //处理中充值金额
            $r_money_1 = $data_list=Db::table('lmq_money_recharge')->where(['status'=>0,'mid'=>$v['id']])->sum('money');
            //充值成功充值金额
            $r_money_2 = $data_list=Db::table('lmq_money_recharge')->where(['status'=>1,'mid'=>$v['id']])->sum('money');
            $results[$k]['w_money_1']=$w_money_1/100;
            $results[$k]['w_fee_1']=$w_fee_1/100;
            $results[$k]['w_money_2']=$w_money_2/100;
            $results[$k]['w_fee_2']=$w_fee_2/100;
            $results[$k]['r_money_1']=$r_money_1/100;
            $results[$k]['r_money_2']=$r_money_2/100;

        }

        $xlsData= $class::make($results, $listRows, $page, $total, $simple, $config);


         $is_del_arr=[0=>'正常', 1=>'注销/删除'];
           foreach ($xlsData as $k=>$v){

               $v['is_del']=$is_del_arr[$v['is_del']];

           }
        $title="会员账户";
        $arrHeader = array('ID','手机号','姓名','可用余额','冻结资金','操盘资金总额','保证金总额','处理中提现金额','处理中提现手续费','累计提现金额','累计提现手续费','处理中充值金额','累计充值金额','账户状态');
        $fields = array('id','mobile','name','account','freeze','operate_account','bond_account','w_money_1','w_fee_1','w_money_2','w_fee_2','r_money_1','r_money_2','is_del');
        export($arrHeader,$fields,$xlsData,$title);
    }

    //注册人数
    public function  registercounts(){

        $req=request();

        $map=$req::instance()->param('map');

        if($map==="888"){

            $startToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

            $startWeek = mktime(0,0,0,date('m'),date('d')-date('w'),date('Y'));
            $endWeek =  strtotime(date('Y-m-d', strtotime("this week Sunday", time()))) + 24 * 3600 - 1;

            $startMonth=mktime(0,0,0,date('m'),1,date('Y'));
            $endMonth=mktime(0,0,0,date('m'),date('d')+1,date('Y'));
            //当日注册会员
            $day = Db::name('member')
                ->where(['is_del'=>0])
                ->where('create_time','between',[$startToday,$endToday])
                ->count();
            //本周注册会员
            $weeks = Db::name('member')
                ->where(['is_del'=>0])
                ->where('create_time','between',[$startWeek,$endWeek])
                ->count();
            //本月注册会员
            $months = Db::name('member')
                ->where(['is_del'=>0])
                ->where('create_time','between',[$startMonth,$endMonth])
                ->count();
            return ZBuilder::make('table')
                ->setTemplate('registercounts')
                ->assign(['day'=>$day,'weeks'=>$weeks,'months'=>$months])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('registercounts', 'map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }
    //会员分析
    public function  useranalysis(){

        $req=request();

        $map=$req::instance()->param('map');

        if($map==="888"){
            //会员总数
            $member_count = Db::name('member')
                ->where(['status'=>1])
                ->count();
            //配资用户
            $borrow_count =Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow`  GROUP BY member_id ) aa');
            $borrow_count =  $borrow_count[0]['nums'];
            //体验用户
            $free_count =Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where  type=4 GROUP BY member_id ) aa');
            $free_count =  $free_count[0]['nums'];

            //模拟用户
            $simulate_count =Db::query('SELECT count(*) as nums from ( SELECT * from `lmq_stock_borrow` where  type=6 GROUP BY member_id ) aa');
            $simulate_count =  $simulate_count[0]['nums'];

            return ZBuilder::make('table')
                ->setTemplate('useranalysis')
                ->assign(['member_count'=>$member_count,'borrow_count'=>$borrow_count,'free_count'=>$free_count,'simulate_count'=>$simulate_count])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                    ->setExtraHtml('<iframe src="'.url('useranalysis', 'map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }
    //代理商分析
    public function  agentanalyse(){

        $req=request();

        $map=$req::instance()->param('map');

        if($map==="888"){
            //会员总数
            $member_count = Db::name('member')
                ->where(['status'=>1])
                ->count();
            $agent_one_counts = Db::name('member')
                ->where(['agent_id'=>1])
                ->count();
            //2级
            $agent_two_counts = Db::name('member')
                ->where(['agent_id'=>2])
                ->count();
            //3ji
            $agent_tree_counts = Db::name('member')
                ->where(['agent_id'=>3])
                ->count();
            //代理邀请的用户数
            $agentIdArr = Db::name('member')
                ->field('id')
                ->where('agent_id<>0')
                ->select();
              $agentId =[];
                foreach ($agentIdArr as $key=>$val){
                     foreach ($val as $v){
                         $agentId[]=$v;

                     }
                }
            $agentId =  implode(',',$agentId);
            $inviteUser = Db::name('member_invitation_relation')
            ->field('id')
                ->where('mid','in',[$agentId])
                ->count();
            //其他
            $other  =  $member_count-($agent_one_counts+$agent_two_counts+$agent_tree_counts+$inviteUser);

            return ZBuilder::make('table')
                ->setTemplate('agentanalyse')
                ->assign(['agent_one_counts'=>$agent_one_counts,'agent_two_counts'=>$agent_two_counts,'agent_tree_counts'=>$agent_tree_counts,'member_count'=>$member_count,'inviteUser'=>$inviteUser,'other'=>$other])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('agentanalyse','map=888').'" width="100%" height="651px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }


    //代理商佣金统计
     public function commissionstatistics(){
         $req=request();
         $map=$req::instance()->param('map');

         if($map==="888"){
             //累计返佣
             $accumulateRemission = Db::name('agents_back_money')
                 ->sum('affect');
             $accumulateRemission=  sprintf("%.2f",$accumulateRemission);
             //1级代理返佣
             $oneRemission = Db::name('agents_back_money')
                ->alias('bm')
                ->join('member m','bm.mid=m.id')
                ->where(['m.agent_id'=>1])
                ->sum('bm.affect');
             $oneRemission=  sprintf("%.2f",$oneRemission);
             //二级代理返佣
             $twoRemission =Db::name('agents_back_money')
                 ->alias('bm')
                 ->join('member m','bm.mid=m.id')
                 ->where(['m.agent_id'=>2])
                 ->sum('bm.affect');
             $twoRemission=  sprintf("%.2f",$twoRemission);
             //三级代理返佣
             $threeRemission = Db::name('agents_back_money')
                 ->alias('bm')
                 ->join('member m','bm.mid=m.id')
                 ->where(['m.agent_id'=>2])
                 ->sum('bm.affect');
             $threeRemission=  sprintf("%.2f",$threeRemission);
             return ZBuilder::make('table')
                 ->setTemplate('commissionstatistics')
                 ->assign(['accumulateRemission'=>$accumulateRemission,'oneRemission'=>$oneRemission,'twoRemission'=>$twoRemission,'threeRemission'=>$threeRemission])
                 ->fetch();
         }else{
             return ZBuilder::make('table')
                 ->hideCheckbox()
                 ->assign('empty_tips', '')
                 ->setExtraHtml('<iframe src="'.url('commissionstatistics', 'map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                 ->fetch();
         }

     }

    //操盘统计

    public function operatestatistics(){
        $req=request();
        $map=$req::instance()->param('map');
        if($map==="888"){
           $Position=new Position();
           $res =  $Position->get_position();
           $market_value='';  //累计持仓总额
            foreach($res as $key=>$val){
                $market_value += $val['market_value'];
            }
           $tatal_operate_account =  Db::name('money')->where('status=1')->sum('operate_account');
           $tatal_bond_account = Db::name('money')->where('status=1')->sum('bond_account');
            $tatal_operate_account = sprintf("%.2f",($tatal_operate_account/100));
            $tatal_bond_account = sprintf("%.2f",($tatal_bond_account/100));
            return ZBuilder::make('table')
                ->setTemplate('operatestatistics')
                ->assign(['tatal_operate_account'=>$tatal_operate_account,'tatal_bond_account'=>$tatal_bond_account,'market_value'=>$market_value])
                ->fetch();
        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('operatestatistics', 'map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }

    //股票统计

    public function sharesstatistics(){

        $req=request();

        $map=$req::instance()->param('map');

        if($map==="888"){

            //$tatal_bond_account = sprintf("%.2f",($tatal_bond_account/100));
           $buy_flag =  Db::name('stock_deal_stock')->where(['flag2'=>'证券买入'])->where('status','<>','0')->sum('amount');
           $sell_flag = Db::name('stock_deal_stock')->where(['flag2'=>'证券卖出'])->where('status','<>','0')->sum('amount');
           //累计成交金额
           $delivery_mount =  Db::name('stock_deal_stock')->where('status','<>','0')->sum('amount');
            //累计委托价格
           $trustPriceMount =  Db::name('stock_trust')->sum('trust_count*trust_price');

            return ZBuilder::make('table')
                ->setTemplate('sharesstatistics')
                ->assign(['buy_flag'=>$buy_flag,'sell_flag'=>$sell_flag,'delivery_mount'=>$delivery_mount,'trustPriceMount'=>$trustPriceMount])

                ->fetch();

        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('sharesstatistics','map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }

    public function amountstatistics(){

        $req=request();

        $map=$req::instance()->param('map');

        if($map==="888") {

         // 累计提盈
            $drawprofit = Db::name('stock_drawprofit ')->where(['status'=>1])->sum('money');
         //累计充值
            //累计充值总额
            $r_total = Db::name('money_recharge')
                ->where(['status'=>1])
                ->sum('money');
            $r_total = money_convert($r_total);

            //累计提现总额
            $w_total = Db::name('money_withdraw')
                ->where(['status'=>1])
                ->sum('money');
            $w_total = money_convert($w_total);

            return ZBuilder::make('table')
                ->setTemplate('amountstatistics')
                ->assign(['r_total'=>$r_total,'w_total'=>$w_total,'drawprofit'=>$drawprofit])
                ->fetch();

        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('amountstatistics','map=888' ).'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }


        }
      public function allocationstatistics(){

          $req=request();

          $map=$req::instance()->param('map');

          if($map==="888") {
             // 累计操盘总资金
              $operate_account = Db::name('money')->where('status=1')->sum('operate_account');
              $operate_account = money_convert($operate_account);
              //累计保证金
              $bond_account = Db::name('money')->where('status=1')->sum('bond_account');
              $bond_account = money_convert($bond_account);
              //天周管理费合计

              $interest_day_week = Db::name('stock_borrow')->where('type','in',[1,2])->sum('borrow_interest');

              $month_interest = Db::name('stock_detail')->where('status=1')->sum('sort_order*interest');
              $all_interest = money_convert($interest_day_week)+$month_interest;

              return ZBuilder::make('table')
                  ->setTemplate('allocationstatistics')
                  ->assign(['operate_account'=>$operate_account,'bond_account'=>$bond_account,'all_interest'=>$all_interest])
                  ->fetch();

          }else{
              return ZBuilder::make('table')
                  ->hideCheckbox()
                  ->assign('empty_tips', '')
                  ->setExtraHtml('<iframe src="'.url('allocationstatistics','map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                  ->fetch();
          }

      }
      public function applynums(){

          $req=request();

          $map=$req::instance()->param('map');

          if($map==="888") {
              //总配资单数
                $allocation_all_nums = Db::name('stock_borrow')->count();
              //免费体验
                $tryCount =  Db::name('stock_borrow')->where('type=4')->count();
              //按天配资
              $dayCount =  Db::name('stock_borrow')->where('type=1')->count();
              //按周配资
              $weekCount =  Db::name('stock_borrow')->where('type=2')->count();
              //按月配资
              $monthCount =  Db::name('stock_borrow')->where('type=3')->count();
              //免息配资
              $freeCount =  Db::name('stock_borrow')->where('type=5')->count();
              //模拟操盘
              $simulationCount =  Db::name('stock_borrow')->where('type=6')->count();

              return ZBuilder::make('table')
                  ->setTemplate('applynums')
                  ->assign(['allocation_all_nums'=>$allocation_all_nums,'tryCount'=>$tryCount,'dayCount'=>$dayCount,'weekCount'=>$weekCount,'monthCount'=>$monthCount,'freeCount'=>$freeCount,'simulationCount'=>$simulationCount])
                  ->fetch();

          }else{
              return ZBuilder::make('table')
                  ->hideCheckbox()
                  ->assign('empty_tips', '')
                  ->setExtraHtml('<iframe src="'.url('applynums','map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                  ->fetch();
          }


      }
      public function invite()
      {
          $req = request();

          $map = $req::instance()->param('map');

          if($map === "888") {
              $returns = array();
              for($i=1;$i<=12;$i++){
                  $year =  date('Y');
                  $last_day = date('t', mktime(0, 0, 0, $i, 1, $year));  //当月有几天

                  $min = strtotime(date("Y").'-'.$i.'-01 00:00:00');
                  $max  =  strtotime(date("Y").'-'.$i.'-'.$last_day.' 23:59:59');

                  $returns[$i]=  Db::name('member_invitation_relation')
                      ->alias('r')
                      ->join('member m','r.mid = m.id','LEFT')
                      ->where('m.agent_id=0')
                      ->where('r.create_time',['>=',$min],['<=',$max],'and')
                      ->count();
              }
              $returns = json_encode($returns);

                  return ZBuilder::make('table')
                  ->setTemplate('invite')
                  ->assign(['returns'=>$returns])
                  ->fetch();

            }else{
                    return ZBuilder::make('table')
                    ->hideCheckbox()
                    ->assign('empty_tips', '')
                    ->setExtraHtml('<iframe src="'.url('invite','map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                    ->fetch();
            }

      }
 //累计邀请金额
    public function invite_amount()
    {
        $req = request();

        $map = $req::instance()->param('map');

        if($map === "888") {

            $returns = array();
            for($i=1;$i<=12;$i++){
                $year =  date('Y');
                $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);  //当月有几天

                $min = strtotime(date("Y").'-'.$i.'-01 00:00:00');
                $max  =  strtotime(date("Y").'-'.$i.'-'.$last_day.' 23:59:59');

                $returns[$i]=  Db::name('member_invitation_record')
                    ->alias('r')
                    ->join('member m','r.mid = m.id','LEFT')
                    ->where('m.agent_id=0')
                    ->where('r.create_time',['>=',$min],['<=',$max],'and')
                    ->sum('money');
                $returns[$i] = $returns[$i];
            }

            $returns = json_encode($returns);

            return ZBuilder::make('table')
                ->setTemplate('invite_amount')
                ->assign(['returns'=>$returns])
                ->fetch();

        }else{
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->assign('empty_tips', '')
                ->setExtraHtml('<iframe src="'.url('invite_amount','map=888').'" width="100%" height="650px" frameborder="0"></iframe>', 'toolbar_bottom')
                ->fetch();
        }

    }


}