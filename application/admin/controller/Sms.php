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

namespace app\admin\controller;

use app\common\builder\ZBuilder;
use app\admin\model\Config as ConfigModel;
use app\admin\model\Module as ModuleModel;

class Sms extends Admin
{

    public function index($group = 'sms')
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        if($group=='sms'){
            // 保存数据
                        if ($this->request->isPost()) {
                            $data = $this->request->post();

                            if (isset(config('config_group')[$group])) {
                                // 查询该分组下所有的配置项名和类型
                                $items = ConfigModel::where('group', $group)->where('status', 1)->column('name,type');

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
                                if (false === ModuleModel::where('name', $group)->update(['config' => json_encode($data)])) {
                                    $this->error('更新失败');
                                }
                                // 非开发模式，缓存数据
                                if (config('develop_mode') == 0) {
                                    cache('module_config_' . $group, $data);
                                }
                            }
                            cache('system_config', null);
                            // 记录行为
                            action_log('system_config_update', 'admin_config', 0, UID, "分组($group)");
                            $this->success('更新成功', url('index', ['group' => $group]));
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

                            $sms_list['sms'] = $list_group['sms'];
                            $sms_list['sms_admin_template'] = $list_group['sms_admin_template'];
                            $sms_list['sms_user_template'] = $list_group['sms_user_template'];
                            $tab_list = [];
                            foreach ($sms_list as $key => $value) {
                                $tab_list[$key]['title'] = $value;
                                $tab_list[$key]['url'] = url('index', ['group' => $key]);
                            }

                            if (isset(config('config_group')[$group])) {
                                // 查询条件
                                $map['group'] = $group;
                                $map['status'] = 1;

                                // 数据列表
                                $data_list = ConfigModel::where($map)
                                    ->order('sort asc,id asc')
                                    ->column('name,title,tips,type,value,options,ajax_url,next_items,param,table,level,key,option,ak,format');

                                if ($group == 'sms') {
                                    $pwds = config('sms_sn') . config('sms_pw');
                                    $sn = config('sms_sn');
                                    $pwds = strtoupper(md5($pwds));
                                    $d = @file_get_contents("http://sdk2.zucp.net:8060/webservice.asmx/balance?sn={$sn}&pwd={$pwds}", false);
                                    preg_match('/<string.*?>(.*?)<\/string>/', $d, $matches);

                                    if ($matches[1] < 0) {
                                        switch ($matches[1]) {
                                            case -2:
                                                $d = "帐号/密码不正确或者序列号未注册";
                                                break;
                                            case -4:
                                                $d = "余额不足";
                                                break;
                                            case -6:
                                                $d = "参数有误";
                                                break;
                                            case -7:
                                                $d = "权限受限,该序列号是否已经开通了调用该方法的权限";
                                                break;
                                            case -12:
                                                $d = "序列号状态错误，请确认序列号是否被禁用";
                                                break;
                                            default:
                                                $d = "用户名或密码错误";
                                                break;
                                        }
                                    } else {
                                        $d = $matches[1] . '条';
                                    }

                                    $data_list['sms_msg'] = array(
                                        "name" => "",
                                        "title" => "状态",
                                        "tips" => "",
                                        "type" => "static",
                                        "value" => $d,
                                        "options" => "",
                                        "ajax_url" => "",
                                        "next_items" => "",
                                        "param" => "",
                                        "table" => "",
                                        "level" => 0,
                                        "key" => "",
                                        "option" => "",
                                        "ak" => "",
                                        "format" => ""
                                    );
                                }


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
                                    ->setPageTitle('系统设置')
                                    ->setTabNav($tab_list, $group)
                                    ->setFormItems($data_list)
                                    ->fetch();
                            }
                        }

        }else{

                        $list_group = ConfigModel::getConfig('config_group');
                        $sms_list['sms'] = $list_group['sms'];
                        $sms_list['sms_admin_template'] = $list_group['sms_admin_template'];
                        $sms_list['sms_user_template'] = $list_group['sms_user_template'];
                            // dump($sms_list);exit;
                        $tab_list = [];
                        foreach ($sms_list as $key => $value)
                        {
                        $tab_list[$key]['title'] = $value;
                        $tab_list[$key]['url'] = url('index', ['group' => $key]);
                        }

                        //$map           = $this->getMap();
                        $map['group'] = $group;
                        $map['status'] = ['egt', 0];

                        //$order = $this->getOrder('sort asc,id asc');

                        // $data_list = ConfigModel::where($map)->find();
                        $data_list = Db('admin_config')->where(['group' => $group])->select();

                        return ZBuilder::make('table')
                            ->setPageTitle('短信管理')// 设置页面标题
                            ->setTabNav($tab_list, $group)// 设置tab分页
                           // ->setSearch(['title' => '标题'])// 设置搜索框
                            ->addColumns([ // 批量添加数据列
                                ['title', '标题', 'text'],
                                //['name', '名称', 'text.edit'],
                                //['type', '类型', 'select', config('form_item_type')],
                                ['value', '值', 'text.edit'],
                                ['status', '状态', 'switch'],
                                //['sort', '排序', 'text.edit'],
                                //['right_button', '操作', 'btn']
                            ])
                            ->addValidate('Config', 'name,title')// 添加快捷编辑的验证器
                           // ->addTopButton('add', ['href' => url('add', ['group' => $group])], true)// 添加单个顶部按钮
                            // ->addTopButtons('enable,disable') // 批量添加顶部按钮
                            //->addRightButton('edit', [], true)
                            //->addRightButton('delete') // 批量添加右侧按钮
                            ->setRowList($data_list)// 设置表格数据
                            ->fetch(); // 渲染模板
              }
    }

    /**
     * 新增配置项
     * @param string $group 分组
     * @author 路人甲乙
     * @return mixed
     */
    public function add($group = '')
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Config');
            if(true !== $result) $this->error($result);

            // 如果是快速联动
            if ($data['type'] == 'linkages') {
                $data['key']    = $data['key']    == '' ? 'id'   : $data['key'];
                $data['pid']    = $data['pid']    == '' ? 'pid'  : $data['pid'];
                $data['level']  = $data['level']  == '' ? '2'    : $data['level'];
                $data['option'] = $data['option'] == '' ? 'name' : $data['option'];
            }

            if ($config = ConfigModel::create($data)) {
                cache('system_config', null);
                $forward = $this->request->param('_pop') == 1 ? null : cookie('__forward__');
                // 记录行为
                $details = '详情：分组('.$data['group'].')、类型('.$data['type'].')、标题('.$data['title'].')、名称('.$data['name'].')';
                action_log('config_add', 'admin_config', $config['id'], UID, $details);
                $this->success('新增成功', $forward);
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增')
            ->addRadio('group', '配置分组', '', config('config_group'), $group)
            ->addSelect('type', '配置类型', '', config('form_item_type'))
            ->addText('title', '配置标题', '一般由中文组成，仅用于显示')
            ->addText('name', '配置名称', '由英文字母和下划线组成，如 <code>web_site_title</code>，调用方法：<code>config(\'web_site_title\')</code>')
            ->addTextarea('value', '配置值', '该配置的具体内容')
            ->addTextarea('options', '配置项', '用于单选、多选、下拉、联动等类型')
            ->addText('ajax_url', '异步请求地址', "如请求的地址是 <code>url('ajax/getCity')</code>，那么只需填写 <code>ajax/getCity</code>，或者直接填写以 <code>http</code>开头的url地址")
            ->addText('next_items', '下一级联动下拉框的表单名', "与当前有关联的下级联动下拉框名，多个用逗号隔开，如：area,other")
            ->addText('param', '请求参数名', "联动下拉框请求参数名，默认为配置名称")
            ->addNumber('level', '级别', '需要显示的级别数量，默认为2', 2, 2, 4)
            ->addText('table', '表名', '要查询的表，里面必须含有id、name、pid三个字段，其中id和name字段可在下面重新定义')
            ->addText('pid', '父级id字段名', '即表中的父级ID字段名，如果表中的主键字段名为pid则可不填写')
            ->addText('key', '键字段名', '即表中的主键字段名，如果表中的主键字段名为id则可不填写')
            ->addText('option', '值字段名', '下拉菜单显示的字段名，如果表中的该字段名为name则可不填写')
            ->addText('ak', 'APPKEY', '百度编辑器APPKEY')
            ->addText('format', '格式')
            ->addText('tips', '配置说明', '该配置的具体说明')
            ->addText('sort', '排序', '', 100)
            ->setTrigger('type', 'linkage', 'ajax_url,next_items,param')
            ->setTrigger('type', 'linkages', 'table,pid,level,key,option')
            ->setTrigger('type', 'bmap', 'ak')
            ->setTrigger('type', 'masked,date,time,datetime', 'format')
            ->fetch();
    }

    /**
     * 编辑
     * @param int $id
     * @author 路人甲乙
     * @return mixed
     */
    public function edit($id = 0)
    {
        if ($id === 0) $this->error('参数错误');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Config');
            if(true !== $result) $this->error($result);

            // 如果是快速联动
            if ($data['type'] == 'linkages') {
                $data['key']    = $data['key']    == '' ? 'id'   : $data['key'];
                $data['pid']    = $data['pid']    == '' ? 'pid'  : $data['pid'];
                $data['level']  = $data['level']  == '' ? '2'    : $data['level'];
                $data['option'] = $data['option'] == '' ? 'name' : $data['option'];
            }

            // 原配置内容
            $config  = ConfigModel::where('id', $id)->find();
            $details = '原数据：分组('.$config['group'].')、类型('.$config['type'].')、标题('.$config['title'].')、名称('.$config['name'].')';

            if ($config = ConfigModel::update($data)) {
                cache('system_config', null);
                $forward = $this->request->param('_pop') == 1 ? null : cookie('__forward__');
                // 记录行为
                action_log('config_edit', 'admin_config', $config['id'], UID, $details);
                $this->success('编辑成功', $forward, '_parent_reload');
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = ConfigModel::get($id);

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('编辑')
            ->addHidden('id')
            ->addRadio('group', '配置分组', '', config('config_group'))
            ->addSelect('type', '配置类型', '', config('form_item_type'))
            ->addText('title', '配置标题', '一般由中文组成，仅用于显示')
            ->addText('name', '配置名称', '由英文字母和下划线组成，如 <code>web_site_title</code>，调用方法：<code>config(\'web_site_title\')</code>')
            ->addTextarea('value', '配置值', '该配置的具体内容')
            ->addTextarea('options', '配置项', '用于单选、多选、下拉、联动等类型')
            ->addText('ajax_url', '异步请求地址', "如请求的地址是 <code>url('ajax/getCity')</code>，那么只需填写 <code>ajax/getCity</code>，或者直接填写以 <code>http</code>开头的url地址")
            ->addText('next_items', '下一级联动下拉框的表单名', "与当前有关联的下级联动下拉框名，多个用逗号隔开，如：area,other")
            ->addText('param', '请求参数名', "联动下拉框请求参数名，默认为配置名称")
            ->addNumber('level', '级别', '需要显示的级别数量，默认为2', 2, 2, 4)
            ->addText('table', '表名', '要查询的表，里面必须含有id、name、pid三个字段，其中id和name字段可在下面重新定义')
            ->addText('pid', '父级id字段名', '即表中的父级ID字段名，如果表中的主键字段名为pid则可不填写')
            ->addText('key', '键字段名', '即表中的主键字段名，如果表中的主键字段名为id则可不填写')
            ->addText('option', '值字段名', '下拉菜单显示的字段名，如果表中的该字段名为name则可不填写')
            ->addText('ak', 'APPKEY', '百度编辑器APPKEY')
            ->addText('format', '格式')
            ->addText('tips', '配置说明', '该配置的具体说明')
            ->addText('sort', '排序', '', 100)
            ->setTrigger('type', 'linkage', 'ajax_url,next_items,param')
            ->setTrigger('type', 'linkages', 'table,pid,level,key,option')
            ->setTrigger('type', 'bmap', 'ak')
            ->setTrigger('type', 'masked,date,time,datetime', 'format')
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 删除配置
     * @param array $record 行为日志
     * @author 路人甲乙
     * @return mixed
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用配置
     * @param array $record 行为日志
     * @author 路人甲乙
     * @return mixed
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用配置
     * @param array $record 行为日志
     * @author 路人甲乙
     * @return mixed
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置配置状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @author 路人甲乙
     * @return mixed
     */
    public function setStatus($type = '', $record = [])
    {

        $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $uid_delete = is_array($ids) ? '' : $ids;
        $ids        = ConfigModel::where('id', 'in', $ids)->column('title');
        return parent::setStatus($type, ['config_'.$type, 'admin_config', $uid_delete, UID, implode('、', $ids)]);
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
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $config  = ConfigModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['config_edit', 'admin_config', $id, UID, $details]);
    }
}