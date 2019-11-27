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

/**
 * 模块信息
 */
return [
    // 模块名[必填]
    'name'        => 'stock',
    // 模块标题[必填]
    'title'       => '配资模块',
    // 模块唯一标识[必填]，格式：模块名.开发者标识.module
    'identifier'  => 'stock.lei.module',
    // 模块图标[选填]
    'icon'        => 'fa fa-fw fa-pie-chart',
    // 模块描述[选填]
    'description' => '配资模块',
    // 开发者[必填]
    'author'      => '路人甲乙',
    // 开发者网址[选填]
    'author_url'  => 'http://www.lurenjiayi.com',
    // 版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
    'version'     => '1.0.0',
    // 模块依赖[可选]，格式[[模块名, 模块唯一标识, 依赖版本, 对比方式]]
    'need_module' => [
        ['admin', 'admin.lurenjiayi.module', '1.0.0']
    ],

    // 插件依赖[可选]，格式[[插件名, 插件唯一标识, 依赖版本, 对比方式]]
    'need_plugin' => [],
    // 数据表[有数据库表时必填]
    'tables' => [
        'stock_account',            //证券账户表
        'stock_account_info',       //证券账户详细信息表
        'stock_deal_stock',         //证券账户股票成交查询存储表
        'stock_delivery_order',     //证券账户股票交割单
        'stock_position',           //证券账户股票持仓存储表
        'stock_trust',              //证券账户股票委托存储表
        'stock_broker',             //证券类型表
        'stock_subaccount',         //子账户基础信息表
        'stock_subaccount_money',   //子账户资金信息表
        'stock_subaccount_risk',    //子账户风控信息表
        'stock_subaccount_self',    //子账户自选信息表
    ],
    // 原始数据库表前缀
    // 用于在导入模块sql时，将原有的表前缀转换成系统的表前缀
    // 一般模块自带sql文件时才需要配置
    'database_prefix' => 'lmq_',

    // 行为配置
    'action' => [
        [
            'module' => 'stock',
            'name' => 'broker_add',
            'title' => '添加证券类型',
            'remark' => '添加证券类型',
            'rule' => '',
            'log' => '[user|get_nickname] 添加了证券类型：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'broker_edit',
            'title' => '编辑证券类型',
            'remark' => '编辑证券类型',
            'rule' => '',
            'log' => '[user|get_nickname] 编辑了证券类型：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'broker_delete',
            'title' => '删除证券类型',
            'remark' => '删除证券类型',
            'rule' => '',
            'log' => '[user|get_nickname] 删除了证券类型：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'broker_disable',
            'title' => '禁用证券类型',
            'remark' => '禁用证券类型',
            'rule' => '',
            'log' => '[user|get_nickname] 禁用了证券类型：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'broker_enable',
            'title' => '启用证券类型',
            'remark' => '启用证券类型',
            'rule' => '',
            'log' => '[user|get_nickname] 启用了证券类型：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'account_add',
            'title' => '添加证券账户',
            'remark' => '添加证券账户',
            'rule' => '',
            'log' => '[user|get_nickname] 添加了证券账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'account_edit',
            'title' => '编辑证券账户',
            'remark' => '编辑证券账户',
            'rule' => '',
            'log' => '[user|get_nickname] 编辑了证券账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'account_delete',
            'title' => '删除证券账户',
            'remark' => '删除证券账户',
            'rule' => '',
            'log' => '[user|get_nickname] 删除了证券账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'account_disable',
            'title' => '禁用证券账户',
            'remark' => '禁用证券账户',
            'rule' => '',
            'log' => '[user|get_nickname] 禁用了证券账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'account_enable',
            'title' => '启用证券账户',
            'remark' => '启用证券账户',
            'rule' => '',
            'log' => '[user|get_nickname] 启用了证券账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'subaccount_add',
            'title' => '添加子账户',
            'remark' => '添加子账户',
            'rule' => '',
            'log' => '[user|get_nickname] 添加了子账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'subaccount_edit',
            'title' => '编辑子账户',
            'remark' => '编辑子账户',
            'rule' => '',
            'log' => '[user|get_nickname] 编辑了子账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'subaccount_delete',
            'title' => '删除子账户',
            'remark' => '删除子账户',
            'rule' => '',
            'log' => '[user|get_nickname] 删除了子账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'subaccount_disable',
            'title' => '禁用子账户',
            'remark' => '禁用子账户',
            'rule' => '',
            'log' => '[user|get_nickname] 禁用了子账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'subaccount_enable',
            'title' => '启用子账户',
            'remark' => '启用子账户',
            'rule' => '',
            'log' => '[user|get_nickname] 启用了子账户：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'subaccountRisk_edit',
            'title' => '编辑子账户风控信息',
            'remark' => '编辑子账户风控信息',
            'rule' => '',
            'log' => '[user|get_nickname] 编辑了编辑子账户风控信息：[details]',
            'status' => 1,
        ],
        [
            'module' => 'stock',
            'name' => 'subaccountMoney_edit',
            'title' => '编辑子账户资金风控信息',
            'remark' => '编辑子账户资金风控信息',
            'rule' => '',
            'log' => '[user|get_nickname] 编辑了编辑子账户资金风控信息：[details]',
            'status' => 1,
        ],

    ],
];
