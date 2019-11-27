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
use app\stock\model\Subaccount as SubaccountModel;
use app\stock\model\SubaccountMoney as SubaccountMoneyModel;
use app\stock\model\SubaccountRisk as SubaccountRiskModel;
use app\stock\validate\SubaccountMoney;
use app\stock\validate\SubaccountRisk;

use util\Tree;
use think\Db;
use think\Hook;

/**
 * 子账户默认控制器
 * @package app\stock\admin
 */
class Subaccount extends Admin
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
        // 数据列表
        $res=Db::name('admin_user')->where(['id'=>UID])->find();


        $data_list = SubaccountModel::getList($map,$order);

        //自定义按钮（自选股）
//        $btn_self = [
//            'title' => '自选股查询',
//            'icon'  => 'fa fa-fw fa-search',
//            'href'  => url('self', ['id' => '__id__'])
//        ];

        //自定义按钮（风控设置）
        $btn_risk = [
            'title' => '风控设置',
            'icon'  => 'fa fa-fw fa-balance-scale',
            'href'  => url('risk', ['id' => '__id__'])
        ];

        //自定义按钮（资金资金设置）
        $btn_money = [
            'title' => '资金设置',
            'icon'  => 'fa fa-fw fa-sliders',
            'href'  => url('money', ['id' => '__id__'])
        ];
        // 分页数据
        $page = $data_list->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('子账户管理') // 设置页面标题
            ->setTableName('stock_subaccount') // 设置数据表名
            ->setSearch(['sub_account' => '子账户名称'],'','',true) // 设置搜索参数
            ->addOrder('id,create_time') // 添加排序
            ->addFilter('id,sub_account') // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['sub_account', '子账户名称'],
                //['sub_pwd', '子账户密码'],
                //['user_type', '子账户类型'],
                ['relation_type', '账户模式'],
                //['name', '所属代理等级'],
                //['username', '所属代理商'],
                ['create_time', '创建时间', 'datetime'],
                //['update_time','更新时间','datetime'],
                //['status', '状态', 'switch'],
                //['prohibit_open',  '开仓',  ['禁止', '允许']],
                //['prohibit_close',  '平仓',  ['禁止', '允许']],
                ['status',  '状态',  ['未用', '已用']],
                //['info', '备注'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add') // 批量添加顶部按钮
            //->addRightButton('self', $btn_self) // 添加子账户自选股查询按钮
            ->addRightButton('risk', $btn_risk) // 添加子账户风控设置按钮
            //->addRightButton('money', $btn_money) // 添加子账户风控设置按钮
            ->addRightButton('edit') // 批量添加右侧按钮
            ->replaceRightButton(['status' => 1], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>','edit')
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
            //获取证券账户信息
            $commission = AccountModel::getAccountByID($data['account_id']);
            if($commission['broker']== -1){//如果选择的券商类型为-1
                $data['relation_type']=0;//选择的账户模式为模拟账户
            }else{
                $data['relation_type']=1;//选择的账户模式为实盘账户
            }
            // 验证
            $result = $this->validate($data, 'Subaccount.insert');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);

            //业务逻辑处理
            $subaccount =SubaccountModel::create($data);

            Db::startTrans();
            $resu = $subaccount->subaccountMoney()->save($data);//关联更新子账户资金表
            /////   更新子账户资金表 ///////////
            $Id = Db::name('stock_subaccount_money')->getLastInsID();
            $money = SubaccountMoneyModel::get($Id);
            $money['commission_scale'] = $commission['commission_scale'];//券商的佣金比例
            $money['profit_share_scale'] = 0;// 平台盈利分成
            $money['rate_scale'] = 0;//管理费分成比例
            $money['min_commission'] = $commission['min_commission'];//最低佣金 config('limit_fee');
            $res = $money->save($money);

            $result2 = $subaccount->subaccountRisk()->save($data);//关联更新子账户风控表
            $res2=false;
            if($result2){
                $RiskId = Db::name('stock_subaccount_risk')->getLastInsID();
                $Risk = SubaccountRiskModel::get($RiskId);
                $Risk['loss_warn'] = 50;
                $Risk['loss_close'] = 20;
                $Risk['position'] = 100;
                $Risk['prohibit_open'] = 1;
                $Risk['prohibit_close'] = 1;
                $Risk['prohibit_back'] = 1;
                $Risk['renewal'] = 1;
                $Risk['autoclose'] = 1;
                $res2 = $Risk->save($Risk);
            }
            ////////////

            Hook::listen('subaccount_add', $subaccount);
            // 记录行为
            $details = '新增子账户,子帐户名为：'.$subaccount['sub_account'];
            action_log('subaccount_add', 'stock_subaccount', $subaccount['id'], UID,$details);
            if($resu&&$res&&$res2){
                Db::commit();
                $this->success('新增成功', url('index'));
            }else{
                Db::rollback();
                $this->error('新增失败');
            }

        }
        $count=Db::name('stock_subaccount')->count();
        $subname=60753101+$count;
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增') // 设置页面标题
            ->addHidden('sub_pwd','123456')
            ->addFormItems([ // 批量添加表单项
                //['password:6', 'sub_pwd', '子账户密码', '<span class="text-danger">必填</span>'],
                //['linkage:6','role_id', '请选择代理等级', '', SubaccountModel::getRole(), '', url('get_agent'), 'agent_id'],
                //['select:6', 'agent_id', '请选择代理商'],
                ['hidden','role_id', '1'],
                ['hidden', 'agent_id', '1'],
                ['linkage:6', 'account_type', '请选择证券账户类型', '', SubaccountModel::getAccountList(),'', url('get_lid'), 'account_id'],
                ['select:6', 'account_id', '请选择证券账户名'],
                //['text:6', 'sub_account', '子账户名称', '<span class="text-danger">必填</span>'],
                ['text:6', 'sub_account', '子账户名称', '<span class="text-danger">系统自动生成不允许修改</span>',$subname,'','readonly="readonly"'],
                //['radio', 'user_type', '请选择子账户类型','<span class="text-danger">必填</span>',['0' => '免费体验账户', '1' => '按天/周/月分配账户', '2' => '试用配资'],1],
                //['radio', 'status', '状态', '', ['未用', '已用'],0],
                ['textarea:7', 'info', '备注信息', '']
            ])
            ->fetch();
    }

    // 根据证券账户类型获取该证券账户类型下所有的证券账户名
    public function get_lid($account_type)
    {
        $arr = [];
        $where['status'] = 1;
        $where['id'] = $account_type;
        $data_list = Db::name('stock_account')->where($where)->find();
        if(!is_null($data_list)){
            $arr['code'] = '1'; //判断状态
            $arr['msg'] = '请求成功'; //回传信息
            $result[$data_list['id']] = $data_list['lid'];
            $arr['list'] =format_linkage($result);//将一维数组转成联动需要的数据格式
        }else{
            $arr['code'] = '0'; //判断状态
            $arr['msg'] = '数据请求失败'; //回传信息
            $arr['list'] =[];//将一维数组转成联动需要的数据格式
        }

        return json($arr);
    }
    // 根据代理商获取改角色下面的所有代理商用户
    public function get_agent($role_id = '')
    {
        $arr = [];
        $where['status'] = 1;
        $where['role'] = $role_id;
        $data_list = Db::name('admin_user')->where($where)->column(true, 'id');

        if(!is_null($data_list)){
            $arr['code'] = '1'; //判断状态
            $arr['msg'] = '请求成功'; //回传信息
            foreach ($data_list as $role) {
                $result[$role['id']] = $role['username'];
            }
            $arr['list'] =format_linkage($result);//将一维数组转成联动需要的数据格式
        }else{
            $arr['code'] = '0'; //判断状态
            $arr['msg'] = '数据请求失败'; //回传信息
            $arr['list'] =[];//将一维数组转成联动需要的数据格式
        }

        return json($arr);
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
            // 验证
            $result = $this->validate($data, 'Subaccount.update');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);

            // 如果没有填写密码，则不更新密码
            if ($data['sub_pwd'] == '') {
                unset($data['sub_pwd']);
            }
            //如果未改变代理商，则不变更代理商
            if($data['agent_id']==''){
                unset($data['agent_id']);
            }
            if (SubaccountModel::update($data)) {
                $subaccount = SubaccountModel::get($data['id']);
                Hook::listen('subaccount_edit', $subaccount);
                // 记录行为
                action_log('subaccount_edit', 'stock_subaccount', $subaccount['id'], UID, $subaccount['id']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        // 获取子账户信息数据
        $info = SubaccountModel::getSubaccountById($id);
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static:6', 'relation_type', '账户模式'],
                ['static:6', 'account_id', '证券账户类型'],
                //['text:6', 'sub_account', '子账户名称', '<span class="text-danger">必填</span>'],
                ['text:6', 'sub_account', '子账户名称', '<span class="text-danger">系统自动生成不允许修改</span>','','','readonly="readonly"'],
             /*   ['password:6', 'sub_pwd', '子账户密码', '<span class="text-danger">必填</span>'],*/
                //['linkage:6','role_id', '请选择代理等级', '', SubaccountModel::getRole(), '', url('get_agent'), 'agent_id'],
                //['select:6', 'agent_id', '请选择代理商'],
                //['radio', 'user_type', '请选择子账户类型','<span class="text-danger">必填</span>',['0' => '免费体验账户', '1' => '按天/周/月配资账户', '2' => '试用配资']],
                ['radio', 'status', '状态', '', ['未用', '已用']],
                ['textarea:6', 'info', '备注信息', '']
            ])
            ->setFormData($info) // 设置表单数据
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
        Hook::listen('subaccount_delete', $ids);
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
        Hook::listen('subaccount_enable', $ids);
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
        Hook::listen('subaccount_disable', $ids);
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
        $type_name = SubaccountModel::where('id', 'in', $ids)->column('id');
        return parent::setStatus($type, ['subaccount_'.$type, 'stock_subaccount', 0, UID, implode('、', $type_name)]);
    }

    /**
     * 快速编辑
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
        $config  = SubaccountModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['subaccount_edit', 'stock_subaccount', $id, UID, $details]);
    }

    /**
     * 子账户自选股查询
     * @param int $id
     * @author 路人甲乙
     * @return mixed
     */
    public function self($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 获取查询条件
        $map = $this->getMap();

        // 获取排序
        $order = $this->getOrder();

        // 数据列表
        $data_list = SubaccountModel::getSelfByID($map,$order,$id);
        // 分页数据
        $page = $data_list->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('自选股列表') // 设置页面标题
            ->setTableName('stock_subaccount_self') // 设置数据表名
            ->addOrder('id,create_time') // 添加排序
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['sub_account', '子账户名称'],
                ['gupiao_name', '股票名称'],
                ['gupiao_code', '股票代码'],
                ['create_time', '添加时间','datetime']
            ])
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
        return $this->fetch();
    }

    /**
     * 子账户风控设置
     * @param int $id
     * @author 路人甲乙
     * @return mixed
     */
    public function risk($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'SubaccountRisk.update');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);

            if ($data['loss_warn'] == '') {
                unset($data['loss_warn']);
            }

            if($data['loss_close']==''){
                unset($data['loss_close']);
            }

            if($data['position']==''){
                unset($data['position']);
            }

            if (SubaccountRiskModel::update($data)) {
                $subaccountRisk = SubaccountRiskModel::get($data['id']);
                Hook::listen('subaccountRisk_edit', $subaccountRisk);
                // 记录行为
                $details = '预警/平仓/持仓风控设置成功';
                action_log('subaccountRisk_edit', 'SubaccountRisk', $subaccountRisk['id'], UID, $details);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取子账户风控信息数据
        $info = SubaccountRiskModel::getRiskByID($id);

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('风控参数设置') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                //['static:6', 'sub_account', '子账户名称'],
                ['number:4', 'loss_warn', '预警线（单位：%）'],
                ['number:4', 'loss_close', '平仓线（单位：%）'],
                ['number:4', 'position', '个股持仓比例（单位：%）'],
                ['radio:4','prohibit_open', '是否禁止开仓', '',['禁止开仓', '允许开仓']],
                ['radio:4', 'prohibit_close', '是否禁止平仓','',['禁止平仓', '允许平仓']],
//                ['radio', 'prohibit_back', '是否禁止撤单','', ['禁止撤单', '允许撤单']],
                ['radio:4', 'renewal', '是否开启自动续期', '', ['不开启', '开启']],
                ['radio:4', 'autoclose', '是否开启自动平仓', '', ['不开启', '开启']],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    /**
     * 子账户资金管理设置
     * @param int $id
     * @author 路人甲乙
     * @return mixed
     */
    public function money($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'SubaccountMoney.update');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);

            // 如果没有填写佣金比例，则不更新佣金比例
            if ($data['commission_scale'] == '') {
                unset($data['commission_scale']);
            }
            // 如果没有填写配资管理费分成比例，则不更新配资管理费分成比例
            if($data['rate_scale']==''){
                unset($data['rate_scale']);
            }
            // 如果没有填写盈利分成比例，则不更新盈利分成比例
            if($data['profit_share_scale']==''){
                unset($data['profit_share_scale']);
            }
            // 如果没有填写最低佣金，则不更新最低佣金
            if($data['min_commission']==''){
                unset($data['min_commission']);
            }else{
                $data['min_commission'] = $data['min_commission']*100;
            }

            if (SubaccountMoneyModel::update($data)) {
                $subaccountMoney = SubaccountMoneyModel::get($data['id']);
                Hook::listen('subaccountMoney_edit', $subaccountMoney);
                // 记录行为
                $details = '资金风控设置成功';
                action_log('subaccountMoney_edit', 'stock_subaccount_money', $subaccountMoney['id'], UID, $details);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取子账户资金信息数据
        $info = SubaccountMoneyModel::getMoneyByID($id);
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('子账户资金管理设置') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['text:6', 'commission_scale', '佣金比例（单位：万分之几）','<span class="text-danger">必填</span>'],
                ['text:6', 'min_commission', '最低佣金（单位：元）', '<span class="text-danger">必填</span>'],
                ['text:6', 'rate_scale', '配资管理费分成比例', '<span class="text-danger">必填</span>'],
                ['text:6','profit_share_scale', '盈利分成比例', '<span class="text-danger">必填</span>'],
                ['static:2', 'avail', '可用金额'],
                ['static:2', 'available_amount', '可提现金额'],
                ['static:2', 'freeze_amount', '冻结金额'],
                ['static:2', 'return_money', '盈亏金额'],
                ['static:2', 'return_rate', '盈亏利率'],
                ['static:2', 'deposit_money', '保证金'],
                ['static:2', 'borrow_money', '配资金额'],
                ['static:2', 'stock_addfinancing', '扩大配资总额'],
                ['static:2', 'stock_addmoney', '追加保证金总额'],
                ['static:2', 'stock_drawprofit', '申请提取盈利总额'],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
}
