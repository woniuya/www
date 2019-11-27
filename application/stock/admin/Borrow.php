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
use app\member\model\Member as MemberModel;
use app\stock\model\Addfinancing as AddfinancingModel;
use app\stock\model\Addmoney as AddmoneyModel;
use app\stock\model\Borrow as BorrowModel;
use app\stock\model\Drawprofit as DrawprofitModel;
use app\stock\model\Renewal as RenewalModel;
use app\stock\model\Subaccount as SubaccountModel;
use app\stock\model\SubaccountMoney;
use think\Db;
use think\Hook;
use think\Request;

/**
 * 配资默认控制器
 * @package app\stock\admin
 */
class Borrow extends Admin
{
    /**
     * 待审核的配资列表
     * @return mixed
     */
    public function index()
    {

        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map      = $this->getMap();
        $urlTypes = request()->get('type');
        if (isset($urlTypes)) {
            $map['b.type'] = $urlTypes;
        }
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        // 数据列表
        $data_list = BorrowModel::getList($map, $order);
        // 分页数据
        $page = $data_list->render();
        // 使用ZBuilder快速创建数据表格
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export.html?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addTopButton('custom', $btn_excel)
            ->setPageTitle('待审核配资列表') // 设置页面标题
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['order_id' => '订单号', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('order_id,name,create_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['order_id', '订单号'],
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['type', '配资类型'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['init_money', '总操盘金额'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束']],
                ['create_time', '申请时间', 'datetime'],
                // ['end_time','终止时间','datetime'],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButtons('edit') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function index_export()
    {
        // 获取查询条件
        $map      = $this->getMap();
        $urlTypes = request()->get('type');
        if (isset($urlTypes)) {
            $map['b.type'] = $urlTypes;
        }
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        // 数据列表
        $xlsData = BorrowModel::getList($map, $order);
        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        foreach ($xlsData as $k => $v) {
            //$v['type']=$type_arr[$v['type']];
            $v['status']      = $status_arr[$v['status']];
            $v['create_time'] = date('Y-m-d H:i', $v['create_time']);
        }
        $title     = "待审核配资申请";
        $arrHeader = array('订单号', '手机号', '配资人', '配资类型', '保证金', '配资金额', '总操盘金额', '操盘期限', '状态', '申请时间');
        $fields    = array('order_id', 'mobile', 'name', 'type', 'deposit_money', 'borrow_money', 'init_money', 'borrow_duration', 'status', 'create_time');
        export($arrHeader, $fields, $xlsData, $title);
    }

    //扩大配资申请
    public function addfinancing()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = AddfinancingModel::getAddfinancing($map, $order);
        $page      = $data_list->render();
        $btn_edit  = [
            'title' => '审核',
            'icon'  => 'fa fa-fw fa-pencil',
            'href'  => url('/stock/borrow/addfinancing_edit', ['id' => '__id__']),
        ];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export.html?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addTopButton('custom', $btn_excel)
            ->setPageTitle('扩大配资管理') // 设置页面标题
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['money', '申请保证金'],
                ['borrow_interest', '申请所需利息'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn', '预警线'],
                ['loss_close', '止损线'],
                ['borrow_duration', '使用时长'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButton('examine', $btn_edit) // 审核
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function addfinancing_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $xlsData  = AddfinancingModel::getAddfinancing($map, $order);
        $type_arr = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "扩大配资申请";
        $arrHeader = array('手机号', '配资人', '申请保证金', '申请所需利息', '配资倍数', '原操盘金额', '配资类型', '配资金额', '申请时间', '终止时间', '预警线', '止损线', '使用时长', '状态');
        $fields    = array('mobile', 'name', 'money', 'borrow_interest', 'multiple', 'init_money', 'type', 'borrow_money', 'add_time', 'end_time', 'loss_warn', 'loss_close', 'borrow_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //扩大配资审核
    public function addfinancing_edit()
    {
        $res = request();
        $id  = intval($res->param('id'));
        if (empty($id)) {$this->error('参数错误', url('addfinancing'));}
        $info = AddfinancingModel::getAddfinancingById($id);
        if (!$info) {$this->error('数据不存在', url('addfinancing'));}
        if ($res->isPost()) {
            $status = intval($res->post('status'));
            if ($status === null) {$this->error('参数错误!', url('addfinancing'));}
            $Addf_Model = new AddfinancingModel();
            $ret        = $Addf_Model->saveAddfinancing($id, $status, $info);
            if ($ret['status'] == '1' && $status == "1") {
                //配资类型  1:按天配资 2:按周配资 3:按月配资 4:试用配资 5:免息配资
                $data = [];
                switch ($info['type']) {
                    case 1:
                        $config                  = day_config();
                        $data['multiple']        = $info['multiple']; // 倍率
                        $data['deposit_money']   = $info['money'] + $info['deposit_money']; //扩大配资后的保证金
                        $loss_warn               = $config['day_loss'][0]; // 预警线
                        $loss_close              = $config['day_loss'][1]; // 止损线
                        $data['borrow_money']    = $info['money'] * $info['multiple'] + $info['borrow_money']; //扩大后的总配资金额
                        $data['loss_warn']       = ($data['deposit_money'] + $data['borrow_money']) * ($loss_warn / 100); //扩大后预警线
                        $data['loss_close']      = ($data['deposit_money'] + $data['borrow_money']) * ($loss_close / 100); //扩大后平仓线
                        $data['rate']            = $config['day_rate'][$data['multiple']]; //系统天利率
                        $data['borrow_interest'] = BorrowModel::interest($info['type'], $data['rate'], $data['borrow_money'], $info['borrow_duration']); //扩大后的管理费
                        break;
                    case 2:
                        $config                  = week_config();
                        $data['multiple']        = $info['multiple']; // 倍率
                        $data['deposit_money']   = $info['money'] + $info['deposit_money']; //扩大配资后的保证金
                        $loss_warn               = $config['week_loss'][0]; // 预警线
                        $loss_close              = $config['week_loss'][1]; // 止损线
                        $data['borrow_money']    = $info['money'] * $info['multiple'] + $info['borrow_money']; //扩大后的总配资金额
                        $data['loss_warn']       = ($data['deposit_money'] + $data['borrow_money']) * ($loss_warn / 100); //扩大后预警线
                        $data['loss_close']      = ($data['deposit_money'] + $data['borrow_money']) * ($loss_close / 100); //扩大后平仓线
                        $data['rate']            = $config['week_rate'][$data['multiple']]; //系统天利率
                        $data['borrow_interest'] = BorrowModel::interest($info['type'], $data['rate'], $data['borrow_money'], $info['borrow_duration']); //扩大后的管理费
                        break;
                    case 3:
                        $config                  = month_config();
                        $data['multiple']        = $info['multiple']; // 倍率
                        $data['deposit_money']   = $info['money'] + $info['deposit_money']; //扩大配资后的保证金
                        $loss_warn               = $config['month_loss'][0]; // 预警线
                        $loss_close              = $config['month_loss'][1]; // 止损线
                        $data['borrow_money']    = $info['money'] * $info['multiple'] + $info['borrow_money']; //扩大后的总配资金额
                        $data['loss_warn']       = ($data['deposit_money'] + $data['borrow_money']) * ($loss_warn / 100); //扩大后预警线
                        $data['loss_close']      = ($data['deposit_money'] + $data['borrow_money']) * ($loss_close / 100); //扩大后平仓线
                        $data['rate']            = $config['month_rate'][$data['multiple']]; //系统天利率
                        $data['borrow_interest'] = BorrowModel::interest($info['type'], $data['rate'], $data['borrow_money'], $info['borrow_duration']); //扩大后的管理费
                        break;
                    default:
                        $this->error('类型出错，请重新输入！');
                }

                $result = Db::name('stock_borrow')->where(['id' => $info['borrow_id']])->update($data);
                //根据佣金比例分配佣金 用户id 配资id 配资管理费
                if ($result) {
                    if ($status) {
                        $BorrowModel = new BorrowModel();
                        if ($data['borrow_interest']) {
                            $res_agent = $BorrowModel->agentToRateMoney($info['uid'], $info['id'], $info['borrow_interest'] / 100, 2);
                        }

                        $this->success($ret['msg'], url('addf_list'));
                    }
                }
            } elseif ($ret['status'] == '0') {
                $this->error($ret['msg'], url('addfinancing'));
            } else {
                $this->success($ret['msg'], url('addf_list'));
            }
        }
        $info['summoney']         = ($info['money'] + $info['borrow_interest']) / 100;
        $info['old_borrow_money'] = $info['borrow_money'] / 100;
        $info['old_init_money']   = $info['init_money'] / 100;
        $info['new_borrow_money'] = ($info['borrow_money'] + $info['money'] * $info['multiple']) / 100;
        $info['new_init_money']   = ($info['deposit_money'] + $info['money']) / 100 + $info['new_borrow_money'];
        return ZBuilder::make('form')
            ->setPageTitle('审核之后会把结果和相关信息通过手机短信和站内信通知客户') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['radio', 'status', '审核状态', '', ['未通过', '审核通过'], 0],
                ['static:4', 'summoney', '用户应交总费用', '', '', true],
                ['static:4', 'old_borrow_money', '审核前配资金额', '', '', true],
                ['static:4', 'new_borrow_money', '审核通过后配资金额', '', '', true],
                ['static:4', 'old_init_money', '审核前操盘总金额', '', '', true],
                ['static:4', 'new_init_money', '审核通过后操盘总金额', '', '', true],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    //追加保证金审核
    public function addmoney_edit()
    {
        $res = request();
        $id  = intval($res->param('id'));
        if (empty($id)) {$this->error('参数错误', url('addmoney'));}
        $info = AddmoneyModel::getAddmoneyById($id);
        if (!$info) {$this->error('数据不存在', url('addmoney'));}
        if ($res->isPost()) {
            $status = intval($res->post('status'));
            if ($status === null) {$this->error('参数错误', url('addmoney'));}
            $Addf_Model = new AddmoneyModel();
            $ret        = $Addf_Model->saveAddmoney($id, $status, $info);
            if ($ret['status'] === '1') {
                $this->success($ret['msg'], url('addmoney_list'));
            } elseif ($ret['status'] === '0') {
                $this->error($ret['msg'], url('addmoney'));
            }
        }
        $info['summoney']         = ($info['money']) / 100;
        $info['old_borrow_money'] = $info['borrow_money'] / 100;
        $info['old_init_money']   = $info['init_money'] / 100;
        $info['new_borrow_money'] = $info['borrow_money'] / 100;
        $info['new_init_money']   = ($info['init_money']) / 100;
        return ZBuilder::make('form')
            ->setPageTitle('审核之后会把结果和相关信息通过手机短信和站内信通知客户') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['radio', 'status', '审核状态', '', ['未通过', '审核通过'], 0],
                ['static:4', 'summoney', '用户应交总费用', '', '', true],
                ['static:4', 'old_borrow_money', '审核前配资金额', '', '', true],
                ['static:4', 'new_borrow_money', '审核通过后配资金额', '', '', true],
                ['static:4', 'old_init_money', '审核前操盘总金额', '', '', true],
                ['static:4', 'new_init_money', '审核通过后操盘总金额', '', '', true],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    //提取盈利审核
    public function drawprofit_edit()
    {
        $res = request();
        $id  = intval($res->param('id'));
        if (empty($id)) {$this->error('参数错误', url('drawprofit'));}
        $info = DrawprofitModel::getDrawprofitById($id);
        if (!$info) {$this->error('数据不存在', url('drawprofit'));}
        if ($res->isPost()) {
            $status = intval($res->post('status'));
            if ($status === null) {$this->error('参数错误', url('drawprofit'));}
            $Addf_Model = new DrawprofitModel();
            $ret        = $Addf_Model->saveDrawprofit($id, $status, $info);
            if ($ret['status'] === '1') {
                $this->success($ret['msg'], url('drawprofit_list'));
            } elseif ($ret['status'] === '0') {
                $this->error($ret['msg'], url('drawprofit'));
            }
        }
        $info['summoney']         = ($info['money']) / 100;
        $info['available_amount'] = money_convert($info['available_amount']);

        return ZBuilder::make('form')
            ->setPageTitle('审核之后会把结果和相关信息通过手机短信和站内信通知客户') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['radio', 'status', '审核状态', '', ['未通过', '审核通过'], 0],
                ['static:4', 'summoney', '提取盈利金额', '', '', true],
                ['static:4', 'available_amount', '审核前可提取盈利金额', '', '', true],
                ['textarea', 'remark', '备注', ''],

            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    //续期审核
    public function renewal_edit()
    {
        $res = request();
        $id  = intval($res->param('id'));
        if (empty($id)) {$this->error('参数错误', url('renewal'));}
        $info = RenewalModel::getRenewalById($id);

        if (!$info) {$this->error('数据不存在', url('renewal'));}
        if ($res->isPost()) {
            $status = intval($res->post('status'));
            if ($status === null) {$this->error('参数错误', url('renewal'));}
            $Renewal_Model = new RenewalModel();
            $ret           = $Renewal_Model->saveRenewal($id, $status, $info);

            if ($ret['status'] === '1') {
                //根据佣金比例分配佣金 用户id 配资id 配资管理费
                if ($status) {
                    $BorrowModel = new BorrowModel();
                    if ($info['borrow_fee']) {
                        $res_agent = $BorrowModel->agentToRateMoney($info['uid'], $info['id'], $info['borrow_fee'] / 100, 3);
                    }
                }

                $this->success($ret['msg'], url('renewal_list'));
            } elseif ($ret['status'] === '0') {
                $this->error($ret['msg'], url('renewal'));
            }
        }
        $info['summoney']         = ($info['borrow_fee']) / 100;
        $info['old_borrow_money'] = $info['borrow_money'] / 100;
        $info['old_init_money']   = $info['init_money'] / 100;
        $info['new_borrow_money'] = $info['borrow_money'] / 100;
        $info['new_init_money']   = ($info['init_money']) / 100;
        return ZBuilder::make('form')
            ->setPageTitle('审核之后会把结果和相关信息通过手机短信和站内信通知客户') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['radio', 'status', '审核状态', '', ['未通过', '审核通过'], 0],
                ['static:4', 'summoney', '用户应交总费用', '', '', true],
                ['static:4', 'old_borrow_money', '审核前配资金额', '', '', true],
                ['static:4', 'new_borrow_money', '审核通过后配资金额', '', '', true],
                ['static:4', 'old_init_money', '审核前操盘总金额', '', '', true],
                ['static:4', 'new_init_money', '审核通过后操盘总金额', '', '', true],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    //提前终止审核
    public function stop_edit()
    {
        $res = request();
        $id  = intval($res->param('id'));
        if (empty($id)) {$this->error('参数错误', url('stop'));}
        $info = RenewalModel::getStopById($id);
        if (!$info) {$this->error('数据不存在', url('stop'));}
        if ($res->isPost()) {
            $status = intval($res->post('status'));
            if ($status === null) {$this->error('参数错误', url('stop'));}

            $submoney_info = SubaccountMoney::getMoneyByID($info['stock_subaccount_id']);
            if ($status === 1) {
                $check_res = BorrowModel::checkApply($info['borrow_id'], $info['uid'], $info['stock_subaccount_id']);
                if ($check_res['status'] === 0) {$this->error($check_res['msg'], url('stop'));}
                if ($submoney_info['freeze_amount'] > 0.1) {
                    $this->error('该子账户还有资金未解冻，请等资金解冻后再审核', url('stop'));
                }
            }
            $surplus_money = $submoney_info['avail'] * 100; //返回的可用余额
            $addmoney      = $submoney_info['stock_addmoney'] * 100; //累计追加保证金
            $drawprofit    = $submoney_info['stock_drawprofit'] * 100; //累计提取盈利

            $Renewal_Model = new RenewalModel();
            $ret           = $Renewal_Model->saveStop($id, $status, $info, $surplus_money, $addmoney, $drawprofit);
            if ($ret['status'] === '1') {
                $this->success($ret['msg'], url('stop_list'));
            } elseif ($ret['status'] === '0') {
                $this->error($ret['msg'], url('stop'));
            }
        }
        $info['summoney']         = ($info['borrow_fee']) / 100;
        $info['old_borrow_money'] = $info['borrow_money'] / 100;
        $info['old_init_money']   = $info['init_money'] / 100;
        $info['new_borrow_money'] = $info['borrow_money'] / 100;
        $info['new_init_money']   = ($info['init_money']) / 100;
        return ZBuilder::make('form')
            ->setPageTitle('审核之后会把结果和相关信息通过手机短信和站内信通知客户') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['radio', 'status', '审核状态', '', ['未通过', '审核通过'], 0],
                ['static:4', 'summoney', '用户应交总费用', '', '', true],
                ['static:4', 'old_borrow_money', '审核前配资金额', '', '', true],
                ['static:4', 'new_borrow_money', '审核通过后配资金额', '', '', true],
                ['static:4', 'old_init_money', '审核前操盘总金额', '', '', true],
                ['static:4', 'new_init_money', '审核通过后操盘总金额', '', '', true],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    //追加配资列表
    public function addf_list()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = AddfinancingModel::getAddfList($map, $order);
        $page      = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('追加配资列表') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['money', '申请保证金'],
                ['borrow_interest', '配资所需利息'],
                ['multiple', '配资倍数'],
                ['last_deposit_money', '原保证金金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn', '现预警线'],
                ['loss_close', '现止损线'],
                ['borrow_duration', '使用时长'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function addf_list_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData    = AddfinancingModel::getAddfList($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            //$v['type']=$type_arr[$v['type']];
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);

        }
        $title     = "追加配资列表";
        $arrHeader = array('手机号', '配资人', '申请保证金', '配资所需利息', '配资倍数', '原保证金金额', '配资类型', '配资金额', '申请时间', '终止时间', '现预警线', '现止损线', '使用时长', '状态');
        $fields    = array('mobile', 'name', 'money', 'borrow_interest', 'multiple', 'last_deposit_money', 'type', 'borrow_money', 'add_time', 'end_time', 'loss_warn', 'loss_close', 'borrow_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    //操盘中配资
    public function lists()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map             = $this->getMap();
        $map['end_time'] = array('>=', time());
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = "b.verify_time desc, b.id desc";
        }
        // 数据列表
        $data_list = BorrowModel::getList($map, $order);
        // 分页数据
        $page       = $data_list->render();
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        $status_str = $status_arr[input('status')];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -14) . "_export?status=1";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -14) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addTopButton('custom', $btn_excel)
            ->setPageTitle($status_str . '配资列表') // 设置页面标题
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['order_id' => '订单号', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('order_id,name,verify_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['order_id', '订单号'],
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['type', '配资类型'],
                ['sub_account', '子账号'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['init_money', '总操盘金额'],
                ['operation', '距离预警线'],
                ['close', '距离平仓线'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束']],
                ['create_time', '申请时间', 'datetime'],
                ['verify_time', '开始时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                //['right_button', '操作', 'btn']
            ])
            ->addRightButtons('edit') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->js('admin_borrow_test')
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function lists_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = "b.verify_time desc";
        }
        // 数据列表
        $xlsData = BorrowModel::getList($map, $order);
        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        foreach ($xlsData as $k => $v) {
            //$v['type']=$type_arr[$v['type']];
            $v['status']      = $status_arr[$v['status']];
            $v['create_time'] = date('Y-m-d H:i', $v['create_time']);
        }
        $title     = "操盘中配资";
        $arrHeader = array('订单号', '手机号', '配资人', '配资类型', '子账号', '保证金', '配资金额', '总操盘金额', '操盘期限', '状态', '申请时间');
        $fields    = array('order_id', 'mobile', 'name', 'type', 'sub_account', 'deposit_money', 'borrow_money', 'init_money', 'borrow_duration', 'status', 'create_time');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //已结束配资
    public function lists_2()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        // 数据列表
        $data_list = BorrowModel::getList($map, $order);

        // 分页数据
        $page = $data_list->render();

        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        $status_str = $status_arr[input('status')];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -14) . "_export?status=2";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -14) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addTopButton('custom', $btn_excel)
            ->setPageTitle($status_str . '配资列表') // 设置页面标题
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['order_id' => '订单号', 'mobile' => '手机号', 'type' => '配资类型'], '', '', true) // 设置搜索参数
            ->addOrder('order_id,name,create_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['order_id', '订单号'],
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['type', '配资类型'],
                ['sub_account', '子账号'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['init_money', '总操盘金额'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束']],
                ['create_time', '申请时间', 'datetime'],
                ['verify_time', '开始时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                //['right_button', '操作', 'btn']
            ])
            ->addRightButtons('edit') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function lists_2_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        // 数据列表
        $xlsData = BorrowModel::getList($map, $order);
        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        foreach ($xlsData as $k => $v) {
            //$v['type']=$type_arr[$v['type']];
            $v['status']      = $status_arr[$v['status']];
            $v['create_time'] = date('Y-m-d H:i', $v['create_time']);
            $v['verify_time'] = date('Y-m-d H:i', $v['verify_time']);
            $v['end_time']    = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "已结束的配资";
        $arrHeader = array('订单号', '手机号', '配资人', '配资类型', '子账号', '保证金', '配资金额', '总操盘金额', '操盘期限', '状态', '申请时间', '开始时间', '终止时间');
        $fields    = array('order_id', 'mobile', 'name', 'type', 'sub_account', 'deposit_money', 'borrow_money', 'init_money', 'borrow_duration', 'status', 'create_time', 'verify_time', 'end_time');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //未通过配资
    public function lists_0()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        // 数据列表
        $data_list = BorrowModel::getList($map, $order);

        // 分页数据
        $page = $data_list->render();

        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        $status_str = $status_arr[input('status')];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -14) . "_export?status=0";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -14) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addTopButton('custom', $btn_excel)
            ->setPageTitle($status_str . '配资列表') // 设置页面标题
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['order_id' => '订单号', 'mobile' => '手机号', 'type' => '配资类型'], '', '', true) // 设置搜索参数
            ->addOrder('order_id,name,create_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['order_id', '订单号'],
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['type', '配资类型'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['init_money', '总操盘金额'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束']],
                ['create_time', '申请时间', 'datetime'],
                ['verify_time', '开始时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                //['right_button', '操作', 'btn']
            ])
            ->addRightButtons('edit') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function lists_0_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = "b.verify_time desc";
        }
        // 数据列表
        $xlsData = BorrowModel::getList($map, $order);
        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        foreach ($xlsData as $k => $v) {
            //$v['type']=$type_arr[$v['type']];
            $v['status']      = $status_arr[$v['status']];
            $v['create_time'] = date('Y-m-d H:i', $v['create_time']);
            $v['verify_time'] = date('Y-m-d H:i', $v['verify_time']);
            $v['end_time']    = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "未通过的配资";
        $arrHeader = array('订单号', '手机号', '配资人', '配资类型', '保证金', '配资金额', '总操盘金额', '操盘期限', '状态', '申请时间', '开始时间', '终止时间');
        $fields    = array('order_id', 'mobile', 'name', 'type', 'deposit_money', 'borrow_money', 'init_money', 'borrow_duration', 'status', 'create_time', 'verify_time', 'end_time');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //即将到期
    public function soonexpire()
    {

        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $map = $this->getMap();
        if (empty($map)) {
            $map['b.end_time'] = ['>', time() - 259200];
        }
        $order     = $this->getOrder();
        $data_list = BorrowModel::getSoonExpire($map, $order);

        // dump($data_list);exit;
        // 分页数据
        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        return ZBuilder::make('table')
            ->setPageTitle('即将到期配资列表') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['order_id' => '订单号', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('order_id,name,create_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['order_id', '订单号'],
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['type', '配资类型'],
                ['sub_account', '子账号'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['init_money', '总操盘金额'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束']],
                ['verify_time', '开始时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function soonexpire_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        if (empty($map)) {
            $map['b.end_time'] = ['>', time() - 259200];
        }
        $order = $this->getOrder();
        // 数据列表
        $xlsData = BorrowModel::getSoonExpire($map, $order);
        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '操盘中', '2' => '已结束'];
        foreach ($xlsData as $k => $v) {
            //$v['type']=$type_arr[$v['type']];
            $v['status']      = $status_arr[$v['status']];
            $v['verify_time'] = date('Y-m-d H:i', $v['verify_time']);
            $v['end_time']    = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "即将到期的配资";
        $arrHeader = array('订单号', '手机号', '配资人', '配资类型', '子账号', '保证金', '配资金额', '总操盘金额', '操盘期限', '状态', '开始时间', '终止时间');
        $fields    = array('order_id', 'mobile', 'name', 'type', 'sub_account', 'deposit_money', 'borrow_money', 'init_money', 'borrow_duration', 'status', 'verify_time', 'end_time');
        export($arrHeader, $fields, $xlsData, $title);
    }

    /*
     * 到期配资
     *
     */
    public function endlist()
    {

        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.end_time desc';
        }
        $data_list = BorrowModel::getEnd($map, $order);
        // 分页数据
        $page = $data_list->render();

        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export.html?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        $btn_edit = [
            'title' => '结算',
            'icon'  => 'fa fa-fw fa-pencil',
            'href'  => url('/stock/borrow/endedit', ['id' => '__id__']),
        ];
        return ZBuilder::make('table')
            ->setPageTitle('已到期配资列表') // 设置页面标题
            ->addTopButton('access', $btn_excel)
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['order_id' => '订单号', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('order_id,name,create_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['order_id', '订单号'],
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['type', '配资类型'],
                ['sub_account', '子账号'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['init_money', '总操盘金额'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['-1' => '待审核', '0' => '未通过', '1' => '待结算', '2' => '已结束', '3' => '已逾期']],
                ['create_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButton('examine', $btn_edit) // 审核
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function endlist_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'b.create_time desc';
        }
        $xlsData = BorrowModel::getEnd($map, $order);

        //$type_arr=['5'=>'免息配资','1'=>'按天配资','2'=>'按周配资','3'=>'按月配资','4'=>'免费体验','6'=>'模拟操盘'];
        $status_arr = ['-1' => '待审核', '0' => '未通过', '1' => '待结算', '2' => '已结束'];
        foreach ($xlsData as $k => $v) {
            //$v['type']=$type_arr[$v['type']];
            $v['status']      = $status_arr[$v['status']];
            $v['create_time'] = date('Y-m-d H:i', $v['create_time']);
            $v['end_time']    = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "到期配资";
        $arrHeader = array('订单号', '手机号', '配资人', '配资类型', '子账号', '保证金', '配资金额', '总操盘金额', '操盘期限', '状态', '申请时间', '终止时间');
        $fields    = array('order_id', 'mobile', 'name', 'type', 'sub_account', 'deposit_money', 'borrow_money', 'init_money', 'borrow_duration', 'status', 'create_time', 'end_time');
        export($arrHeader, $fields, $xlsData, $title);
    }
    /*
     *  处理到期配资
     *
     */
    public function endedit()
    {
        $res      = request();
        $id       = intval($res->param('id'));
        $stop_res = RenewalModel::stopfind($id);
        if (!empty($stop_res)) {
            $this->error('该用户有未处理的提前终止申请，请处理!');
        }
        $info = BorrowModel::getBorrowById($id);
        if (empty($info)) {$this->error("数据不存在!");}
        $check_res = BorrowModel::checkApply($info['id'], $info['member_id'], $info['stock_subaccount_id']);
        if ($check_res['status'] === 0) {$this->error($check_res['msg'], url('endlist'));}
        if ($res->isPost()) {
            $sum_status = intval($res->post('sum_status'));
            if ($sum_status === 1) {
                $submoney_info = SubaccountMoney::getMoneyByID($info['stock_subaccount_id']);
                $surplus_money = $submoney_info['avail'] * 100; //返回的可用余额
                $addmoney      = $submoney_info['stock_addmoney'] * 100; //累计追加保证金
                $drawprofit    = $submoney_info['stock_drawprofit'] * 100; //累计提取盈利
                $contents      = "配资使用期限结束";
                $renewal       = new RenewalModel();
                Db::startTrans();
                $ret = $renewal->settlementFinancing($surplus_money, $addmoney, $drawprofit, $id);
                if ($ret) {
                    Db::commit();
                    $u_info = MemberModel::getMemberInfoByID($info['member_id']);
                    if ($u_info['mobile']) {

                        $var        = $u_info['mobile'];
                        $contentarr = getconfigSms_status(['name' => 'stock_borrow_endedit']);
                        $content    = str_replace(array("#var#"), array($var), $contentarr['value']);
                        if ($contentarr['status'] == 1) {
                            sendsms_mandao('', $content, '');
                        }
                    }
                    $this->success('结算成功!', url('endlist'));
                } else {
                    Db::rollback();
                    $this->error("提交失败!", url('endlist'));
                }
            } else {
                $this->success("", url('endlist'));
            }
        }
        $this->assign("id", intval($id));
        return ZBuilder::make('form')
            ->setPageTitle('') // 设置页面标题
            ->method('post')
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['radio', 'sum_status', '是否结算', '', ['不结算', '确认结算'], 0],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    //追加保证金申请
    public function addmoney()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = AddmoneyModel::getAddmoney($map, $order);
        $page      = $data_list->render();
        // 使用ZBuilder快速创建数据表格
        $btn_edit = [
            'title' => '审核',
            'icon'  => 'fa fa-fw fa-pencil',
            'href'  => url('/stock/borrow/addmoney_edit', ['id' => '__id__']),
        ];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        return ZBuilder::make('table')
            ->setPageTitle('待审核的追加保证金') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['deposit_money', '原保证金'],
                ['money', '追加保证金'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验']],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn', '预警比例'],
                ['loss_close', '止损比例'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButton('examine', $btn_edit) // 审核
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function addmoney_export()
    {
        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData    = AddmoneyModel::getAddmoney($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);

        }
        $title     = "追加保证金申请";
        $arrHeader = array('手机号', '配资人', '原保证金', '追加保证金', '配资倍数', '原操盘金额', '配资类型', '配资金额', '申请时间', '终止时间', '预警比例', '止损比例', '操盘期限', '状态');
        $fields    = array('mobile', 'name', 'deposit_money', 'money', 'multiple', 'init_money', 'type', 'borrow_money', 'add_time', 'end_time', 'loss_warn', 'loss_close', 'borrow_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //追加配资列表
    public function addmoney_list()
    {
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = AddmoneyModel::getAddmoneyList($map, $order);
        $page      = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('追加保证金记录') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['deposit_money', '原保证金'],
                ['money', '追加保证金'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn', '预警比例'],
                ['loss_close', '止损比例'],
                ['borrow_duration', '使用时长'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function addmoney_list_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData = AddmoneyModel::getAddmoneyList($map, $order);

        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);

        }
        $title     = "追加保证金记录";
        $arrHeader = array('手机号', '配资人', '原保证金', '追加保证金', '配资倍数', '原操盘金额', '配资类型', '配资金额', '申请时间', '终止时间', '预警比例', '止损比例', '操盘期限', '状态');
        $fields    = array('mobile', 'name', 'deposit_money', 'money', 'multiple', 'init_money', 'type', 'borrow_money', 'add_time', 'end_time', 'loss_warn', 'loss_close', 'borrow_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //续期申请
    public function renewal()
    {
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = RenewalModel::getRenewal($map, $order);
        $page      = $data_list->render();
        $btn_edit  = [
            'title' => '审核',
            'icon'  => 'fa fa-fw fa-pencil',
            'href'  => url('/stock/borrow/renewal_edit', ['id' => '__id__']),
        ];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('延期申请管理') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['borrow_duration', '申请时长'],
                ['borrow_fee', '申请服务费'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn_money', '预警线'],
                ['loss_close_money', '止损线'],
                ['y_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButton('examine', $btn_edit) // 审核
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function renewal_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData    = RenewalModel::getRenewal($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '配资倍数', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "续期申请";
        $arrHeader = array('手机号', '配资人', '申请时长', '申请服务费', '配资倍数', '原操盘金额', '配资类型', '保证金', '配资金额', '申请时间', '终止时间', '预警线', '止损线', '操盘期限', '状态');
        $fields    = array('mobile', 'name', 'borrow_duration', 'borrow_fee', 'multiple', 'init_money', 'type', 'deposit_money', 'borrow_money', 'add_time', 'end_time', 'loss_warn_money', 'loss_close_money', 'y_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //续期记录
    public function renewal_list()
    {
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = RenewalModel::getRenewalList($map, $order);
        $page      = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('续期记录列表') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_borrow') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['borrow_fee', '申请服务费'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn_money', '预警线'],
                ['loss_close_money', '止损线'],
                ['b_duration', '使用时长'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
            ])
            ->addRightButtons('edit') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function renewal_list_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }

        // 数据列表
        $xlsData    = RenewalModel::getRenewalList($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '配资倍数', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "续期记录";
        $arrHeader = array('手机号', '配资人', '申请服务费', '配资倍数', '原操盘金额', '配资类型', '保证金', '配资金额', '申请时间', '终止时间', '预警线', '止损线', '操盘期限', '状态');
        $fields    = array('mobile', 'name', 'borrow_fee', 'multiple', 'init_money', 'type', 'deposit_money', 'borrow_money', 'add_time', 'end_time', 'loss_warn_money', 'loss_close_money', 'b_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //提前终止申请
    public function stop()
    {
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = RenewalModel::getStop($map, $order);
        $page      = $data_list->render();
        $btn_edit  = [
            'title' => '审核',
            'icon'  => 'fa fa-fw fa-pencil',
            'href'  => url('/stock/borrow/stop_edit', ['id' => '__id__']),
        ];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('终止操盘管理') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_renewal') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['borrow_fee', '申请服务费'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn_money', '预警线'],
                ['loss_close_money', '止损线'],
                ['b_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButton('examine', $btn_edit) // 审核
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function stop_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData    = RenewalModel::getStop($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '配资倍数', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "终止操盘";
        $arrHeader = array('手机号', '配资人', '申请服务费', '配资倍数', '原操盘金额', '配资类型', '保证金', '配资金额', '申请时间', '终止时间', '预警线', '止损线', '操盘期限', '状态');
        $fields    = array('mobile', 'name', 'borrow_fee', 'multiple', 'init_money', 'type', 'deposit_money', 'borrow_money', 'add_time', 'end_time', 'loss_warn_money', 'loss_close_money', 'b_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //提前终止记录
    public function stop_list()
    {
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = RenewalModel::getStopList($map, $order);

        $page = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('提前终止记录列表') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_renewal') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['borrow_fee', '申请服务费'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn', '预警线'],
                ['loss_close', '止损线'],
                ['b_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

    public function stop_list_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData    = RenewalModel::getStopList($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '配资倍数', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "提前终止记录列表";
        $arrHeader = array('手机号', '配资人', '申请服务费', '配资倍数', '原操盘金额', '配资类型', '保证金', '配资金额', '申请时间', '终止时间', '预警线', '止损线', '操盘期限', '状态');
        $fields    = array('mobile', 'name', 'borrow_fee', 'multiple', 'init_money', 'type', 'deposit_money', 'borrow_money', 'add_time', 'end_time', 'loss_warn', 'loss_close', 'b_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }

    //提取盈利申请
    public function drawprofit()
    {
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = DrawprofitModel::getDrawprofit($map, $order);
        $page      = $data_list->render();
        $btn_edit  = [
            'title' => '审核',
            'icon'  => 'fa fa-fw fa-pencil',
            'href'  => url('/stock/borrow/drawprofit_edit', ['id' => '__id__']),
        ];
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('提取收益管理') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_drawprofit') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['avail', '可用余额'],
                ['available_amount', '可提取收益'],
                ['money', '申请提取金额'],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['init_money', '操盘资金'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['add_time', '申请时间', 'datetime'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButton('examine', $btn_edit) // 审核
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function drawprofit_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData    = DrawprofitModel::getDrawprofit($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '配资倍数', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            //   $v['end_time']=date('Y-m-d H:i',$v['end_time']);
        }
        $title     = "提取盈利申请";
        $arrHeader = array('手机号', '配资人', '可用余额', '可提取收益', '申请提取金额', '保证金', '配资金额', '操盘资金', '配资类型', '申请时间', '状态');
        $fields    = array('mobile', 'name', 'avail', 'available_amount', 'money', 'deposit_money', 'borrow_money', 'init_money', 'type', 'add_time', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    //提取盈利记录
    public function drawprofit_list()
    {
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        $data_list = DrawprofitModel::getDrawprofitList($map, $order);
        $page      = $data_list->render();
        if (empty($_SERVER["QUERY_STRING"])) {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 0, -
                5) . "_export";
        } else {
            $excel_url = substr(http() . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"], 0, -
                5) . "_export?" . $_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url, '', ''),
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('提取盈利记录') // 设置页面标题
            ->addTopButton('custem', $btn_excel)
            ->setTableName('stock_drawprofit') // 设置数据表名
            ->setSearch(['name' => '配资人', 'mobile' => '手机号'], '', '', true) // 设置搜索参数
            ->addOrder('mobile,name,add_time') // 添加排序
            ->addFilter('type', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']) // 添加筛选
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '手机号'],
                ['name', '配资人'],
                ['money', '提取盈利金额'],
                ['multiple', '配资倍数'],
                ['init_money', '原操盘金额'],
                ['type', '配资类型', ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘']],
                ['deposit_money', '保证金'],
                ['borrow_money', '配资金额'],
                ['add_time', '申请时间', 'datetime'],
                ['end_time', '终止时间', 'datetime'],
                ['loss_warn', '预警线'],
                ['loss_close', '止损线'],
                ['borrow_duration', '操盘期限'],
                ['status', '状态', 'status', '', ['0' => '待审核', '1' => '已通过', '2' => '未通过']],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
    public function drawprofit_list_export()
    {
        // 获取查询条件
        $map   = $this->getMap();
        $order = $this->getOrder();
        if (empty($order)) {
            $order = 'a.id desc';
        }
        // 数据列表
        $xlsData    = DrawprofitModel::getDrawprofitList($map, $order);
        $type_arr   = ['5' => '免息配资', '1' => '按天配资', '2' => '按周配资', '3' => '按月配资', '4' => '免费体验', '6' => '模拟操盘'];
        $status_arr = ['0' => '待审核', '1' => '已通过', '2' => '未通过'];
        foreach ($xlsData as $k => $v) {
            $v['type']     = $type_arr[$v['type']];
            $v['status']   = $status_arr[$v['status']];
            $v['add_time'] = date('Y-m-d H:i', $v['add_time']);
            $v['end_time'] = date('Y-m-d H:i', $v['end_time']);
        }
        $title     = "提取盈利记录";
        $arrHeader = array('手机号', '配资人', '提取盈利金额', '配资倍数', '原操盘金额', '配资类型', '保证金', '配资金额', '申请时间', '终止时间', '预警线', '止损线', '操盘期限', '状态');
        $fields    = array('mobile', 'name', 'money', 'multiple', 'init_money', 'type', 'deposit_money', 'borrow_money', 'add_time', 'end_time', 'loss_warn', 'loss_close', 'borrow_duration', 'status');
        export($arrHeader, $fields, $xlsData, $title);
    }
    /*
     * 编辑
     * @param null $id 角色id
     * @author 路人甲乙
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) {
            $this->error('缺少参数');
        }

        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data['type'] == "模拟操盘" && $data['trade_type'] == 1) {
                $this->error("模拟操盘配资不能选择实盘子账户！");
            }

            $data['stock_subaccount_id'] = $data['stock_subaccount_id_r'];
            $data['status']              = $data['v_status'];
            if ($data['status'] == 1) {
                //$data['stock_subaccount_id'] = !$data['trade_type'] ? $data['stock_subaccount_id_v'] :$data['stock_subaccount_id_r'];
                $data['stock_subaccount_id'] = $data['stock_subaccount_id_r'];
                $result                      = $this->validate($data, 'Borrow.audit');
                if (true !== $result) {
                    $this->error($result);
                }

            }

            $BorrowModel = new BorrowModel();
            //临时测试 审核不通过也分配佣金

            $result = $BorrowModel->saveBorrow($data);
            $member = MemberModel::getMemberInfoByID($data['member_id']);
            $mobile = $member['mobile'];
            $infos  = BorrowModel::get($data['id']); //短信用到的信息
            if ($result['status'] == 1) {
                //根据佣金比例分配佣金 用户id 配资id 配资管理费
                if ($data['borrow_interest']) {
                    $res_agent = $BorrowModel->agentToRateMoney($data['member_id'], $data['id'], $data['borrow_interest'] / 100);
                }
                //申请配资审核通过-短信通知
                $browid = $data['id'];
                $info   = BorrowModel::getEditBorrow($browid); //获取配资信息
                self::borrow_audite_sms($mobile, 'stock_auditing', $infos);
                $this->success($result['msg'], url('index'));

            } elseif ($result['status'] == 2) {
                self::borrow_audite_sms($mobile, 'stock_auditing_fail', $infos);
                $this->success($result['msg'], url('index'));
            } else {
                self::borrow_audite_sms($mobile, 'stock_auditing_fail', $infos);

                $this->error($result['msg']);
            }
            // 记录行为
            action_log('borrow_edit', 'borrow', $id, UID, $data['id']);
        }
        // 获取数据
        $info  = BorrowModel::getEditBorrow($id); //获取配资信息
        $agent = isset($info['agent']) ? $info['agent'] : "无代理商";
        //$accountList = SubaccountModel::all(); //获取证券账户类型
        $init_money = $info['init_money'];
        $sub_arr    = SubaccountModel::getSelaccountList(0, $init_money);
        $lack       = '';
        if ($sub_arr['lack'] != '') {
            $lack = $sub_arr['lack'];
            unset($sub_arr['lack']);
        } else {
            unset($sub_arr['lack']);
        }

        $jsVarHtml = "<script>var riskUrl = '" . url('risk') . "';</script>";
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('审核之后会把结果和相关信息通过手机短信和站内信通知客户') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['hidden', 'member_id'],
                ['hidden', 'borrow_duration'],
                ['hidden', 'member_id'],
                ['hidden', 'type'],
                ['radio', 'v_status', '审核状态', '', ['未通过', '审核通过'], 0],
                //['radio', 'trade_type', '子账户类型', '请选择子账户类型', ['模拟账户', '实盘账户'], 0],
                //['select', 'stock_subaccount_id_v', '请选择模拟子账户', '分配给配资人的模拟盘子账户  <span style="color:#ff0000">注意：子账户代理商要和会员所属代理商一致才允许选择 当前会员代理商:' . $agent . '</span>', SubaccountModel::getSelaccountList(0, $init_money, $info['pid'])],
                //['select', 'stock_subaccount_id_r', '请选择实盘子账户', '分配给配资人的实盘证券子账户 <span style="color:#ff0000">注意：子账户代理商要和会员所属代理商一致才允许选择 当前会员代理商:' . $agent . '</span>', SubaccountModel::getSelaccountList(1, $init_money, $info['pid'])],
                ['select', 'stock_subaccount_id_r', '请选择子账户', '分配给配资人的证券子账户  <span style="color:#ff0000">' . $lack . '</span>', $sub_arr],
                ['textarea', 'contents', '审核意见', ''],
                ['static:4', 'type', '配资类型', '', '', true],
                ['static:4', 'init_money', '总操盘金额', '', '', true],
                ['static:4', 'deposit_money', '保证金', '', '', true],
                ['static:4', 'borrow_money', '配资金额', '', '', true],
                ['static:4', 'borrow_interest', '配资管理费', '', '', true],
            ])
            ->addGroup(
                [
                    '配资信息'  => [
                        ['static:3', 'type', '配资类型', ''],
                        ['static:3', 'init_money', '股票初始资金', ''],
                        ['static:3', 'deposit_money', '保证金', ''],
                        ['static:3', 'borrow_money', '融资资金', ''],
                        ['static:3', 'borrow_interest', '配资管理费', ''],
                    ],
                    '风控信息'  => [
                        ['text:4', 'loss_warn', '警告线', '%'],
                        ['text:4', 'loss_close', '止损线', '%'],
                        ['text:4', 'position', '个股持仓比例', '（单位：%），区间0-100，100表示不限仓'],
                        ['radio:4', 'prohibit_open', '是否禁止开仓', '', ['1' => '允许开仓', '0' => '禁止开仓']],
                        ['radio:4', 'prohibit_close', '是否禁止平仓', '', ['1' => '允许平仓', '0' => '禁止平仓']],
                        //['radio:4', 'prohibit_back', '是否禁止撤单', '', ['1'=>'允许撤单',  '0'=>'禁止撤单']],
                        ['radio:4', 'renewal', '是否开启自动续期 ', '', ['0' => '不开启', '1' => '开启']],
                        ['radio:4', 'autoclose', '是否开启自动平仓 ', '', ['0' => '不开启', '1' => '开启']],
                    ],
                    '收费方案'  => [
                        ['text:4', 'commission_scale', '佣金比例', '（单位：万分之几） 如：5 代表万分之五'],
                        ['text:4', 'min_commission', '最低佣金', '（单位：元）'],
                        ['text:4', 'rate_scale', '配资管理费分成比例', '（单位：%）'],
                        ['text:4', 'profit_share_scale', '盈利分成比例', '（单位：%）'],
                    ],
                    '所属代理商' => [
                        ['static:4', 'agent', '所属代理', ''],
                    ],
                ]
            )
            ->setFormData($info) // 设置表单数据
        //->setTrigger('v_status', '1', 'trade_type')
            ->setTrigger('v_status', '1', 'stock_subaccount_id_r')
            ->setTrigger('trade_type', '0', 'stock_subaccount_id_v')
            ->setTrigger('trade_type', '1', 'stock_subaccount_id_r')
            ->setTrigger('trade_type', '0', 'builder-form-group-tab')
            ->setExtraHtml($jsVarHtml)
            ->js('admin_borrow')
            ->fetch();
    }
    public static function borrow_audite_sms($mobile, $type, $info)
    {

        $contentarr = getconfigSms_status(['name' => $type]);
        $content    = str_replace(array("#var#", "#order_id#"), array($mobile, $info['order_id']), $contentarr['value']);
        if ($contentarr['status'] == 1) {
            sendsms_mandao($mobile, $content, 'user');
        }
    }
    /**
     * 删除券商类型
     * @param array $ids 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function delete($ids = [])
    {
        Hook::listen('borrow_delete', $ids);
        return $this->setStatus('delete');
    }

    /**
     * 启用券商类型
     * @param array $ids 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function enable($ids = [])
    {
        Hook::listen('borrow_enable', $ids);
        return $this->setStatus('enable');
    }

    /**
     * 禁用券商类型
     * @param array $ids 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function disable($ids = [])
    {
        Hook::listen('borrow_disable', $ids);
        return $this->setStatus('disable');
    }

    /**
     * 设置券商类型状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @author 路人甲乙
     * @return mixed
     */
    public function setStatus($type = '', $record = [])
    {
        $ids       = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $type_name = BrokerModel::where('id', 'in', $ids)->column('id');
        return parent::setStatus($type, ['borrow_' . $type, 'stock_borrow', 0, UID, implode('、', $type_name)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志
     * @author 路人甲乙
     * @return mixed
     */
    public function quickEdit($record = [])
    {
        $id = input('post.pk', '');
        //$id      == UID && $this->error('禁止操作当前账号');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $config  = BorrowModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['borrow_edit', 'stock_borrow', $id, UID, $details]);
    }

    public function risk($id = null)
    {
        if (!$id) {
            die('');
        }
        $risk = Db::name('stock_subaccount_risk')->where(['stock_subaccount_id' => $id])->find();
        //$account=Db::name('stock_account')->where([''=>])->find();
        $money = Db::name('stock_subaccount_money')->where(['stock_subaccount_id' => $id])->find();
        if (!empty($money)) {
            $money['min_commission'] = money_convert($money['min_commission']);
        }
        $json_arr = array_merge($risk, $money);
        echo json_encode($json_arr);
    }

    public function stock_operation()
    {

        if ($this->request->isAjax()) {

            $order_id_str = $this->request->param('str');
            $borrow_arr   = Db::name('stock_borrow')->where(['status' => 1, 'order_id' => ['in', $order_id_str]])
                ->order('verify_time', 'desc')
                ->order('id', 'desc')
                ->select();
            if (!empty($borrow_arr)) {
                foreach ($borrow_arr as $key => $val) {
                    if ($val['type'] != 4) {
                        $position_stock_arr = Db::name('stock_position')->where('sub_id', $val['stock_subaccount_id'])->field('gupiao_code,stock_count')->select();
                        $market_val         = '';
                        $gupiao_code_arr    = array();
                        $code_arr           = array();
                        if (!empty($position_stock_arr)) {
                            foreach ($position_stock_arr as $value) {
                                $gupiao_code_arr[$value['gupiao_code']] = $value['stock_count'];
                                $code_arr[]                             = $value['gupiao_code'];
                            }
                        }

                        $new_code_arr = z_market_bat(implode(',', $code_arr));
                        foreach ($new_code_arr as $k => $v) {
                            $market_val += $gupiao_code_arr[$v['code']] * $v['current_price'];
                        }

                        $subaccount_money_arr  = Db::name('stock_subaccount_money')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->field('avail,freeze_amount,deposit_money,borrow_money')->find();
                        $subaccount_loss       = Db::name('stock_subaccount_risk')->where('stock_subaccount_id', $borrow_arr[$key]['stock_subaccount_id'])->field('loss_warn,loss_close')->find();
                        $subaccount_loss_warn  = $subaccount_loss['loss_warn'] / 100;
                        $subaccount_loss_close = $subaccount_loss['loss_close'] / 100;
                        $loss_warn             = money_convert($subaccount_money_arr['borrow_money']) + money_convert($subaccount_money_arr['deposit_money']) * $subaccount_loss_warn;
                        $sell_freez            = Db::name('stock_delivery_order')
                            ->where('sub_id', $val['stock_subaccount_id'])
                            ->where(['status' => 0])
                            ->where(['business_name' => '证券卖出'])
                            ->sum('liquidation_amount');
                        $subaccount_money_arr['freeze_amount'] = $subaccount_money_arr['freeze_amount'] - $sell_freez * 100;
                        $now_init_amount                       = $market_val + money_convert($subaccount_money_arr['avail']) + money_convert($subaccount_money_arr['freeze_amount']);

                        //止损线=保证金*止损比例+配资金额-市值-可用余额-冻结资金
                        $loss_close                   = money_convert($subaccount_money_arr['borrow_money']) + money_convert($subaccount_money_arr['deposit_money']) * $subaccount_loss_close;
                        $operation[$key]['losswarn']  = sprintf("%.2f", ($now_init_amount - $loss_warn)) . '元';
                        $operation[$key]['lossclose'] = sprintf("%.2f", ($now_init_amount - $loss_close)) . '元';
                        if ($operation[$key]['losswarn'] <= 0) {
                            $operation[$key]['losswarn'] = "<span style='color:red'>" . $operation[$key]['losswarn'] . "</span>";
                        }
                        if ($operation[$key]['lossclose'] <= 0) {
                            $operation[$key]['lossclose'] = "<span style='color:red'>" . $operation[$key]['lossclose'] . "</span>";
                        }
                    } else {
                        $operation[$key]['losswarn']  = '';
                        $operation[$key]['lossclose'] = '';
                    }

                }
                if ($operation) {
                    return json(['data' => $operation, 'status' => 1, 'message' => 'success']);
                }
            } else {
                echo "无配资记录\n\r";
            }

        }

    }
}
