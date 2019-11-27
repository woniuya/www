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
 * 菜单信息
 */
return [
    [
        'title'       => '用户代理',
        'icon'        => 'fa fa-fw fa-newspaper-o',
        'url_type'    => 'module_admin',
        'url_value'   => 'agents/manager/agent_list',
        'url_target'  => '_self',
        'online_hide' => 0,
        'sort'        => 100,
        'child'       => [
            [
                'title'       => '代理商管理',
                'icon'        => 'fa fa-fw fa-tachometer',
                'url_type'    => 'module_admin',
                'url_value'   => 'agents/manager/agent_list',
                'url_target'  => '_self',
                'online_hide' => 0,
                'sort'        => 100,
                'child'       => [
					[
						'title'       => '新增代理商',
						'icon'        => 'fa fa-fw fa-tachometer',
						'url_type'    => 'module_admin',
						'url_value'   => 'agents/manager/agent_add',
						'url_target'  => '_self',
						'online_hide' => 1,
						'sort'        => 100,
						'child'       => []
					],
					[
						'title'       => '编辑代理商',
						'icon'        => 'fa fa-fw fa-tachometer',
						'url_type'    => 'module_admin',
						'url_value'   => 'agents/manager/edit',
						'url_target'  => '_self',
						'online_hide' => 1,
						'sort'        => 100,
						'child'       => []
					],
					[
						'title'       => '模糊查询手机号',
						'icon'        => 'fa fa-fw fa-tachometer',
						'url_type'    => 'module_admin',
						'url_value'   => 'agents/manager/getLikeMobile',
						'url_target'  => '_self',
						'online_hide' => 1,
						'sort'        => 100,
						'child'       => []
					]
					
				],
            ],
			[
                'title'       => '邀请记录',
                'icon'        => 'fa fa-fw fa-tachometer',
                'url_type'    => 'module_admin',
                'url_value'   => 'agents/manager/application_invite',
                'url_target'  => '_self',
                'online_hide' => 0,
                'sort'        => 100,
                'child'       => [],
            ],
			[
                'title'       => '提现记录',
                'icon'        => 'fa fa-fw fa-tachometer',
                'url_type'    => 'module_admin',
                'url_value'   => 'agents/manager/application_record',
                'url_target'  => '_self',
                'online_hide' => 0,
                'sort'        => 100,
                'child'       => [],
            ],
			[
                'title'       => '佣金分成明细',
                'icon'        => 'fa fa-fw fa-tachometer',
                'url_type'    => 'module_admin',
                'url_value'   => 'agents/manager/agent_share',
                'url_target'  => '_self',
                'online_hide' => 0,
                'sort'        => 100,
                'child'       => [],
            ],
        ],
    ],
];
