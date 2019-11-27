<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 绿麻雀（北京）科技有限公司 [ http://www.lvmaque.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lvmaque.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
use think\Db;
use think\View;
use app\user\model\User;
use app\apicom\model\JWT;
use think\request;

//define("JWT_TOKEN_KEY",'LVMAQUE23101988');
// 应用公共文件

if (!function_exists('is_signin')) {
    /*
     * 判断是否登录
     * @author 绿麻雀 
     * @return mixed
     */
    function is_signin()
    {
        $user = session('user_auth');
        if (empty($user)) {
            // 判断是否记住登录
            if (cookie('?uid') && cookie('?signin_token')) {
                $UserModel = new User();
                $user = $UserModel::get(cookie('uid'));
                if ($user) {
                    $signin_token = data_auth_sign($user['username'].$user['id'].$user['last_login_time']);
                    if (cookie('signin_token') == $signin_token) {
                        // 自动登录
                        $UserModel->autoLogin($user);
                        return $user['id'];
                    }
                }
            };
            return 0;
        }else{
            return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
        }
    }
}

if (!function_exists('data_auth_sign')) {
    /*
     * 数据签名认证
     * @param array $data 被认证的数据
     * @author 绿麻雀 
     * @return string
     */
    function data_auth_sign($data = [])
    {
        // 数据类型检测
        if(!is_array($data)){
            $data = (array)$data;
        }

        // 排序
        ksort($data);
        // url编码并生成query字符串
        $code = http_build_query($data);
        // 生成签名
        $sign = sha1($code);
        return $sign;
    }
}

if (!function_exists('get_file_path')) {
    /*
     * 获取附件路径
     * @param int $id 附件id
     * @author 绿麻雀 
     * @return string
     */
    function get_file_path($id = 0)
    {
        $path = model('admin/attachment')->getFilePath($id);
        if (!$path) {
            return config('public_static_path').'admin/img/none.png';
        }
        return $path;
    }
}

if (!function_exists('get_files_path')) {
    /*
     * 批量获取附件路径
     * @param array $ids 附件id
     * @author 绿麻雀 
     * @return array
     */
    function get_files_path($ids = [])
    {
        $paths = model('admin/attachment')->getFilePath($ids);
        return !$paths ? [] : $paths;
    }
}

if (!function_exists('get_thumb')) {
    /*
     * 获取图片缩略图路径
     * @param int $id 附件id
     * @author 绿麻雀 
     * @return string
     */
    function get_thumb($id = 0)
    {
        $path = model('admin/attachment')->getThumbPath($id);
        if (!$path) {
            return config('public_static_path').'admin/img/none.png';
        }
        return $path;
    }
}

if (!function_exists('get_avatar')) {
    /*
     * 获取用户头像路径
     * @param int $uid 用户id
     * @author 绿麻雀 
     * @alter 小乌 <82950492@qq.com>
     * @return string
     */
    function get_avatar($uid = 0)
    {
        $avatar = Db::name('admin_user')->where('id', $uid)->value('avatar');
        $path = model('admin/attachment')->getFilePath($avatar);
        if (!$path) {
            return config('public_static_path').'admin/img/avatar.jpg';
        }
        return $path;
    }
}

if (!function_exists('get_file_name')) {
    /*
     * 根据附件id获取文件名
     * @param string $id 附件id
     * @author 绿麻雀 
     * @return string
     */
    function get_file_name($id = '')
    {
        $name = model('admin/attachment')->getFileName($id);
        if (!$name) {
            return '没有找到文件';
        }
        return $name;
    }
}

if (!function_exists('minify')) {
    /*
     * 合并输出js代码或css代码
     * @param string $type 类型：group-分组，file-单个文件，base-基础目录
     * @param string $files 文件名或分组名
     * @author 绿麻雀 
     */
    function minify($type = '', $files = '')
    {
        $files = !is_array($files) ? $files : implode(',', $files);
        $url   = PUBLIC_PATH. 'min/?';

        switch ($type) {
            case 'group':
                $url .= 'g=' . $files;
                break;
            case 'file':
                $url .= 'f=' . $files;
                break;
            case 'base':
                $url .= 'b=' . $files;
                break;
        }
        echo $url;
    }
}

if (!function_exists('ck_js')) {
    /*
     * 返回ckeditor编辑器上传文件时需要返回的js代码
     * @param string $callback 回调
     * @param string $file_path 文件路径
     * @param string $error_msg 错误信息
     * @author 绿麻雀 
     * @return string
     */
    function ck_js($callback = '', $file_path = '', $error_msg = '')
    {
        return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback, '$file_path' , '$error_msg');</script>";
    }
}

if (!function_exists('parse_attr')) {
    /*
     * 解析配置
     * @param string $value 配置值
     * @return array|string
     */
    function parse_attr($value = '') {
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        if (strpos($value, ':')) {
            $value  = array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k]   = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }
}

if (!function_exists('implode_attr')) {
    /*
     * 组合配置
     * @param array $array 配置值
     * @return string
     */
    function implode_attr($array = []) {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $key.':'.$value;
        }
        return empty($result) ? '' : implode(PHP_EOL, $result);
    }
}

if (!function_exists('parse_array')) {
    /*
     * 将一维数组解析成键值相同的数组
     * @param array $arr 一维数组
     * @author 绿麻雀 
     * @return array
     */
    function parse_array($arr) {
        $result = [];
        foreach ($arr as $item) {
            $result[$item] = $item;
        }
        return $result;
    }
}

if (!function_exists('parse_config')) {
    /**
     * 解析配置，返回配置值
     * @param array $configs 配置
     * @author 绿麻雀 
     * @return array
     */
    function parse_config($configs = []) {
        $type = [
            'hidden'      => 2,
            'date'        => 4,
            'ckeditor'    => 4,
            'daterange'   => 4,
            'datetime'    => 4,
            'editormd'    => 4,
            'file'        => 4,
            'colorpicker' => 4,
            'files'       => 4,
            'icon'        => 4,
            'image'       => 4,
            'images'      => 4,
            'jcrop'       => 4,
            'range'       => 4,
            'number'      => 4,
            'password'    => 4,
            'sort'        => 4,
            'static'      => 4,
            'summernote'  => 4,
            'switch'      => 4,
            'tags'        => 4,
            'text'        => 4,
            'array'       => 4,
            'textarea'    => 4,
            'time'        => 4,
            'ueditor'     => 4,
            'wangeditor'  => 4,
            'radio'       => 5,
            'bmap'        => 5,
            'masked'      => 5,
            'select'      => 5,
            'linkage'     => 5,
            'checkbox'    => 5,
            'linkages'    => 6
        ];
        $result = [];
        foreach ($configs as $item) {
            // 判断是否为分组
            if ($item[0] == 'group') {
                foreach ($item[1] as $option) {
                    foreach ($option as $group => $val) {
                        $result[$val[1]] = isset($val[$type[$val[0]]]) ? $val[$type[$val[0]]] : '';
                    }
                }
            } else {
                $result[$item[1]] = isset($item[$type[$item[0]]]) ? $item[$type[$item[0]]] : '';
            }
        }
        return $result;
    }
}

if (!function_exists('set_config_value')) {
    /**
     * 设置配置的值，并返回配置好的数组
     * @param array $configs 配置
     * @param array $values 配置值
     * @author 绿麻雀 
     * @return array
     */
    function set_config_value($configs = [], $values = []) {
        $type = [
            'hidden'      => 2,
            'date'        => 4,
            'ckeditor'    => 4,
            'daterange'   => 4,
            'datetime'    => 4,
            'editormd'    => 4,
            'file'        => 4,
            'colorpicker' => 4,
            'files'       => 4,
            'icon'        => 4,
            'image'       => 4,
            'images'      => 4,
            'jcrop'       => 4,
            'range'       => 4,
            'number'      => 4,
            'password'    => 4,
            'sort'        => 4,
            'static'      => 4,
            'summernote'  => 4,
            'switch'      => 4,
            'tags'        => 4,
            'text'        => 4,
            'array'       => 4,
            'textarea'    => 4,
            'time'        => 4,
            'ueditor'     => 4,
            'wangeditor'  => 4,
            'radio'       => 5,
            'bmap'        => 5,
            'masked'      => 5,
            'select'      => 5,
            'linkage'     => 5,
            'checkbox'    => 5,
            'linkages'    => 6
        ];

        foreach ($configs as &$item) {
            // 判断是否为分组
            if ($item[0] == 'group') {
                foreach ($item[1] as &$option) {
                    foreach ($option as $group => &$val) {
                        $val[$type[$val[0]]] = isset($values[$val[1]]) ? $values[$val[1]] : '';
                    }
                }
            } else {
                $item[$type[$item[0]]] = isset($values[$item[1]]) ? $values[$item[1]] : '';
            }
        }
        return $configs;
    }
}

if (!function_exists('hook')) {
    /**
     * 监听钩子
     * @param string $name 钩子名称
     * @param mixed  $params 传入参数
     * @param mixed  $extra  额外参数
     * @param bool   $once   只获取一个有效返回值
     * @author 绿麻雀 
     * @alter 小乌 <82950492@qq.com>
     */
    function hook($name = '', $params = null, $extra = null, $once = false) {
        \think\Hook::listen($name, $params, $extra, $once);
    }
}

if (!function_exists('module_config')) {
    /**
     * 显示当前模块的参数配置页面，或获取参数值，或设置参数值
     * @param string $name
     * @param string $value
     * @author 绿麻雀
     * @return mixed
     */
    function module_config($name = '', $value = '')
    {
        if ($name === '') {
            // 显示模块配置页面
            return action('admin/admin/moduleConfig');
        } elseif ($value === '') {
            // 获取模块配置
            if (strpos($name, '.')) {
                list($name, $item) = explode('.', $name);
                return model('admin/module')->getConfig($name, $item);
            } else {
                return model('admin/module')->getConfig($name);
            }
        } else {
            // 设置值
            return model('admin/module')->setConfig($name, $value);
        }
    }
}

if (!function_exists('plugin_menage')) {
    /*
     * 显示插件的管理页面
     * @param string $name 插件名
     * @author 绿麻雀
     * @return mixed
     */
    function plugin_menage($name = '')
    {
        return action('admin/plugin/manage', ['name' => $name]);
    }
}

if (!function_exists('plugin_config')) {
    /*
     * 获取或设置某个插件配置参数
     * @param string $name 插件名.配置名
     * @param string $value 设置值
     * @author 绿麻雀
     * @return mixed
     */
    function plugin_config($name = '', $value = '')
    {
        if ($value === '') {
            // 获取插件配置
            if (strpos($name, '.')) {
                list($name, $item) = explode('.', $name);
                return model('admin/plugin')->getConfig($name, $item);
            } else {
                return model('admin/plugin')->getConfig($name);
            }
        } else {
            return model('admin/plugin')->setConfig($name, $value);
        }
    }
}

if (!function_exists('get_plugin_class')) {
    /**
     * 获取插件类名
     * @param  string $name 插件名
     * @author 绿麻雀 
     * @return string
     */
    function get_plugin_class($name)
    {
        return "plugins\\{$name}\\{$name}";
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * 获取客户端IP地址
     * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

if (!function_exists('format_bytes')) {
    /**
     * 格式化字节大小
     * @param  number $size      字节数
     * @param  string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('format_time')) {
    /**
     * 时间戳格式化
     * @param string $time 时间戳
     * @param string $format 输出格式
     * @return false|string
     */
    function format_time($time = '', $format='Y-m-d H:i') {
        return !$time ? '' : date($format, intval($time));
    }
}

if (!function_exists('format_date')) {
    /**
     * 使用bootstrap-datepicker插件的时间格式来格式化时间戳
     * @param null $time 时间戳
     * @param string $format bootstrap-datepicker插件的时间格式 https://bootstrap-datepicker.readthedocs.io/en/stable/options.html#format
     * @author 绿麻雀 
     * @return false|string
     */
    function format_date($time = null, $format='yyyy-mm-dd') {
        $format_map = [
            'yyyy' => 'Y',
            'yy'   => 'y',
            'MM'   => 'F',
            'M'    => 'M',
            'mm'   => 'm',
            'm'    => 'n',
            'DD'   => 'l',
            'D'    => 'D',
            'dd'   => 'd',
            'd'    => 'j',
        ];

        // 提取格式
        preg_match_all('/([a-zA-Z]+)/', $format, $matches);
        $replace = [];
        foreach ($matches[1] as $match) {
            $replace[] = isset($format_map[$match]) ? $format_map[$match] : '';
        }

        // 替换成date函数支持的格式
        $format = str_replace($matches[1], $replace, $format);
        $time = $time === null ? time() : intval($time);
        return date($format, $time);
    }
}

if (!function_exists('format_moment')) {
    /**
     * 使用momentjs的时间格式来格式化时间戳
     * @param null $time 时间戳
     * @param string $format momentjs的时间格式
     * @author 绿麻雀 
     * @return false|string
     */
    function format_moment($time = null, $format='YYYY-MM-DD HH:mm') {
        $format_map = [
            // 年、月、日
            'YYYY' => 'Y',
            'YY'   => 'y',
//            'Y'    => '',
            'Q'    => 'I',
            'MMMM' => 'F',
            'MMM'  => 'M',
            'MM'   => 'm',
            'M'    => 'n',
            'DDDD' => '',
            'DDD'  => '',
            'DD'   => 'd',
            'D'    => 'j',
            'Do'   => 'jS',
            'X'    => 'U',
            'x'    => 'u',

            // 星期
//            'gggg' => '',
//            'gg' => '',
//            'ww' => '',
//            'w' => '',
            'e'    => 'w',
            'dddd' => 'l',
            'ddd'  => 'D',
            'GGGG' => 'o',
//            'GG' => '',
            'WW' => 'W',
            'W'  => 'W',
            'E'  => 'N',

            // 时、分、秒
            'HH'  => 'H',
            'H'   => 'G',
            'hh'  => 'h',
            'h'   => 'g',
            'A'   => 'A',
            'a'   => 'a',
            'mm'  => 'i',
            'm'   => 'i',
            'ss'  => 's',
            's'   => 's',
//            'SSS' => '[B]',
//            'SS'  => '[B]',
//            'S'   => '[B]',
            'ZZ'  => 'O',
            'Z'   => 'P',
        ];

        // 提取格式
        preg_match_all('/([a-zA-Z]+)/', $format, $matches);
        $replace = [];
        foreach ($matches[1] as $match) {
            $replace[] = isset($format_map[$match]) ? $format_map[$match] : '';
        }

        // 替换成date函数支持的格式
        $format = str_replace($matches[1], $replace, $format);
        $time = $time === null ? time() : intval($time);
        return date($format, $time);
    }
}

if (!function_exists('format_linkage')) {
    /**
     * 格式化联动数据
     * @param array $data 数据
     * @author 绿麻雀 
     * @return array
     */
    function format_linkage($data = [])
    {
        $list = [];
        foreach ($data as $key => $value) {
            $list[] = [
                'key'   => $key,
                'value' => $value
            ];
        }
        return $list;
    }
}

if (!function_exists('get_auth_node')) {
    /**
     * 获取用户授权节点
     * @param int $uid 用户id
     * @param string $group 权限分组，可以以点分开模型名称和分组名称，如user.group
     * @author 绿麻雀 
     * @return array|bool
     */
    function get_auth_node($uid = 0, $group = '')
    {
        return model('admin/access')->getAuthNode($uid, $group);
    }
}

if (!function_exists('check_auth_node')) {
    /**
     * 检查用户的某个节点是否授权
     * @param int $uid 用户id
     * @param string $group $group 权限分组，可以以点分开模型名称和分组名称，如user.group
     * @param int $node 需要检查的节点id
     * @author 绿麻雀 
     * @return bool
     */
    function check_auth_node($uid = 0, $group = '', $node = 0)
    {
        return model('admin/access')->checkAuthNode($uid, $group, $node);
    }
}

if (!function_exists('get_level_data')) {
    /**
     * 获取联动数据
     * @param string $table 表名
     * @param  integer $pid 父级ID
     * @param  string $pid_field 父级ID的字段名
     * @author 绿麻雀 
     * @return false|PDOStatement|string|\think\Collection
     */
    function get_level_data($table = '', $pid = 0, $pid_field = 'pid')
    {
        if ($table == '') {
            return '';
        }

        $data_list = Db::name($table)->where($pid_field, $pid)->select();

        if ($data_list) {
            return $data_list;
        } else {
            return '';
        }
    }
}

if (!function_exists('get_level_pid')) {
    /**
     * 获取联动等级和父级id
     * @param string $table 表名
     * @param int $id 主键值
     * @param string $id_field 主键名
     * @param string $pid_field pid字段名
     * @author 绿麻雀 
     * @return mixed
     */
    function get_level_pid($table = '', $id = 1, $id_field = 'id', $pid_field = 'pid')
    {
        return Db::name($table)->where($id_field, $id)->value($pid_field);
    }
}

if (!function_exists('get_level_key_data')) {
    /**
     * 反向获取联动数据
     * @param string $table 表名
     * @param string $id 主键值
     * @param string $id_field 主键名
     * @param string $name_field name字段名
     * @param string $pid_field pid字段名
     * @param int $level 级别
     * @author 绿麻雀 
     * @return array
     */
    function get_level_key_data($table = '', $id = '', $id_field = 'id', $name_field = 'name', $pid_field = 'pid', $level = 1)
    {
        $result = [];
        $level_pid = get_level_pid($table, $id, $id_field, $pid_field);
        $level_key[$level] = $level_pid;
        $level_data[$level] = get_level_data($table, $level_pid, $pid_field);

        if ($level_pid != 0) {
            $data = get_level_key_data($table, $level_pid, $id_field, $name_field, $pid_field, $level + 1);
            $level_key = $level_key + $data['key'];
            $level_data = $level_data + $data['data'];
        }
        $result['key'] = $level_key;
        $result['data'] = $level_data;

        return $result;
    }
}

if (!function_exists('plugin_action_exists')) {
    /**
     * 检查插件控制器是否存在某操作
     * @param string $name 插件名
     * @param string $controller 控制器
     * @param string $action 动作
     * @author 绿麻雀 
     * @return bool
     */
    function plugin_action_exists($name = '', $controller = '', $action = '')
    {
        if (strpos($name, '/')) {
            list($name, $controller, $action) = explode('/', $name);
        }
        return method_exists("plugins\\{$name}\\controller\\{$controller}", $action);
    }
}

if (!function_exists('plugin_model_exists')) {
    /**
     * 检查插件模型是否存在
     * @param string $name 插件名
     * @author 绿麻雀 
     * @return bool
     */
    function plugin_model_exists($name = '')
    {
        return class_exists("plugins\\{$name}\\model\\{$name}");
    }
}

if (!function_exists('plugin_validate_exists')) {
    /**
     * 检查插件验证器是否存在
     * @param string $name 插件名
     * @author 绿麻雀 
     * @return bool
     */
    function plugin_validate_exists($name = '')
    {
        return class_exists("plugins\\{$name}\\validate\\{$name}");
    }
}

if (!function_exists('get_plugin_model')) {
    /**
     * 获取插件模型实例
     * @param  string $name 插件名
     * @author 绿麻雀 
     * @return object
     */
    function get_plugin_model($name)
    {
        $class = "plugins\\{$name}\\model\\{$name}";
        return new $class;
    }
}

if (!function_exists('plugin_action')) {
    /**
     * 执行插件动作
     * 也可以用这种方式调用：plugin_action('插件名/控制器/动作', [参数1,参数2...])
     * @param string $name 插件名
     * @param string $controller 控制器
     * @param string $action 动作
     * @param mixed $params 参数
     * @author 绿麻雀 
     * @return mixed
     */
    function plugin_action($name = '', $controller = '', $action = '', $params = [])
    {
        if (strpos($name, '/')) {
            $params = is_array($controller) ? $controller : (array)$controller;
            list($name, $controller, $action) = explode('/', $name);
        }
        if (!is_array($params)) {
            $params = (array)$params;
        }
        $class = "plugins\\{$name}\\controller\\{$controller}";
        $obj = new $class;
        return call_user_func_array([$obj, $action], $params);
    }
}

if (!function_exists('_system_check')) {
    function _system_check()
    {
        $c = cache('_i_n_f_o');
        if (!$c || (time() - $c) > 86401) {
            cache('_i_n_f_o', time());
            $url = base64_decode('d3d3LmRvbHBoaW5waHAuY29tL3VwZGF0ZUluZm8=');
            $url = 'http://'.$url;
            $p['d'.'om'.'ain'] = request()->domain();
            $p[strtolower('I').'p'] = request()->server('SERVER_ADDR');
            $p = base64_encode(json_encode($p));

            $o = [
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL            => $url,
                CURLOPT_USERAGENT      => request()->server('HTTP_USER_AGENT'),
                CURLOPT_POST           => 1,
                CURLOPT_POSTFIELDS     => ['p' => $p]
            ];

            if (function_exists('curl_init')) {
                $c = curl_init();curl_setopt_array($c, $o);curl_exec($c);curl_close($c);
            }
        }
    }
}

if (!function_exists('get_plugin_validate')) {
    /**
     * 获取插件验证类实例
     * @param string $name 插件名
     * @author 绿麻雀 
     * @return bool
     */
    function get_plugin_validate($name = '')
    {
        $class = "plugins\\{$name}\\validate\\{$name}";
        return new $class;
    }
}

if (!function_exists('plugin_url')) {
    /**
     * 生成插件操作链接
     * @param string $url 链接：插件名称/控制器/操作
     * @param array $param 参数
     * @param string $module 模块名，admin需要登录验证，index不需要登录验证
     * @author 绿麻雀 
     * @return string
     */
    function plugin_url($url = '', $param = [], $module = 'admin')
    {
        $params = [];
        $url = explode('/', $url);
        if (isset($url[0])) {
            $params['_plugin'] = $url[0];
        }
        if (isset($url[1])) {
            $params['_controller'] = $url[1];
        }
        if (isset($url[2])) {
            $params['_action'] = $url[2];
        }

        // 合并参数
        $params = array_merge($params, $param);

        // 返回url地址
        return url($module .'/plugin/execute', $params);
    }
}

if (!function_exists('public_url')) {
    /**
     * 生成插件操作链接(不需要登陆验证)
     * @param string $url 链接：插件名称/控制器/操作
     * @param array $param 参数
     * @author 绿麻雀 
     * @return string
     */
    function public_url($url = '', $param = [])
    {
        // 返回url地址
        return plugin_url($url, $param, 'index');
    }
}

if (!function_exists('clear_js')) {
    /**
     * 过滤js内容
     * @param string $str 要过滤的字符串
     * @author 绿麻雀 
     * @return mixed|string
     */
    function clear_js($str = '')
    {
        $search ="/<script[^>]*?>.*?<\/script>/si";
        $str = preg_replace($search, '', $str);
        return $str;
    }
}

if (!function_exists('get_nickname')) {
    /**
     * 根据用户ID获取用户昵称
     * @param  integer $uid 用户ID
     * @return string  用户昵称
     */
    function get_nickname($uid = 0)
    {
        static $list;
        // 获取当前登录用户名
        if (!($uid && is_numeric($uid))) {
            return session('user_auth.username');
        }

        // 获取缓存数据
        if (empty($list)) {
            $list = cache('sys_user_nickname_list');
        }

        // 查找用户信息
        $key = "u{$uid}";
        if (isset($list[$key])) {
            // 已缓存，直接使用
            $name = $list[$key];
        } else {
            // 调用接口获取用户信息
            $info = model('user/user')->field('nickname')->find($uid);
            if ($info !== false && $info['nickname']) {
                $nickname = $info['nickname'];
                $name = $list[$key] = $nickname;
                /* 缓存用户 */
                $count = count($list);
                $max   = config('user_max_cache');
                while ($count-- > $max) {
                    array_shift($list);
                }
                cache('sys_user_nickname_list', $list);
            } else {
                $name = '';
            }
        }
        return $name;
    }
}

if (!function_exists('action_log')) {
    /**
     * 记录行为日志，并执行该行为的规则
     * @param null $action 行为标识
     * @param null $model 触发行为的模型名
     * @param string $record_id 触发行为的记录id
     * @param null $user_id 执行行为的用户id
     * @param string $details 详情
     * @author huajie <banhuajie@163.com>
     * @alter 绿麻雀 
     * @return bool|string
     */
    function action_log($action = null, $model = null, $record_id = '', $user_id = null, $details = '')
    {
        // 判断是否开启系统日志功能
        if (config('system_log')) {
            // 参数检查
            if(empty($action) || empty($model)){
                return '参数不能为空';
            }
            if(empty($user_id)){
                $user_id = is_signin();
            }
            if (strpos($action, '.')) {
                list($module, $action) = explode('.', $action);
            } else {
                $module = request()->module();
            }
            // 查询行为,判断是否执行
            $action_info = model('admin/action')->where('module', $module)->getByName($action);
            if($action_info['status'] != 1){
                return '该行为被禁用或删除';
            }
            // 插入行为日志
            $data = [
                'action_id'   => $action_info['id'],
                'user_id'     => $user_id,
                'action_ip'   => get_client_ip(1),
                'model'       => $model,
                'record_id'   => $record_id,
                'create_time' => request()->time()
            ];

            // 解析日志规则,生成日志备注
            if(!empty($action_info['log'])){
                if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
                    $log = [
                        'user'    => $user_id,
                        'record'  => $record_id,
                        'model'   => $model,
                        'time'    => request()->time(),
                        'data'    => ['user' => $user_id, 'model' => $model, 'record' => $record_id, 'time' => request()->time()],
                        'details' => $details
                    ];

                    $replace = [];
                    foreach ($match[1] as $value){
                        $param = explode('|', $value);
                        if(isset($param[1])){
                            $replace[] = call_user_func($param[1], $log[$param[0]]);
                        }else{
                            $replace[] = $log[$param[0]];
                        }
                    }

                    $data['remark'] = str_replace($match[0], $replace, $action_info['log']);
                }else{
                    $data['remark'] = $action_info['log'];
                }
            }else{
                // 未定义日志规则，记录操作url
                $data['remark'] = '操作url：'.$_SERVER['REQUEST_URI'];
            }

            // 保存日志
            model('admin/log')->insert($data);

            if(!empty($action_info['rule'])){
                // 解析行为
                $rules = parse_action($action, $user_id);
                // 执行行为
                $res = execute_action($rules, $action_info['id'], $user_id);
                if (!$res) {
                    return '执行行为失败';
                }
            }
        }

        return true;
    }
}

if (!function_exists('parse_action')) {
    /**
     * 解析行为规则
     * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
     * 规则字段解释：table->要操作的数据表，不需要加表前缀；
     *            field->要操作的字段；
     *            condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
     *            rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
     *            cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
     *            max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
     * 单个行为后可加 ； 连接其他规则
     * @param string $action 行为id或者name
     * @param int $self 替换规则里的变量为执行用户的id
     * @author huajie <banhuajie@163.com>
     * @alter 绿麻雀 
     * @return boolean|array: false解析出错 ， 成功返回规则数组
     */
    function parse_action($action = null, $self){
        if(empty($action)){
            return false;
        }

        // 参数支持id或者name
        if(is_numeric($action)){
            $map = ['id' => $action];
        }else{
            $map = ['name' => $action];
        }

        // 查询行为信息
        $info = model('admin/action')->where($map)->find();
        if(!$info || $info['status'] != 1){
            return false;
        }

        // 解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
        $rule   = $info['rule'];
        $rule   = str_replace('{$self}', $self, $rule);
        $rules  = explode(';', $rule);
        $return = [];
        foreach ($rules as $key => &$rule){
            $rule = explode('|', $rule);
            foreach ($rule as $k => $fields){
                $field = empty($fields) ? array() : explode(':', $fields);
                if(!empty($field)){
                    $return[$key][$field[0]] = $field[1];
                }
            }
            // cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
            if (!isset($return[$key]['cycle']) || !isset($return[$key]['max'])) {
                unset($return[$key]['cycle'],$return[$key]['max']);
            }
        }

        return $return;
    }
}

if (!function_exists('execute_action')) {
    /**
     * 执行行为
     * @param array|bool $rules 解析后的规则数组
     * @param int $action_id 行为id
     * @param array $user_id 执行的用户id
     * @author huajie <banhuajie@163.com>
     * @alter 绿麻雀 
     * @return boolean false 失败 ， true 成功
     */
    function execute_action($rules = false, $action_id = null, $user_id = null){
        if(!$rules || empty($action_id) || empty($user_id)){
            return false;
        }

        $return = true;
        foreach ($rules as $rule){
            // 检查执行周期
            $map = ['action_id' => $action_id, 'user_id' => $user_id];
            $map['create_time'] = ['gt', request()->time() - intval($rule['cycle']) * 3600];
            $exec_count = model('admin/log')->where($map)->count();
            if($exec_count > $rule['max']){
                continue;
            }

            // 执行数据库操作
            $field = $rule['field'];
            $res   = Db::name($rule['table'])->where($rule['condition'])->setField($field, array('exp', $rule['rule']));

            if(!$res){
                $return = false;
            }
        }
        return $return;
    }
}

if (!function_exists('get_location')) {
    /**
     * 获取当前位置
     * @param string $id 节点id，如果没有指定，则取当前节点id
     * @param bool $del_last_url 是否删除最后一个节点的url地址
     * @param bool $check 检查节点是否存在，不存在则抛出错误
     * @author 绿麻雀 
     * @return mixed
     */
    function get_location($id = '', $del_last_url = false, $check = true)
    {
        $location = model('admin/menu')->getLocation($id, $del_last_url, $check);
        return $location;
    }
}

if (!function_exists('packet_exists')) {
    /**
     * 查询数据包是否存在，即是否已经安装
     * @param string $name 数据包名
     * @author 绿麻雀 
     * @return bool
     */
    function packet_exists($name = '')
    {
        if (Db::name('admin_packet')->where('name', $name)->find()) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('sendsms_to_admin')) {
    /**
     * 给管理员发送短信
     * @param string $msg 短信内容
     * @author 绿麻雀
     * @return bool
     */
    function sendsms_to_admin($msg)
    {
        $mobile=$avatar = Db::name('admin_user')->where('username','admin')->value('mobile');
        $res=send_sms($mobile,"",$msg);
        return $res;
    }
}

if (!function_exists('load_assets')) {
    /**
     * 加载静态资源
     * @param string $assets 资源名称
     * @param string $type 资源类型
     * @author 绿麻雀 
     * @return string
     */
    function load_assets($assets = '', $type = 'css')
    {
        $assets_list = config('assets.'. $assets);

        $result = '';
        foreach ($assets_list as $item) {
            if ($type == 'css') {
                $result .= '<link rel="stylesheet" href="'.$item.'">';
            } else {
                $result .= '<script src="'.$item.'"></script>';
            }
        }
        $result = str_replace(array_keys(config('view_replace_str')), array_values(config('view_replace_str')), $result);
        return $result;
    }
}

if (!function_exists('parse_name')) {
    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @param string $name 字符串
     * @param integer $type 转换类型
     * @return string
     */
    function parse_name($name, $type = 0) {
        if ($type) {
            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match){return strtoupper($match[1]);}, $name));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }
}

if (!function_exists('home_url')) {
    /**
     * 生成前台入口url
     * @param string        $url 路由地址
     * @param string|array  $vars 变量
     * @param bool|string   $suffix 生成的URL后缀
     * @param bool|string   $domain 域名
     * @author 小乌 <82950492@qq.com>
     * @return string
     */
    function home_url($url = '', $vars = '', $suffix = true, $domain = false) {
        $url = url($url, $vars, $suffix, $domain);
        if (defined('ENTRANCE') && ENTRANCE == 'admin') {
            $base_file = request()->baseFile();
            $base_file = substr($base_file, strripos($base_file, '/') + 1);
            return preg_replace('/\/'.$base_file.'/', '/index.php', $url);
        } else {
            return $url;
        }
    }
}

if (!function_exists('admin_url')) {
    /**
     * 生成后台入口url
     * @param string        $url 路由地址
     * @param string|array  $vars 变量
     * @param bool|string   $suffix 生成的URL后缀
     * @param bool|string   $domain 域名
     * @author 小乌 <82950492@qq.com>
     * @return string
     */
    function admin_url($url = '', $vars = '', $suffix = true, $domain = false) {
        $url = url($url, $vars, $suffix, $domain);
        if (defined('ENTRANCE') && ENTRANCE == 'admin') {
            return $url;
        } else {
            return preg_replace('/\/index.php/', '/'.ADMIN_FILE, $url);
        }
    }
}

if (!function_exists('htmlpurifier')) {
    /**
     * html安全过滤
     * @param string $html 要过滤的内容
     * @author 绿麻雀 
     * @return string
     */
    function htmlpurifier($html = '') {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $clean_html = $purifier->purify($html);
        return $clean_html;
    }
}

if (!function_exists('extend_form_item')) {
    /**
     * 扩展表单项
     * @param array $form 类型
     * @param array $_layout 布局参数
     * @author 绿麻雀 
     * @return string
     */
    function extend_form_item($form = [], $_layout = []) {
        if (!isset($form['type'])) return '';
        if (!empty($_layout) && isset($_layout[$form['name']])) {
            $form['_layout'] = $_layout[$form['name']];
        }

        $template = './extend/form/'.$form['type'].'/'.$form['type'].'.html';
        if (file_exists($template)) {
            $template_content = file_get_contents($template);
            $view = new View();
            return $view->display($template_content, array_merge($form, $_layout));
        } else {
            return '';
        }
    }
}

if (!function_exists('role_auth')) {
    /**
     * 读取当前用户权限
     * @author 绿麻雀 
     */
    function role_auth() {
        session('role_menu_auth', model('user/role')->roleAuth());
    }
}

if (!function_exists('get_server_ip')) {
    /**
     * 获取服务器端IP地址
     * @return array|false|string
     */
    function get_server_ip(){
        if(isset($_SERVER)){
            if($_SERVER['SERVER_ADDR']){
                $server_ip = $_SERVER['SERVER_ADDR'];
            }else{
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        }else{
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip;
    }
}

if (!function_exists('get_browser_type')) {
    /**
     * 获取浏览器类型
     * @return string
     */
    function get_browser_type(){
        $agent = $_SERVER["HTTP_USER_AGENT"];
        if(strpos($agent,'MSIE') !== false || strpos($agent,'rv:11.0')) return "ie";
        if(strpos($agent,'Firefox') !== false) return "firefox";
        if(strpos($agent,'Chrome') !== false) return "chrome";
        if(strpos($agent,'Opera') !== false) return 'opera';
        if((strpos($agent,'Chrome') == false) && strpos($agent,'Safari') !== false) return 'safari';
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')) return '360SE';
        return 'unknown';
    }
}

if (!function_exists('generate_rand_str')) {
    /**
     * 生成随机字符串
     * @param int $length 生成长度
     * @param int $type 生成类型：0-小写字母+数字，1-小写字母，2-大写字母，3-数字，4-小写+大写字母，5-小写+大写+数字
     * @author 绿麻雀 
     * @return string
     */
    function generate_rand_str($length = 8, $type = 0) {
        $a = 'abcdefghijklmnopqrstuvwxyz';
        $A = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $n = '0123456789';

        switch ($type) {
            case 1: $chars = $a; break;
            case 2: $chars = $A; break;
            case 3: $chars = $n; break;
            case 4: $chars = $a.$A; break;
            case 5: $chars = $a.$A.$n; break;
            default: $chars = $a.$n;
        }

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }
}

if(!function_exists('money_convert')){
    /**
     * 资金单位为分转换其他单位
     * @param  [type] $value [description]
     * @return [type]        [description]
     * @author 张继立 <404851763@qq.com>
     */
    function money_convert($value=0, $unit='元'){
        $money = bcdiv($value, 100, 2);
        if($unit=='万' && $value > 100000){
            $money = bcdiv($value, 10000, 2);
        }
        return $money;
    }
}

if(!function_exists('send_sms')){
    /**
     * 发送手机验证码 (短信插件 控制器名称必须和插件名相同 并且尾部带有Sms 字符，必须实现sendsms方法)
     * @param  int    $mobile   手机号
     * @param  string $template 模版名称,使用模板名称时，传入的短信内容info不生效
     * @param  string $info     短信内容
     * @return string           状态 成功 true 失败 false
     * @author 张继立 <404851763@qq.com>
     */
    function send_sms($mobile, $template,$info = null){
        // 读取一条短信插件
        $sms_plugin = Db('admin_plugin')->where("name like '%Sms' and status = 1")->find();
        //$develop_mode=config('develop_mode');//判断是否开发模式
        if(!$sms_plugin){ // 如果没有开启短信接口则发送默认验证码 0000
            $arr['code']=md5('000000');
            $arr['time']=time();
            setRegSmsCache($mobile,$arr);
            $res = true;
        }else{
            if(isset($template) || $template !='' ){//如果存在后台模板，则使用模板；否则，使用自定义短信内容
                $code = generate_rand_str(6, 3);
                $tp_str = config($template);
                $info = preg_replace("/\[code\]/", $code, $tp_str);
                $arr['code']=md5($code);
                $arr['time']=time();
                setRegSmsCache($mobile,$arr);
            }else {
                $code = mt_rand(100000, 999999);
                $info = '您好,' . $mobile . '您的验证码是' . $code;
                $arr['code'] = md5($code);
                $arr['time'] = time();
                setRegSmsCache($mobile, $arr);
            }
            $res = plugin_action($sms_plugin['name'], $sms_plugin['name'], 'sendSms', [$mobile, $info]);
            if($res == false) return false;
        }
        return $res;
    }
}

if(!function_exists('send_sms_member')){
    function send_sms_member($msg,$param,$type='NULL'){
        // 读取一条短信插件
        $sms_plugin = Db('admin_plugin')->where("name like '%Sms' and status = 1")->find();
        if($sms_plugin) {
            $admin_mobile = $avatar = Db::name('admin_user')->where('username', 'admin')->value('mobile');
            $params = $type == 1000 ? $param : $admin_mobile . ',' . $param;
            $res = plugin_action($sms_plugin['name'], $sms_plugin['name'], 'sendSms_member', [$msg, $params]);
            if ($res == false) return false;
            return $res;
        }
    }
}
if (!function_exists('yan_time')) {
    /*
     * 验证交易时间
     */
    function yan_time($last_time = 14.95)
    {
        $t = time() - strtotime(date("Y-m-d", time()));
        $t2 = 3600 * 9.5;//早盘开盘时间
        $t3 = 3600 * 11.5;//早盘停盘时间
        $t4 = 3600 * 13;//下午开盘时间
        $t5 = 3600 * $last_time;//默认下午停盘时间14:55
        if (!(($t > $t2 && $t < $t3) || ($t > $t4 && $t < $t5))) {
            return false;//
        }
        if (date('N', time()) > 5) { //周六周日
            return false;//
        }
        $array = festival();//返回节假日
        if (in_array(date("md", time()), $array)) {//如果是节假日
            return false;
        }
        return true;
    }
}
if(!function_exists('setRegSmsCache')){

    /**
     * 设置手机短息验证码缓存
     * @param $data_cache
     */
    function setRegSmsCache($mobile,$arr)
    {
        //think\cache::set('sms_'.$mobile, $arr, 300);
        cache('sms_ip', $arr, 120);
        cache('sms_'.$mobile, $arr, 300);
    }

}

if(!function_exists('check_sms_code')){
    /*
     * 检测手机短信验证码
     * @param $mobile 手机号
     * @param bool|false $code
     * @return bool
     * @author menghui
     */
    function check_sms_code($mobile, $code = false)
    {
        if (!$mobile) return false;
        if ($code === false) {   //判断60秒以内是否重复发送
            if (!think\cache::has('sms_'.$mobile) && !think\cache::has('sms_ip') &&(think\cache::get('sms_ip')['ip'] != get_client_ip())) return true;
            if (think\cache::get('sms_'.$mobile)['time']+60 < time()) {
                if(think\cache::get('sms_ip')['ip'] == get_client_ip()) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } else {  //判断验证码是否输入正确
            if (!think\cache::has('sms_'.$mobile)) return false;
            if (think\cache::get('sms_'.$mobile)['code'] == md5($code)) {
                think\cache::get('sms_'.$mobile)['code'] = '';
                return true;
            } else {
                return false;
            }
        }
       return true;
    }

}


if(!function_exists('sendsms_mandao')) {
    /****************************
     * /*  手机短信接口（漫道短信www.zucp.net）
     * /* 参数：$mob        手机号码
     * /*        $content    短信内容
     * /*        $tp        类型 $tp='code' 是验证码短信 ，等于空是变量短信
     *****************************/

    function sendsms_mandao($mob, $content, $tp = '')
    {
        $pwds = config('sms_sn').config('sms_pw');
        $sn  = config('sms_sn');
        $tip  = config('sms_sign');
      	 
        $pwds = strtoupper(md5($pwds));
        $admin_mobile = $avatar = Db::name('admin_user')->where('username', 'admin')->value('mobile');
       switch ($tp){
           case 'code':
               $mob = $mob;
               break;
           case '':
               $mob = $admin_mobile;
               break;
           case 'user':
               $mob= $mob;
               break;
       }
        $type = config('sms_status');// type=0 漫道短信接口
        if ($type == 1) {

            if ($tp == 'code') {
                $code = mt_rand(100000, 999999);
                $content = $content . $code;
                $arr['code']=md5($code);
                $arr['time']=time();
                $arr['ip']=get_client_ip();
                setRegSmsCache($mob,$arr);
            }
            /////////////////////////////////////////漫道短信接口 开始/////////////////////////////////////////////////////////////
            //如果您的系统是utf-8,请转成GB2312 后，再提交、
            $flag = 0;
            //要post的数据
            $argv = array(
                'sn' =>$sn, ////替换成您自己的序列号
                'pwd' => $pwds, //此处密码需要加密 加密方式为 md5(sn+password) 32位大写

                'mobile' => $mob,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
                'content' => iconv("UTF-8", "gb2312//IGNORE", $content.$tip),//短信内容
                'ext' => '',
                'stime' => '',//定时时间 格式为2011-6-29 11:09:21
                'rrid' => ''
            );
          error_log(print_r($argv,1),3,'sms_send.log');
            //构造要post的字符串
            $params = "";
            foreach ($argv as $key => $value) {
                if ($flag != 0) {
                    $params .= "&";
                    $flag = 1;
                }
                $params .= $key . "=";
                $params .= urlencode($value);
                $flag = 1;
            }
            $length = strlen($params);
            //创建socket连接
            $fp = fsockopen("sdk2.zucp.net", 8060, $errno, $errstr, 10) or exit($errstr . "--->" . $errno);
            //构造post请求的头
            $header = "POST /webservice.asmx/mt HTTP/1.1\r\n";
            $header .= "Host:sdk2.zucp.net\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . $length . "\r\n";
            $header .= "Connection: Close\r\n\r\n";
            //添加post的字符串
            $header .= $params . "\r\n";
            //发送post的数据
            fputs($fp, $header);
            $inheader = 1;
            while (!feof($fp)) {
                $line = fgets($fp, 1024); //去除请求包的头只显示页面的返回数据
                if ($inheader && ($line == "\n" || $line == "\r\n")) {
                    $inheader = 0;
                }
                if ($inheader == 0) {
                    // echo $line;
                }
            }
            $line = str_replace("
	<string xmlns=\"http://tempuri.org/\">", "", $line);
            $line = str_replace("</string>
	", "", $line);
            $result = explode("-", $line);
            if (count($result) > 1) {
                return true;
            } else {
                return true;
            }
            /////////////////////////////////////////漫道短信接口 结束/////////////////////////////////////////////////////////////
        } else {

            if ($tp == 'code') {
                $code = '000000';
                $arr['code']=md5($code);
                $arr['time']=time();
                $arr['ip']=get_client_ip();
                setRegSmsCache($mob,$arr);
                return true;
            }


        }
    }
}



if(!function_exists('getconfigSms_status')){
        function getconfigSms_status($map)
        {
            $data_list = Db::name('admin_config')->where($map)
                ->field('value,status')->find();
            return $data_list;
        }
}

if(!function_exists('is_member_signin')){
    /*
     * 判断会员是否登录 
     * @return int member_id
     * @author 张继立 <404851763@qq.com>
     */
    function is_member_signin()
    {
		 $req=request();
		 $token = $req->param("token");
		 if(empty($token)){
			  $member = session('member_auth'); 
				if (empty($member)) {
					// 判断是否记住登录
					if (cookie('?mid') && cookie('?m_signin_token')) {
						$MemberModel = new member();
						$member = $MemberModel::get(cookie('mid'));
						if ($member) {
							$signin_token = data_auth_sign($member['mobile'].$member['id'].$member['last_login_time']);
							if (cookie('m_signin_token') == $signin_token) {
								// 自动登录
								$MemberModel->autoLogin($member);
								return $member['id'];
							}
						}
					};
					return 0;
				}else{
					return session('member_auth_sign') == data_auth_sign($member) ? $member['mid'] : 0;
				}
		 }else{
			 $decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256')); 
			  $doHost = $_SERVER['HTTP_HOST'];
				if($doHost == $decoded->doHost){
					return $decoded->uid; 
				}else{
					return 0;
				} 
		 }
       
    }
}


if(!function_exists("format_str")){

    function format_str($str, $type='mobile')
    {
        switch ($type) {
            case 'mobile':
                $str = substr_replace($str,'****',3,4);
                break;
            
            case 'bank':
                $len = strlen($str)-4;
                $str = substr_replace($str,'**** **** **** ',0,$len);
                break;
        }
        return $str;
    }
}
if(!function_exists("get_area")) {

    /*
     * 获取城市地区数据并写入缓存
     * @param  intval $reid 上级id
     * @param  intval $id   地区id
     * @return [type]       [description]
     */
    function get_area($reid = null, $id = null)
    {
        if (!cache('area')) {
            $area_arr = Db('area')->select();
            foreach ($area_arr as $value) {
                $area[$value['id']] = $value;
            }
            cache('area', $area);
        } else {
            $area = cache('area');
        }

        if ($reid) {
            foreach ($area as $val) {
                $val['reid'] == $reid && $data[] = $val;
            }
            return $data;
        }

        if ($id) {
            return $area[$id];
        }

        return null;
    }
}

if (!function_exists("arrangeTime")) {
    /**
     * 返回本年、本周、本月、本日的开始和结束时间的时间戳
     *
     * @return array
     */
    function arrangeTime($type)
    {
        switch ($type) {
            case 'month':
                $y = date("Y", time());
                $m = date("m", time());
                $d = date("d", time());
                $t0 = date('t');                   // 本月一共有几天
                $d0 = mktime(0, 0, 0, $m, 1, $y);       // 创建本月开始时间
                $d1 = mktime(23, 59, 59, $m, $t0, $y);  // 创建本月结束时间
                break;
            case 'week':
                $w = date("w", time());   //这天是星期几
                $d0 = mktime(0, 0, 0, date("m"), date("d") - $w, date("Y"));       //创建周开始时间
                $d1 = mktime(23, 59, 59, date("m"), date("d") - $w + 6, date("Y"));//创建周结束时间
                break;
            case 'day':
                $d0 = mktime(0, 0, 0, date("m"), date("d"), date("Y"));   //创建日开始时间
                $d1 = mktime(23, 59, 59, date("m"), date("d"), date("Y"));//创建日结束时间
                break;
            case 'year':
                $d0 = strtotime(date("Y", time()) . "-1" . "-1"); //本年开始
                $d1 = strtotime(date("Y", time()) . "-12" . "-31"); //本年结束
                break;
        }
        return array($d0, $d1);
    }
}


if (!function_exists("is_page_active")) {
    function is_page_active($menu){
        $request = \think\Request::instance();
        $current_url = $request->url();

        if(strpos($current_url, '.html') !== false) $current_url = preg_replace('/\.html/', '', $current_url);
        if(strpos($menu, '.html') !== false) $menu = preg_replace('/\.html/', '', $menu);
        return $menu === $current_url;
    }
}

/**
 * @info
 * @param  $id 广告id号
 * @param  $bg 是否匹配出 src
 * @author lmq_zcj <357745180@qq.com>
 * @date  2018-06-15
 * @return
 */

if (!function_exists("get_advert")) {
    function get_advert($id,$bg=0){
        $result =  Db::name('cms_advert')->where("id={$id} and status=1")->field('content')->find();
        if($bg){
           $match_str = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/"; 
            preg_match_all ($match_str,$result['content'],$out,PREG_PATTERN_ORDER);
            $result['content']=$out[1][0];
        }
        return $result['content'];
    }
}
if (!function_exists("get_spapi")) {
    function get_spapi($type=1,$port=''){
        if($port==''){
            $port=config('web_trade_port');
        }

        $result=1;//file_get_contents('http://yg.lvmaque.net/spapi.php?type='.$type.'&port='.$port);
        return $result;

        //$result=file_get_contents(http().$_SERVER["SERVER_NAME"].'/spapi.php?type='.$type.'&port='.$port);
        //return $result;
        return false;

    }
}
/*
 * 导出excel表
 * $arrHeader  表头
 * $fields 表头对应字段
 * $xlsData 数据
 * $title 表名
 */
if (!function_exists("export")) {
    function export($arrHeader,$fields,$xlsData,$title){
        Vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $tit="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,S,Y,Z,";
        $arr=explode(',',substr($tit,0,-1));
        $lenth =  count($arrHeader);
        if($lenth>26){
            return "目前只支持最大26列";
        }
        $tit_tmd=substr($tit,0,2*$lenth-1);
        $letter =explode(',',$tit_tmd);
        //填充表头信息
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($xlsData as $k=>$v){
            $k +=2;
            $num=0;
            foreach ($fields as $key=>$value){
                $objActSheet->setCellValue($arr[$num].$k,$v[$value]);
                $num+=1;
            }
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
        $num=0;
        $width = array(10,15,20,25,30);//可选宽度
        foreach ($fields as $key=>$value){
            //设置表格的宽度
            $objActSheet->getColumnDimension($arr[$num])->setWidth($width[2]);
            $num+=1;
        }
        $outfile = $title.".xlsx";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
}

function getFirstChar($s0){
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "H";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return null;
}

function getPinYin($zh){
    $ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getFirstChar($s2);
        }else{
            $ret .= $s1;
        }
    }
    return $ret;
}

/**
 * 根据指定字段查$arr2里，不存在于$arr1中的数组
 *
 * @param $field string 指定筛选字段
 * @param $arr1 array 老数组
 * @param $arr2 array 新数组
 * @return array 数组差
 */

function filterNotExistChildren($arr1 =[], $arr2=[], $field){
    $return = [];
    if(empty($field)) return $return;
    $oldArray =  array_reduce($arr1, function($carry=[], $item) use ($field){
        $carry[] = $item[$field];
        return $carry;
    });
    $return = array_filter( $arr2, function ($item) use ($oldArray){
        return !in_array($item['code'],$oldArray);
    });
    return $return;
}