<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    'queryapi_echart'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['controller', 'view'],
        'controller' => ['Index'],
        'view'       => ['index/index'],
    ],
    'queryapi_sinachart'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['controller', 'view'],
        'controller' => ['Index'],
        'view'       => ['index/index'],
    ],
  'websocket_echart'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['controller', 'view'],
        'controller' => ['Index'],
        'view'       => ['index/index'],
    ],
  'websocket_tdview'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['controller', 'view'],
        'controller' => ['Index'],
        'view'       => ['index/index'],
    ],
    'queryapi_tdview'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view','lang'],
        'controller' => ['Index'],
        'model'      => ['Test'],
        'view'       => ['index/index'],
    ]
];
