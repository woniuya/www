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

/**
 * 系统模块控制器
 * @package app\admin\controller
 */
class System extends Admin
{
    /**
     * 系统设置
     * @param string $group 分组
     * @author 路人甲乙
     * @return mixed
     */
    public function index($group = 'base')
    {

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

            unset( $list_group['develop'], $list_group['stock'],$list_group['member'],$list_group['onlinerecharge'],$list_group['sms'],$list_group['sms_admin_template'],$list_group['sms_user_template']);
            $tab_list = [];
            foreach ($list_group as $key => $value) {
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
                if ($group == 'system') {
                    $Jss = 'system_hidden';
                } elseif ($group == 'base') {
                    $Jss = 'base_hidden';
                }elseif ($group == 'upload') {
                    $Jss = 'upload_hidden';
                }else{
                    $Jss = '';
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
                    ->js($Jss)
                    ->fetch();
            } else {
                // 模块配置
              if($group == 'cms'){$Jss ='cms_hidden';}
                $module_info = ModuleModel::getInfoFromFile($group);
                $config = $module_info['config'];
                $trigger = isset($module_info['trigger']) ? $module_info['trigger'] : [];

                // 数据库内的模块信息
                $db_config = ModuleModel::where('name', $group)->value('config');
                $db_config = json_decode($db_config, true);

                // 使用ZBuilder快速创建表单
                return ZBuilder::make('form')
                    ->setPageTitle('模块设置')
                    ->setTabNav($tab_list, $group)
                    ->addFormItems($config)
                    ->js($Jss)
                    ->setFormdata($db_config)// 设置表格数据
                    ->setTrigger($trigger)// 设置触发

                    ->fetch();
            }
        }
    }
}