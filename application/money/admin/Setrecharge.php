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

namespace app\money\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\money\model\Recharge as RechargeModel;
use app\admin\model\Config as ConfigModel;
use app\admin\model\Module as ModuleModel;
use think\Db;

/**
 * 充值设置
 * @package app\money\admin
 */
class Setrecharge extends Admin
{
    /**
     * 充值列表
     * @author menghui
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = Db::name("admin_bank")
            ->where($map)
            ->order($order)
            ->paginate();
        //dump($data_list);exit;
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setPageTitle('设置线下充值对公账户')
            //->setExtraHtml('html代码', 'toolbar_top')
            ->setSearch(['bank_name' => '银行名称', 'payee' => '收款人']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['bank_name', '银行名称'],
                ['payee', '收款人'],
                ['card', '收款账号'],
                ['open_bank', '开户行'],
                ['status', '状态','switch'],
                ['notes', '说明'],
                ['create_time', '建立时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->setTableName('admin_bank')
            ->addTopButtons('add,enable,disable') // 批量添加顶部按钮
            ->addRightButton('edit')
            ->addRightButton('delete')
            ->addOrder('id,create_time,status')
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }
    public function add(){
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'bank.create');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);
            $data['create_time']=time();
            $result_up = Db::name("admin_bank")->insert($data);
            if($result_up===1){
                $this->success('添加成功',  'index');
            }else {
                $this->error($result_up);
            }
        }
        return ZBuilder::make('form')
            ->addFormItems([
                ['text:5', 'bank_name', '转账方式','银行方式填银行名称,其他方式填方式名,例如(支付宝)'],
                ['text:5', 'card', '收款账号','收款账号为(银行账号,支付宝,微信账号)'],
                ['text:5', 'payee', '收款人姓名'],

                ['text:5', 'open_bank', '开户行'],
                ['radio', 'status', '是否禁用', '', ['1' => '允许使用', '0' => '禁止使用'],1],
                ['image', 'image', '二维码'],
                ['textarea', 'notes', '说明'],
            ])
            ->fetch(); // 渲染模板
    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null , '_close_pop');

        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $result = $this->validate($data, 'bank.create');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);
            $result_up = Db::name("admin_bank")->where(['id'=>$id])->update($data);
            if($result_up===1){
                $this->success('编辑成功', 'index');
            }else {
                $this->error($result_up);
            }

        }
        $info = Db::name("admin_bank")->where(['id'=>$id])->find();

        return ZBuilder::make('form')
            ->setPageTitle('审核') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['text:5', 'bank_name', '银行名称'],
                ['text:5', 'payee', '收款人'],
                ['text:5', 'card', '收款账号'],
                ['text:5', 'open_bank', '开户行'],
                ['image', 'image', '二维码'],
                ['textarea', 'notes', '说明'],
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }
    public function delete($id = null){
        $id = $this->request->param('ids');
        if ($id === null) $this->error('缺少参数', null , '_close_pop');
        $result = Db::name("admin_bank")->where(['id'=>$id])->delete();
        if($result===1){
            $this->success('删除成功','index');
        }else {
            $this->error($result);
        }
    }
    public function online($group='onlinerecharge'){

            // 保存数据
            if ($this->request->isPost()) {
                $data = $this->request->post();

                if (isset(config('config_group')['onlinerecharge'])) {
                    // 查询该分组下所有的配置项名和类型
                    $items = ConfigModel::where('group', 'onlinerecharge')->where('status', 1)->column('name,type');

                    foreach ($items as $name => $type) {
                        if (!isset($data[$name])) {
                            switch ($type) {
                                // 开关
                                case 'switch':
                                    $data[$name] = 0;
                                    break;
                                case 'checkbox':
                                    $data[$name] = '';
                                    break;
                            }
                        } else {
                            // 如果值是数组则转换成字符串，适用于复选框等类型
                            if (is_array($data[$name])) {
                                $data[$name] = implode(',', $data[$name]);
                            }
                            switch ($type) {
                                // 开关
                                case 'switch':
                                    $data[$name] = 1;
                                    break;
                                // 日期时间
                                case 'date':
                                case 'time':
                                case 'datetime':
                                    $data[$name] = strtotime($data[$name]);
                                    break;
                            }
                        }
                        ConfigModel::where('name', $name)->update(['value' => $data[$name]]);
                    }
                } else {
                    // 保存模块配置
                    if (false === ModuleModel::where('name', 'onlinerecharge')->update(['config' => json_encode($data)])) {
                        $this->error('更新失败');
                    }
                    // 非开发模式，缓存数据
                    if (config('develop_mode') == 0) {
                        cache('module_config_'.'onlinerecharge', $data);
                    }
                }
                cache('system_config', null);
                // 记录行为
                action_log('system_config_update', 'admin_config', 0, UID, "分组('onlinerecharge')");
                $this->success('更新成功', url('online', ['group' => 'onlinerecharge']));
            } else {

                // 配置分组信息
                $list_group = config('config_group');

                // 读取模型配置
                $modules = ModuleModel::where('config', 'neq', '')
                    ->where('status', 1)
                    ->column('config,title,name', 'name');
                foreach ($modules as $name => $module) {
                    $list_group[$name] = $module['title'];
                }

                $tab_list   = [];
                foreach ($list_group as $key => $value) {
                    $tab_list[$key]['title'] = $value;
                    $tab_list[$key]['url']  = url('index', ['group' => $key]);
                }
                if (isset(config('config_group')['onlinerecharge'])) {
                    // 查询条件
                    $map['group']  = 'onlinerecharge';
                    $map['status'] = 1;

                    // 数据列表
                    $data_list = ConfigModel::where($map)
                        ->order('sort asc,id asc')
                        ->column('name,title,tips,type,value,options,ajax_url,next_items,param,table,level,key,option,ak,format');
                    foreach ($data_list as &$value) {
                        // 解析options
                        if ($value['options'] != '') {
                            $value['options'] = parse_attr($value['options']);
                        }
                    }
                    // 默认模块列表
                    if (isset($data_list['home_default_module'])) {
                        $list_module['index'] = '默认';
                        $data_list['home_default_module']['options'] = array_merge($list_module, ModuleModel::getModule());
                    }

                    // 使用ZBuilder快速创建表单
                    return ZBuilder::make('form')
                        ->setPageTitle('在线充值设置')
                        /*->setTabNav($tab_list, $group)*/
                        ->setFormItems($data_list)
                        ->fetch();
                }
            }
        }

}