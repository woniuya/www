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

namespace app\stock\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\stock\model\Account as AccountModel;
use app\stock\model\AccountInfo as AccountInfoModel;
use app\market\model\Position;
use app\market\model\Deal_stock;
use app\market\model\Trust;
use app\market\model\Delivery;
use app\market\model\Grid;
use think\Db;
use think\Hook;
use think\Log;

/**
 * 证券账户默认控制器
 * @package app\stock\admin
 */
class Account extends Admin
{
    /**
     * 用户首页
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();

        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='create_time desc';
        }
        // 数据列表
        $data_list = AccountModel::where($map)->order($order)->paginate();
        if(isset($data_list)){
            foreach ($data_list as $key=>$value){
                $data_list[$key]['min_commission'] = $data_list[$key]['min_commission']/100;
            }
        }
        $btn_detail = [
            'title' => '详情查询',
            'icon'  => 'fa fa-fw fa-search',
            'href'  => url('info', ['id' => '__id__','broker'=>'__broker__'])
        ];

        // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('证券信息管理') // 设置页面标题
            ->setTableName('stock_account') // 设置数据表名
            ->setSearch(['id' => 'ID', 'lid' => '证券账户名', 'stockjobber' => '证券公司']) // 设置搜索参数
            ->addOrder('id,create_time') // 添加排序
            ->addFilter('stockjobber,lid,user') // 添加筛选
            ->addColumns([ // 批量添加列
                ['stockjobber', '证券公司'],
                ['stock_exchange', '证券营业部'],
                ['lid', '交易账户'],
                ['user', '接口账户'],
                ['create_time', '创建时间', 'datetime'],
                ['status', '状态', 'status', '', ['禁用:danger', '启用:success']],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable') // 批量添加顶部按钮
            ->addRightButton('custom', $btn_detail) // 添加资金查询按钮
            ->addRightButtons('edit') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

    /**
     * 新增
     * @author 路人甲乙
     * @return mixed
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $broker = AccountModel::getBroker($data['stockjobber']);
            if(isset($broker)){
                $data['broker'] = $broker['broker_id'];//券商类型
                $data['stockjobber'] = $broker['broker_value'];//券商名称
                $data['clienver'] = $broker['clienver'];//券商客户端登录版本
            }
            $data['net'] = 0;//网络类型，0网络不确定、1中国电信、2中国联通、4中国移动、8华数、16其它网络
            $data['sever'] = 2;//指定服务类型，默认2 2:交易服务、 1: 普通行情
            $data['min_commission'] = $data['min_commission']*100;//佣金，保存数据库时单位为分

            //如果佣金比例未填写，则默认使用全局佣金比例
            if($data['commission_scale']=='' || $data['commission_scale']==0){
                $data['commission_scale'] = config('commission');
            }

            //如果最低佣金未填写，则默认使用全局最低佣金
            if($data['min_commission']==''){
                $data['min_commission'] =config('limit_fee')*100;//单位注意由元转化为分
            }

            // 验证
            $result = $this->validate($data, 'Account.insert');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);
            $resu=[];
            $switch=false;
            if($data['broker']==-1){//模拟盘类型
                $data['type'] = 0;//模拟盘类型
                //新增券商业务处理
                $resu = AccountModel::addAccount($data);

            }else{//如果是实盘
                $reTrade=false;

                if(config('web_real_api')==1) {
                    //检测校验券商账号是否能用（这里用的是安全模式）
                    $reTrade = gs('CheckBroker', [$data['lid'], $data['user'], $data['pwd'], $data['broker'], $data['clienver']]);
                }
                if(config('web_real_api')==2){
                    $switch=true;
                    $reTrade=true;
                }
                //如果该券商已开通
                if($reTrade) {
                    $data['type'] = 1;//实盘类型
                    //新增券商业务处理
                    $resu = AccountModel::addAccount($data);
                }else{
                    $this->error('该券商尚未开通交易接口，请先联系系统商进行开通！');
                }
            }

            if($resu){
                Hook::listen('account_add', $resu);
                $details = '添加证券账户信息成功,证券账户名称：'.$data['stockjobber'];
                // 记录行为
                action_log('account_add', 'stock_account', '', UID,$details);
                if($switch){$this->success('新增成功,但因为当前网格实盘接口不支持，系统未能校验', url('index'));}
                $this->success('新增成功', url('index'));
            }else{
                $this->error('新增失败');
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['select:6', 'stockjobber', '请选择证券类型', '<span class="text-danger">必选</span>', AccountModel::getBrokerList()],
                ['text:6', 'stock_exchange', '证券营业部', '证券营业部'],
                ['text:6', 'lid', '证券账户名','必填'],
                ['text:6', 'user', '交易通账户','必填'],
                ['password:6', 'pwd', '密码','必填'],
                ['password:6', 'communication_pwd', '通讯密码','选填'],
                ['number:6', 'commission_scale', '佣金比例', '（单位：万分之几） 如：5 代表万分之五'],
                ['number:6', 'min_commission', '最低佣金', '（单位：元）'],
                ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
            ])
            ->setPageTips('<span class="text-danger">佣金比例和最低佣金如果不做设置，系统默认使用系统参数！</span>')
            ->fetch();
    }

    /**
     * 编辑
     * @param null $id 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['min_commission'] = $data['min_commission']*100;//佣金，保存数据库时单位为分
            // 验证
            $result = $this->validate($data, 'Account.update');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);

            // 如果没有填写密码，则不更新密码
            if ($data['pwd'] == '') {
                unset($data['pwd']);
            }

            //如果没有填写最低佣金，则不更新最低佣金
            if($data['min_commission']==''){
                unset($data['min_commission']);
            }

            //如果没有填写佣金比例，则不更新佣金比例
            if($data['commission_scale']==''){
                unset($data['commission_scale']);
            }

            if (AccountModel::update($data)) {
                $account = AccountModel::get($data['id']);
                Hook::listen('account_edit', $account);
                // 记录行为
                action_log('account_edit', 'stock_account', $account['id'], UID, $account['id']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = Db::name("stock_account")->where('id', $id)->field('*', false)->find();

        $info['min_commission'] = $info['min_commission']/100;//将单位分转化元分显示在页面

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static:6', 'stockjobber', '证券公司', '券商名'],
                ['static:6', 'stock_exchange', '证券营业部', '证券营业部'],
                ['static:6', 'lid', '证券账户名','不可修改'],
                ['text:6', 'user', '交易通账户','必填'],
                ['password:6', 'pwd', '密码','必填'],
                ['password:6', 'communication_pwd', '通讯密码','选填'],
                ['number:6', 'commission_scale', '佣金比例', '（单位：万分之几） 如：5 代表万分之五'],
                ['number:6', 'min_commission', '最低佣金', '（单位：元）'],
                ['radio', 'status', '状态', '', ['禁用', '启用']]
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    //启动交易服务
    public function start_api()
    {
        $req=$this->request;
        if ($this->request->isPost()) {
            $this->success('正在启动',url('start_api','id=1'));
        }
        $num=get_spapi();
        if($num=='1'){
            $info['status']='';
            return ZBuilder::make('table')
                ->setPageTitle('交易服务已启动！如需重启请先关闭交易服务。') // 设置页面标题
                ->assign('empty_tips', '')
                ->hideCheckbox()
                ->fetch();
        }
        $id=$req->param('id');
        if($id==1){
            $info['status']='';
            return ZBuilder::make('table')
                ->setPageTitle('交易服务已启动！') // 设置页面标题
                ->assign('empty_tips', '')
                ->hideCheckbox()
                ->js('start_api')
                ->fetch();
        }else{
            $info['status']='提交确认启动交易服务';
            return ZBuilder::make('form')
                ->setPageTitle('启动交易服务') // 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['hidden','id'],
                    ['static:6', 'status', '', ''],
                ])
                ->setFormData($info)// 设置表单数据
                ->fetch();
        }
    }
    //关闭交易通服务
    public function close_api()
    {
        $req=$this->request;
        if ($this->request->isPost()) {
            $this->success('正在关闭',url('close_api','id=100'));
        }
        $id=$req->param('id');
        if($id==100){
            $info['status']='';
            return ZBuilder::make('table')
                ->setPageTitle('交易服务已关闭！') // 设置页面标题
                ->assign('empty_tips', '')
                ->hideCheckbox()
                ->js('close_api')
                ->fetch();
        }
        $num=get_spapi();
        if($num=='1'){
            $info['status']='提交确认关闭交易服务';
            return ZBuilder::make('form')
                ->setPageTitle('关闭交易服务') // 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['hidden','id'],
                    ['static:6', 'status', '', ''],
                ])
                ->setFormData($info)// 设置表单数据
                ->fetch();
        }
        $info['status']='';
        return ZBuilder::make('table')
            ->setPageTitle('交易服务已是关闭状态！') // 设置页面标题
            ->assign('empty_tips', '')
            ->hideCheckbox()
            ->fetch();

    }



    /**
     * 证券账户详情查询
     * @param string $tab tab分组名
     * @param int $uid 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function info($id = null,$broker)
    {
        if ($id === null) $this->error('缺少参数');
        // 查询获取券商基本信息
        $account_info = AccountInfoModel::getAccountInfo($id);

        //如果是真实券商，则调用股票大盘接口，获取证券资金账户信息
        if($broker>0){
            if(get_spapi()=='0'){
                $this->error("交易服务未启动，请启动交易服务！");
            }
            $list=[];
            $list2=[];
            if(config('web_real_api')==1){
                $list = gs('queryTradeData',[$id,1]);//更新查询资金信息到数据表
                $list2 = gs('queryTradeData',[$id,6]);//更新查询股东代码信息到数据表
            }
            if(config('web_real_api')==2){
                $res=json_decode(Grid::grid_funds($id),true);
                $list['1']['1']="空值";
                $list['1']['2']=$res["TotalAvailableAmount"];
                $list['1']['3']="空值";
                $list['1']['4']=$res["TotalAvailableAmount"];
                $list['1']['5']=$res["TotalMarketValue"];
                $list['1']['6']=$res["TotalAssets"];
                $list['1']['7']="空值";
                $list['1']['8']=null;
                $list2 =Grid::grid_category_stockholder($id);
            }
            if(!empty($list)&&!empty($list2)){
                $account_info[0]['balance']=$list['1']['1'];//资金余额
                $account_info[0]['account_money']=$list['1']['2'];//可用资金
                $account_info[0]['desirable_money']=$list['1']['4'];//可取资金
                $account_info[0]['freeze_money']=$list['1']['3'];//冻结资金
                $account_info[0]['total_money']=$list['1']['6'];//总 资 产
                $account_info[0]['market_value']=$list['1']['5'];//最新市值
                $account_info[0]['ck_profit']=$list['1']['7'];//参考浮动盈亏
                $account_info[0]['sz_code']=$list2['1']['0'];
                $account_info[0]['sh_code']=$list2['2']['0'];
                $account_info[0]['gudong_name']=$list2['1']['1'];
                $account_info[0]['info']=$list['1']['8'];
            }
            $config = array("type" =>"bootstrap","var_page"=>"page","list_rows"=>"10","query"=> []);
            $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\think\\paginator\\driver\\' . ucwords($config['type']);
            $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);
            $class::make($account_info, 10, 1, 1, 1, $config);
        }else{
            $config = array("type" =>"bootstrap","var_page"=>"page","list_rows"=>"10","query"=> []);
            $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\think\\paginator\\driver\\' . ucwords($config['type']);
            $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);
            $class::make($account_info, 10, 1, 1, 1, $config);
            return ZBuilder::make('table')
                ->hideCheckbox()
                ->addColumns([ // 批量添加列
                    ['lid', '交易账户名'],
                    ['soruce', '证券来源'],
                    ['user', '证券账户'],
                    ['currency', '币种','callback',function($value){
                        if($value==0){
                            return '人民币';
                        }else{
                            return '其他币种';
                        }
                    }],
                    ['balance','资金余额','text.edit'],
                    ['account_money', '可用资金','text.edit'],
                    ['desirable_money', '可取资金','text.edit'],
                    ['freeze_money', '冻结资金','text.edit'],
                    ['total_money', '总 资 产','text.edit'],
                    ['market_value', '最新市值','text.edit'],
                    ['ck_profit', '参考浮动盈亏','text.edit'],
                    ['sz_code', '股东深市代码','text.edit'],
                    ['sh_code', '股东沪市代码','text.edit'],
                    ['gudong_name', '股东名称','text.edit'],
                    ['info', '状态', '保留信息','text.edit'],
                ])
                ->setRowList($account_info) // 设置表格数据
                ->setTableName('stock_account_info') // 设置表格数据
                ->fetch();
        }
        $btn_position = [
            'title' => '持仓',
            'class' => 'btn btn-info',
            'icon'  => 'fa fa-fw fa-file-text-o',
            'href'  => url('position', ['id' => $id])
        ];
        $btn_deal_stock = [
            'title' => '当日成交',
            'class' => 'btn btn-info',
            'icon'  => 'fa fa-fw fa-handshake-o',
            'href'  => url('deal_stock', ['id' => $id])
        ];
        $btn_trust = [
            'title' => '当日委托',
            'class' => 'btn btn-info',
            'icon'  => 'fa fa-fw fa-hand-o-right',
            'href'  => url('trust', ['id' => $id])
        ];
        $btn_history_deal = [
            'title' => '历史成交',
            'class' => 'btn btn-info',
            'icon'  => 'fa fa-fw fa-briefcase',
            'href'  => url('history_deal', ['id' => $id])
        ];
        $btn_history_trust = [
            'title' => '历史委托',
            'class' => 'btn btn-info',
            'icon'  => 'fa fa-fw fa-clock-o',
            'href'  => url('history_trust', ['id' => $id])
        ];
        $btn_delivery = [
            'title' => '交割单',
            'class' => 'btn btn-info',
            'icon'  => 'fa fa-fw fa-list-ol',
            'href'  => url('delivery', ['id' => $id])
        ];
        $btn_BrokerLogin = [
            'title' => '登录',
            'icon'  => 'fa fa-fw fa-sign-in',
            'class' => 'btn  btn-info ajax-get',
            'href'  => url('BrokerLogin', ['id' => $id])
        ];
        $btn_broker_out = [
            'title' => '退出登录',
            'icon'  => 'fa fa-fw fa-sign-out',
            'class' => 'btn  btn-info ajax-get confirm',
            'data-title' => '真的要退出吗？',
            'data-tips' => '此账号关联的所有子账号将停止交易！',
            'href'  => url('broker_out', ['id' => $id])
        ];
        return ZBuilder::make('table')
            ->hideCheckbox()
            ->addTopButton('access',$btn_position,true)
            ->addTopButton('access',$btn_deal_stock,true)
            ->addTopButton('access',$btn_trust,true)
            ->addTopButton('access',$btn_history_deal,true)
            ->addTopButton('access',$btn_history_trust,true)
            ->addTopButton('access',$btn_delivery,true)
            ->addTopButton('access',$btn_BrokerLogin)
            ->addTopButton('access',$btn_broker_out)
            ->addColumns([ // 批量添加列
                ['lid', '交易账户名'],
                ['soruce', '证券来源'],
                ['user', '证券账户'],
                ['currency', '币种','callback',function($value){
                    if($value==0){
                        return '人民币';
                    }else{
                        return '其他币种';
                    }
                }],
                ['balance','资金余额'],
                ['account_money', '可用资金'],
                ['desirable_money', '可取资金'],
                ['freeze_money', '冻结资金'],
                ['total_money', '总 资 产'],
                ['market_value', '最新市值'],
                ['ck_profit', '参考浮动盈亏'],
                ['sz_code', '股东深市代码'],
                ['sh_code', '股东沪市代码'],
                ['gudong_name', '股东名称'],
                ['info', '状态', '保留信息'],
            ])
            ->setRowList($account_info) // 设置表格数据
            ->setTableName('stock_account_info') // 设置表格数据
            ->fetch();

        /////////////
    }
    /*
     * 券商账号退出登录
     * $id 券商在表中的id号
     */
    public function broker_out(){
        $req=request();
        $id=$req::instance()->param('id');
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法退出',url('index'));
        }
        $res=Db::name('admin_plugin')->where(['name'=>"GreenSparrow"])->find();
        if(empty($res)) {
            $this->error('请安装路人甲乙实盘交易插件');
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $data="";
        if(config('web_real_api')==1) {
            $data = gs('BrokerOut', [$id]);
        }
        if(config('web_real_api')==2){
            $this->error('网格接口不支持退出登录');
        }
        if($data){
            $this->success('操作成功',url('index'));
        }else{
            $this->error('操作失败');
        }
    }
    /*
     * 券商账号登录
     * $LID 安全模式登录id
     * $user 安全模式登录用户名
     * $password 安全模式登录密码
     * $broker 券商代码
     * $clienver 券商版本号
     * $id 券商在表中的id号
     */
    public function BrokerLogin(){
        $req=request();
        $id=$req::instance()->param('id');
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法登录',url('index'));
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $LID=$broker['lid'];
        $user=$broker['user'];
        $password=$broker['pwd'];
        $broker=$broker['broker'];
        $clienver=$broker['clienver'];
        $res=Db::name('admin_plugin')->where(['name'=>"GreenSparrow"])->find();
        if(empty($res)) {
            $this->error('请安装路人甲乙实盘交易插件');
        }
        $data="";
        if(config('web_real_api')==1) {
            $data = gs('BrokerLogin', [$LID, $user, $password, $broker, $clienver, $id]);
        }
        if(config('web_real_api')==2){
            $this->error('网格接口不支持账号登录');
        }
        if($data){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
    /*
     * 交割单查询
     * @id 券商id号
     */
    public function delivery(){
        $map = $this->getMap();
        $req=request();
        $id=$req::instance()->param('id');
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法查看',url('index'));
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        if(empty($map['add_time'][1][0])){
            $beginday=date('Ymd',time()-2592000);
        }else{
            $beginday=date('Ymd',strtotime($map['add_time'][1][0]));
        }
        if(empty($map['add_time'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['add_time'][1][1]));
        }
        $Delivery=new Delivery();
        $lid=$broker['lid'];
        $user=$broker['user'];
        $soure=$broker['stockjobber'];
        if($broker['broker']!=-1){
            $data="";
            if(config('web_real_api')==1) {
                $data = gs('history', [$broker['id'], 4, $beginday, $endday]);
            }
            if(config('web_real_api')==2){
                $this->error('网格接口不支持此操作，请换用交易通接口');
            }
            unset($data[0]);
            if(!empty($data)){
                $Delivery->add_delivery_order($data,$lid,$user,$soure);
            }
        }
        $res=Db::name('stock_delivery_order_broker')
            ->where(['lid'=>$lid])
            ->where($map)
            ->paginate($listRows = 20);
        $page = $res->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('交割单查询') // 设置页面标题
            ->setTableName('stock_list') // 设置数据表名
            ->addTimeFilter('add_time', [$beginday, $endday]) // 添加时间段筛选
            ->addColumns([ // 批量添加列
                ['lid', '证券账户'],
                ['soruce', '证券来源'],
                ['gupiao_code', '股票代码'],
                ['gupiao_name', '股票名称'],
                ['business_name', '业务名称'],
                ['deal_date', '成交日期', 'date'],
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
                ['gudong_code', '股东代码'],
            ])
            ->setRowList($res) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    //历史委托
    public function history_trust(){
        $map = $this->getMap();
        $req=request();
        $trade_id=$req::instance()->param('id');//券商id
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($trade_id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法查看',url('index'));
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $type=1;
        if(empty($map['add_time'][1][0])){
            $beginday=date('Ymd',time()-2592000);
        }else{
            $beginday=date('Ymd',strtotime($map['add_time'][1][0]));
        }
        if(empty($map['add_time'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['add_time'][1][1]));
        }
        $trust=new Trust();
        $lid=$broker['lid'];
        $user=$broker['user'];
        $soure=$broker['stockjobber'];
        if($broker['broker']!=-1){
            $data="";
            if(config('web_real_api')==1) {
                $data= gs('history', [$trade_id, $type, $beginday, $endday]);
            }
            if(config('web_real_api')==2){
                $this->error('网格接口不支持此操作，请换用交易通接口');
            }
            unset($data[0]);
            if(!empty($data)){
                $trust->add_history_trust($data,$lid,$user,$soure);
            }
        }
        $res=Db::name('stock_trust_broker')
            ->where(['lid'=>$lid])
            ->where($map)
            ->paginate($listRows = 20);
        $page = $res->render();
        //为保证格式统一转换一下日期格式
        $beginday=date('Y-m-d',strtotime($beginday));
        $endday=date('Y-m-d',strtotime($endday));
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('历史委托') // 设置页面标题
            ->setTableName('stock_list') // 设置数据表名
            ->addTimeFilter('add_time', [$beginday, $endday]) // 添加时间段筛选
            ->addColumns([ // 批量添加列
                ['lid', '证券账户'],
                ['soruce', '证券来源'],
                ['gupiao_code', '股票代码'],
                ['gupiao_name', '股票名称'],
                ['trust_date', '委托日期'],
                ['trust_time', '委托时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['gudong_code', '股东代码'],
                ['cancel_order_flag', '撤单标志'],
                ['cancel_order_count', '撤单数量'],
                ['type', '账号类别',['0'=>'深证A股','1'=>'上证A股']],
                ['status', '状态说明'],
            ])
            ->setRowList($res) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    //历史成交
    public function history_deal(){
        $map = $this->getMap();
        // 获取排序
        $req=request();
        $trade_id=$req::instance()->param('id');//券商id
        $type=2;
        if(empty($map['deal_date'][1][0])){
            $beginday=date('Ymd',time()-2592000);
        }else{
            $beginday=date('Ymd',strtotime($map['deal_date'][1][0]));
        }
        if(empty($map['deal_date'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['deal_date'][1][1]));
        }
        // 自定义按钮
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($trade_id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法查看',url('index'));
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $deal_stack=new Deal_stock();
        $lid=$broker['lid'];
        $user=$broker['user'];
        $soure=$broker['stockjobber'];
        if($broker['broker']!=-1){
            $data="";
            if(config('web_real_api')==1) {
                $data= gs('history', [$trade_id, $type, $beginday, $endday]);
            }
            if(config('web_real_api')==2){
                $this->error('网格接口不支持此操作，请换用交易通接口');
            }
            unset($data[0]);
            if(!empty($data)){
                $deal_stack->add_deal_stock_broker($data,$lid,$user,$soure);
            }
        }
        $res=Db::name('stock_deal_stock_broker')
            ->where(['lid'=>$lid])
            ->where($map)
            ->paginate($listRows = 20);
        $page = $res->render();
        //为保证格式统一转换一下日期格式
        $beginday=date('Y-m-d',strtotime($beginday));
        $endday=date('Y-m-d',strtotime($endday));
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('历史成交') // 设置页面标题
            ->setTableName('stock_list') // 设置数据表名
            ->addTimeFilter('deal_date', [$beginday, $endday]) // 添加时间段筛选
            ->addColumns([ // 批量添加列
                ['lid', '证券账户'],
                ['soruce', '证券来源'],
                ['gupiao_code', '股票代码'],
                ['gupiao_name', '股票名称'],
                ['deal_price', '成交价格'],
                ['volume', '成交数量'],
                ['deal_date', '成交日期','date'],
                ['deal_time', '成交时间'],
                ['status', '状态'],
                ['right_button', '操作', 'btn'],
            ])
            ->setRowList($res) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    /*
     * 当日委托查询
     * @id 子账户id
     */
    public function trust(){
        $req=request();
        $id=$req::instance()->param('id');
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法查看',url('index'));
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $trust=new Trust();
        $lid=$broker['lid'];
        $user=$broker['user'];
        $soruce=$broker['stockjobber'];
        if($broker['broker']!=-1){
            $data=[];
            if(config('web_real_api')==1) {
                $data = gs('queryTradeData', [$broker['id'], 3]);
            }
            if(config('web_real_api')==2){
                $data =Grid::grid_category_trust($broker['id']);
            }
            unset($data[0]);
            if(!empty($data)){
                $trust->add_trust_broker($data,$lid,$user,$soruce);
            }
        }
        $time=strtotime(date('Ymd',time()));
        $res=Db::name('stock_trust_broker')
            ->where(['lid'=>$lid])
            ->where('trust_date','>=',$time)
            ->paginate($listRows = 20);
        $page = $res->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('持仓查询') // 设置页面标题
            ->setTableName('stock_list') // 设置数据表名
            ->addColumns([ // 批量添加列
                ['lid', '证券账户'],
                ['soruce', '证券来源'],
                ['gupiao_code', '股票代码'],
                ['gupiao_name', '股票名称'],
                ['trust_date', '委托日期','date'],
                ['trust_time', '委托时间'],
                ['trust_no', '委托编号'],
                ['trust_price', '委托价格'],
                ['trust_count', '委托数量'],
                ['volume', '成交数量'],
                ['amount', '成交金额'],
                ['gudong_code', '股东代码'],
                ['cancel_order_flag', '撤单标志'],
                ['cancel_order_count', '撤单数量'],
                ['type', '账号类别',['0'=>'深证A股','1'=>'上证A股']],
                ['status', '状态说明'],
            ])
            ->setRowList($res) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    /*
     * 当日成交查询
     * @id 子账户id
     */
    public function deal_stock(){
        $req=request();
        $id=$req::instance()->param('id');
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法查看',url('index'));
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $lid=$broker['lid'];
        $user=$broker['user'];
        $soruce=$broker['stockjobber'];
        $deal_stack=new Deal_stock();
        if($broker['broker']!=-1){
            $data=[];
            if(config('web_real_api')==1) {
                $data=gs('queryTradeData',[$broker['id'],4]);
            }
            if(config('web_real_api')==2){
                $data =Grid::grid_category_deal($broker['id']);
            }
            unset($data[0]);
            if(!empty($data)){
                $deal_stack->add_deal_stock_broker($data,$lid,$user,$soruce);
            }
        }
        $time=strtotime(date('Y-m-d',time()));
        $res=Db::name('stock_deal_stock_broker')
            ->where(['lid'=>$lid,'deal_date'=>$time])
            ->paginate($listRows = 10);
        $page = $res->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('当日成交') // 设置页面标题
            ->setTableName('stock_list') // 设置数据表名
            ->addColumns([ // 批量添加列
                ['lid', '证券账户'],
                ['soruce', '证券来源'],
                ['gupiao_code', '股票代码'],
                ['gupiao_name', '股票名称'],
                ['deal_price', '成交价格'],
                ['volume', '成交数量'],
                ['deal_time', '成交时间', 'datetime'],
                ['status', '状态'],
                ['right_button', '操作', 'btn'],
            ])
            ->setRowList($res) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    /*
     * 持仓查询
     * @id 券商id号
     */
    public function position(){
        $req=request();
        $id=$req::instance()->param('id');
        $AccountInfo=new AccountInfoModel();
        $broker = $AccountInfo->get_broker($id);
        if($broker['broker']<0){
            $this->error('此账户为模拟账户，无法查看',url('index'));
        }
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $position=new Position();
        $lid=$broker['lid'];
        $user=$broker['user'];
        $soruce=$broker['stockjobber'];
        if($broker['broker']!=-1){
            $data=[];
            if(config('web_real_api')==1) {
                $data=gs('queryTradeData',[$broker['id'],2]);
            }
            if(config('web_real_api')==2){
                $data =Grid::grid_category_stock($broker['id']);
            }
            unset($data[0]);
            if(!empty($data)){
                $position->add_position_broker($data,$lid,$user,$soruce);
            }else{
                Db::name("stock_position_broker")
                    ->where(["lid"=>$broker['lid']])
                    ->where(['buying'=>0])
                    ->delete();
//                Db::name("stock_position")
//                    ->where(["lid"=>$broker['lid']])
//                    ->where(['buying'=>0])
//                    ->delete();
//                Log::write(date('y-m-d H:i:s',time())."::清空账户".$broker['lid']."的股票");
            }
        }
        $res=Db::name('stock_position_broker')
            ->where(['lid'=>$lid])
            ->where(['buying'=>0])
            ->where('stock_count','>',0)
            ->paginate($listRows = 10);
        $page = $res->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('持仓查询') // 设置页面标题
            ->setTableName('stock_list') // 设置数据表名
            ->addColumns([ // 批量添加列
                ['lid', '证券账户'],
                ['soruce', '证券来源'],
                ['gupiao_code', '股票代码'],
                ['gupiao_name', '股票名称'],
                ['stock_count', '实际数量'],
                ['canbuy_count', '可卖数量'],
                ['ck_price', '参考成本价'],
                ['buy_average_price', '买入均价'],
                ['now_price', '当前价'],
                ['market_value', '最新市值'],
                ['ck_profit', '参考浮动盈亏'],
                ['profit_rate', '盈亏比例%'],
                ['jiyisuo', '交易所'],
            ])
            ->setRowList($res) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    /**
     * 删除用户
     * @param array $ids 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function delete($ids = [])
    {
        Hook::listen('account_delete', $ids);
        return $this->setStatus('delete');
    }

    /**
     * 启用用户
     * @param array $ids 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function enable($ids = [])
    {
        Hook::listen('account_enable', $ids);
        return $this->setStatus('enable');
    }

    /**
     * 禁用用户
     * @param array $ids 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function disable($ids = [])
    {
        Hook::listen('account_disable', $ids);
        return $this->setStatus('disable');
    }

    /**
     * 设置用户状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @author 路人甲乙
     * @return mixed
     */
    public function setStatus($type = '', $record = [])
    {
        $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $type_name = Db::name("stock_account_info")->where('id', 'in', $ids)->column('id');
        return parent::setStatus($type, ['account_'.$type, 'stock_account', 0, UID, implode('、', $type_name)]);
    }

    /**
     * 资金明细快速编辑
     * @param array $record 行为日志
     * @author 路人甲乙
     * @return mixed
     */
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        //$id      == UID && $this->error('禁止操作当前账号');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $config  = Db::name("stock_account_info")->where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['account_edit', 'stock_account', $id, UID, $details]);
    }
    /**
     * 证券账户详情查询
     * @param string $tab tab分组名
     * @param int $uid 用户id
     * @author 路人甲乙
     * @return mixed
     */
    /*public function detail($group = 'tab1',$id = 0)
    {
        // 获取券商基本信息
        $account_info = AccountInfoModel::getAccountInfo($id);

        //获取股票委托基本信息
        $lid = $account_info[0]['lid'];
        $user = $account_info[0]['user'];
        $stock_trust = AccountModel::getTrust($lid,$user);
        // 分页数据
        $page2 = $stock_trust->render();
        $list_tab = [
            'tab1' => ['title' => '券商基本信息', 'url' => url('detail', ['group' => 'tab1','id' => $id])],
            'tab2' => ['title' => '委托记录', 'url' => url('detail', ['group' => 'tab2','id' => $id])],
        ];

        switch ($group) {
            case 'tab1':
                return ZBuilder::make('table')
                    ->setTabNav($list_tab,  $group)
                    ->setPrimaryKey('lid') // 设置主键名为lid
                    ->hideCheckbox()
                    ->addColumns([ // 批量添加列
                        ['lid', '交易账户名'],
                        ['soruce', '证券来源'],
                        ['user', '证券账户'],
                        ['currency', '币种','callback',function($value){
                            if($value==0){
                                return '人民币';
                            }else{
                                return '其他币种';
                            }
                        }],
                        ['balance','资金余额','text.edit'],
                        ['balance','资金余额','text.edit'],
                        ['account_money', '可用资金','text.edit'],
                        ['desirable_money', '可取资金','text.edit'],
                        ['freeze_money', '冻结资金','text.edit'],
                        ['total_money', '总 资 产','text.edit'],
                        ['market_value', '最新市值'],
                        ['ck_profit', '参考浮动盈亏'],
                        ['sz_code', '股东深市代码'],
                        ['sh_code', '股东沪市代码'],
                        ['gudong_name', '股东名称'],
                        ['info', '状态', '保留信息'],
                    ])
                    ->setRowList($account_info) // 设置表格数据
                    ->setTableName('stock_account_info') // 设置表格数据
                    ->fetch();
                break;
            case 'tab2':
                return ZBuilder::make('table')
                    ->setTabNav($list_tab, $group)
                    ->hideCheckbox()
                    ->addColumns([ // 批量添加列
                        ['__INDEX__', '序列'],
                        ['soruce', '证券来源'],
                        ['login_name', '证券账户'],
                        ['add_time', '操作日期','date'],
                        ['trust_time', '委托时间','time'],
                        ['gudong_code', '股东代码'],
                        ['gupiao_code', '证券代码'],
                        ['gupiao_name', '证券名称'],
                        ['flag2', '买卖标志'],
                        ['trust_price', '委托价格'],
                        ['trust_count', '委托数量'],
                        ['trust_no', '委托编号'],
                        ['volume', '成交数量'],
                        ['amount', '成交金额'],
                        ['cancel_order_count', '撤单数量'],
                        ['status', '状态说明'],
                        ['cancel_order_flag', '撤单标志'],
                        ['trust_date', '委托日期','date'],
                        ['beizhu', '备注'],
                        ['info', '状态', '保留信息'],
                    ])
                    ->setRowList($stock_trust) // 设置表格数据
                    ->setPages($page2) // 设置分页数据
                    ->fetch();
                break;
        }
        /////////////
    }*/

}
