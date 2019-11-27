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
return [
    // +----------------------------------------------------------------------
    // | 系统相关设置
    // +----------------------------------------------------------------------

    // 后台公共模板
    'admin_base_layout' => APP_PATH . 'admin/view/layout.html',
    // 插件目录路径
    'plugin_path'       => ROOT_PATH . 'plugins/',
    // 数据包目录路径
    'packet_path'       => ROOT_PATH . 'packet/',
    // 文件上传路径
    'upload_path'       => ROOT_PATH . 'public' . DS . 'uploads',
    // 文件上传临时目录
    'upload_temp_path'  => ROOT_PATH . 'public' . DS . 'uploads' . DS . 'temp/',

    // +----------------------------------------------------------------------
    // | 验证码设置
    // +----------------------------------------------------------------------
    'captcha' => [
        // 验证码密钥
        'seKey'    => 'lurenjiayi.com',
        // 验证码图片高度
        'imageH'   => 34,
        // 验证码图片宽度
        'imageW'   => 130,
        // 验证码字体大小(px)
        'fontSize' => 18,
        // 验证码位数
        'length'   => 4,
    ],

    // +----------------------------------------------------------------------
    // | 用户相关设置
    // +----------------------------------------------------------------------

    // 最大缓存用户数
    'user_max_cache' => 1000,
    // 管理员用户ID
    'user_admin'     => 1,

    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用调试模式
    'app_debug'             => ture,
    // 应用Trace
    'app_trace'             => false,
    // 应用模式状态
    'app_status'            => '',
    // 是否支持多模块
    'app_multi_module'      => true,
    // 入口自动绑定模块
    'auto_bind_module'      => false,
    // 注册的根命名空间
    'root_namespace'        => ['plugins' => ROOT_PATH . 'plugins/'],
    // 扩展函数文件
    'extra_file_list'       => [
        THINK_PATH . 'helper' . EXT,
        APP_PATH . 'function' . EXT,
    ],
    // 默认输出类型
    'default_return_type'   => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'   => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler' => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'     => 'callback',
    // 默认时区
    'default_timezone'      => 'PRC',
    // 是否开启多语言
    'lang_switch_on'        => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'        => '',
    // 默认语言
    'default_lang'          => 'zh-cn',
    // 应用类库后缀
    'class_suffix'          => false,
    // 控制器类后缀
    'controller_suffix'     => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => '/index/Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'         => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'       => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'        => '/',
    // URL伪静态后缀
    'url_html_suffix'      => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'     => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'       => 0,
    // 是否开启路由
    'url_route_on'         => true,
    // 路由使用完整匹配
    'route_complete_match' => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'    => ['route'],
    // 是否强制使用路由
    'url_route_must'       => false,
    // 域名部署
    'url_domain_deploy'    => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'      => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'          => true,
    // 默认的访问控制器层
    'url_controller_layer' => 'controller',
    // 表单请求类型伪装变量
    'var_method'           => '_method',
    // 表单ajax伪装变量
    'var_ajax'             => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'             => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'        => false,
    // 请求缓存有效期
    'request_cache_expire' => null,

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template' => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'      => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => APP_PATH . 'admin/view/dispatch_jump.tpl',
    'dispatch_error_tmpl'   => APP_PATH . 'admin/view/dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'   => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',
    //'exception_tmpl'         => '/404.html',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle' => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log' => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'        => 'File',
        // 日志记录级别
        'level'       => [],
        //独立保存error和sql
        'apart_level' => ['error', 'sql'],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace' => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache' => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session' => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'lmq_admin_',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie' => [
        // cookie 名称前缀
        'prefix'    => 'lmq_home_',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'                => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    'http_exception_template' => [
        // 定义404错误的重定向页面地址
        404 => APP_PATH . '404.html',
    ],

    /*每分钟*/
    /*每小时 某分*/
    /*每天 某时:某分*/
    /*每周-某天 某时:某分  0=周日*/
    /*每月-某天 某时:某分*/
    /*某月-某日 某时-某分*/
    /*某年-某月-某日 某时-某分*/
    'sys_crond_timer' => array('*', '*:i', 'H:i', '@-w H:i', '*-d H:i', 'm-d H:i', 'Y-m-d H:i'),

    //网格接口端口
    'grid_port' => array('1' => '10090', '2' => '10091'),

    //短信模板,已废弃，政策原因该短信商不接入配资类，已修改为阿里云短信
    'sms_template' => [
        'stock_expend'                        => '用户#var#申请了扩大配资',
        'stock_addmoney'                      => '会员#var#申请了追加保证金,追加金额#amount#元',
        'stock_drawprofit'                    => '会员#var#申请了提取收益',
        'stock_renewal'                       => '会员#var#申请了配资延期',
        'stock_stop'                          => '会员#var#申请了终止配资',
        'stock_borrow_endedit'                => '会员#var#配资使用期限结束',
        'stock_handle_applySave'              => '会员#var#申请了配资',
        'stock_realname'                      => '会员#var#,申请了实名认证.',
        'stock_withdraw'                      => '会员#var#,申请了提现，金额#amount#元.',
        'stock_offline'                       => '会员#var#,申请了线下充值,金额#amount#元.',
        'register'                            => '#var#你好，你正在平台系统申请手机验证，验证码为',
        'stock_auditing'                      => '尊敬的会员#var#,您的订单号#order_id#的配资审核通过',
        'stock_realname_pass'                 => '尊敬的会员#var#,您的实名认证审核通过',
        'stock_realname_fail'                 => '尊敬的会员#var#,您的实名认证审核失败,请仔细填写',
        'stock_auditing_fail'                 => '尊敬的会员#var#,您的订单号#order_id#的配资审核失败',
        'stock_addfinancing_saveAddfinancing' => '尊敬的会员#var#,您的订单号#order_id#追加配资审核不通过,释放冻结资金',
        'stock_addfinancing_success'          => '尊敬的会员#var#,您的订单号#order_id#追加配资审核通过',
        'stock_addmoney_saveAddmoney'         => '尊敬的会员#var#,您的订单号#order_id#,追加保证金审核不通过，退回冻结金额',
        'stock_drawprofit_saveDrawprofit'     => '尊敬的会员#var#,您的订单号#order_id#申请提取盈利审核未通过',
        'stock_renewal_saveRenewal'           => '尊敬的会员#var#,您的订单号#order_id#扩大续期审核未通过，返回冻结服务费',
        'stock_offline_auditing_success'      => '尊敬的会员#var#,线下充值金额#amount#元,审核通过.',
        'stock_offline_auditing_fail'         => '尊敬的会员#var#,线下充值金额#amount#元,审核失败.',
        'stock_withdraw_auditing_success'     => '尊敬的会员#var#,提现金额#amount#元,审核通过.',
        'stock_withdraw_auditing_fail'        => '尊敬的会员#var#,提现金额#amount#元,审核失败.',
        'stock_loss_warn'                     => '尊敬的会员#var#,您的订单号#order_id#的配资的操盘资金低于预警线',
        'stock_loss_close'                    => '尊敬的会员#var#,您的订单号#order_id#的配资的操盘资金低于平仓线，已自动平仓',
    ],
    'ali_sms'      => [
        'SignName'     => '阿里云短信测试专用',
        'accessKeyId'  => 'LTAIybbPCsfoh0hp',
        'accessSecret' => 'Al2US7MEaNSNgG3Iw3zzSd9r2zRPt7',
        'TemplateCode' => [
            'register'  => 'SMS_100445079',
            'changepwd' => 'SMS_100445078',
        ],
    ],
];
