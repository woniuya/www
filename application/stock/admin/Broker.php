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
use app\stock\model\Broker as BrokerModel;
use util\Tree;
use think\Db;
use think\Hook;

/**
 * 券商类型默认控制器
 * @package app\stock\admin
 */
class Broker extends Admin
{
    /**
     * 券商类型首页
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='create_time desc';
        }
        // 数据列表
        $data_list = BrokerModel::where($map)->order($order)->paginate();

        // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('证券类型管理') // 设置页面标题
            ->setTableName('stock_broker') // 设置数据表名
            ->setSearch(['id' => 'ID', 'broker_id' => '券商ID', 'broker_value' => '券商名称']) // 设置搜索参数
            ->addOrder('broker_id,broker_value,create_time') // 添加排序
            ->addFilter('broker_id,broker_value') // 添加筛选
            ->addColumns([ // 批量添加列
                ['broker_id', '券商ID'],
                ['broker_value', '券商名称'],
                ['clienver', '客户端版本号','text.edit'],
                ['create_time','创建时间','datetime'],
                ['update_time','更新时间','datetime'],
                ['info', '备注'],
                ['status', '状态', 'switch'],
            ])
            ->addTopButtons('add,enable,disable') // 批量添加顶部按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

    /**
     * 新增券商类型
     * @author 路人甲乙
     * @return mixed
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Broker.insert');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);

            if ($broker = BrokerModel::create($data)) {
                Hook::listen('broker_add', $broker);
                $details = "新增券商类型：券商ID-".$data['broker_id'].",券商名称-".$data['broker_value'];
                // 记录行为
                action_log('broker_add', 'stock_broker', $broker['id'], UID,$details);
                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增券商类型') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['number:6', 'broker_id', '券商ID', '券商类型ID 如：32代表中信证券'],
                ['text:6', 'broker_value', '券商名称','必填'],
                ['text:6', 'clienver', '客户端版本号','必填'],
                ['text:6', 'info', '备注',''],
                ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
            ])
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

            // 验证
            $result = $this->validate($data, 'Broker.update');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);

            if (BrokerModel::update($data)) {
                $broker = BrokerModel::get($data['id']);
                Hook::listen('broker_edit', $broker);
                // 记录行为
                $details = "编辑券商类型：券商ID-".$broker['broker_id'].",券商名称-".$broker['broker_value'];
                action_log('broker_edit', 'stock_broker', $broker['id'], UID, $details);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = BrokerModel::where('id', $id)->field('*', false)->find();
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('编辑券商类型') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static:6', 'broker_id', '券商ID', ''],
                ['static:6', 'broker_value', '券商名称',''],
                ['text:6', 'clienver', '客户端版本号',''],
                ['text:6', 'info', '备注',''],
                ['radio', 'status', '状态', '', ['禁用', '启用']]
            ])
            ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    /**
     * 删除券商类型
     * @param array $ids 用户id
     * @author 路人甲乙
     * @return mixed
     */
    public function delete($ids = [])
    {
        Hook::listen('broker_delete', $ids);
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
        Hook::listen('broker_enable', $ids);
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
        Hook::listen('broker_disable', $ids);
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
        $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $type_name = BrokerModel::where('id', 'in', $ids)->column('id');
        return parent::setStatus($type, ['broker_'.$type, 'stock_broker', 0, UID, implode('、', $type_name)]);
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
        $config  = BrokerModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['broker_edit', 'stock_broker', $id, UID, $details]);
    }

}
