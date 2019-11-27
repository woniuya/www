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
// | 作者:绿麻雀
// +----------------------------------------------------------------------
// [ PHP版本检查 ]


header("Content-type: text/html; charset=utf-8");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, HEAD, GET, OPTIONS, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
header('Access-Control-Max-Age: 1728000');

if (version_compare(PHP_VERSION, '5.5', '<')) {
    die('PHP版本过低，最少需要PHP5.5，请升级PHP版本！');
}

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

// 定义后台入口文件
define('ADMIN_FILE', 'admin.php');
define("EXTEND_PATH",'../extend/');
define('JWT_TOKEN_KEY', "fdasfdsafjkdfjkladsjfklsdjlk");
if(!is_file(__DIR__ . '/../data/install.lock')){
    define('BIND_MODULE', 'install');
}
// 加载框架基础文件
require __DIR__ . '/../thinkphp/base.php';

// cli 模式关闭路由
if (PHP_SAPI == 'cli'){
    \think\App::route(false);
}
// 执行应用
\think\App::run()->send();


