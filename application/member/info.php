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
  'name' => 'member',
  'title' => '会员',
  'identifier' => 'member.lurenjiayi.module',
  'icon' => 'fa fa-fw fa-users',
  'author' => 'ZhangJiLi',
  'author_url' => 'http://www.lurenjiayi.com',
  'version' => '1.0.0',
  'description' => '会员模块',
  'need_module' => [
    [
      'admin',
      'admin.lurenjiayi.module',
      '1.0.0',
    ],
  ],
  'need_plugin' => [],
  'tables' => [
    'member',
    'member_message',
    'member_invitation_relation',
    'member_invitation_record',
  ],
  'database_prefix' => 'lmq_',
  'config' => [
    [
      'radio',
      'member_is_login',
      '前台会员登录',
      '',
      [
        '关闭',
        '开启',
      ],
      '1',
    ],
    [
      'radio',
      'member_is_register',
      '前台会员注册',
      '',
      [
        '关闭',
        '开启',
      ],
      '1',
    ],
  ],
  'action' => [
    [
      'module' => 'member',
      'name' => 'member_edit',
      'title' => '编辑会员信息',
      'remark' => '编辑会员信息',
      'rule' => '',
      'log' => '[user|get_nickname] 修改了会员：[details]',
      'status' => 1,
    ],
    [
      'module' => 'member',
      'name' => 'member_delete',
      'title' => '删除会员',
      'remark' => '删除会员',
      'rule' => '',
      'log' => '[user|get_nickname] 删除了会员：[details]',
      'status' => 1,
    ],
    [
      'module' => 'member',
      'name' => 'member_disable',
      'title' => '开启会员登陆',
      'remark' => '开启会员登陆',
      'rule' => '',
      'log' => '[user|get_nickname] 修改了会员登录状态：[details]',
      'status' => 1,
    ],
    [
      'module' => 'member',
      'name' => 'member_enable',
      'title' => '禁止登陆',
      'remark' => '会员禁止登陆',
      'rule' => '',
      'log' => '[user|get_nickname] 禁止会员登录：[details]',
      'status' => 1,
    ],
    [
      'module' => 'member',
      'name' => 'member_id_auth',
      'title' => '身份认证',
      'remark' => '身份认证',
      'rule' => '',
      'log' => '[user|get_nickname] 修改了身份认证信息：[details]',
      'status' => 1,
    ],
    [
      'module' => 'member',
      'name' => 'member_edit',
      'title' => '编辑会员信息',
      'remark' => '编辑会员信息',
      'rule' => '',
      'log' => '[user|get_nickname] 修改了会员：[details]',
      'status' => 1,
    ],
  ],
];
