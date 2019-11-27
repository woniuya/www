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
  'name' => 'money',
  'title' => '资金',
  'identifier' => 'money.jili.module',
  'icon' => 'fa fa-fw fa-rmb',
  'author' => 'ZhangJiLi',
  'author_url' => 'http://www.lurenjiayi.com',
  'version' => '1.0.0',
  'description' => '资金模块',
  'need_module' => [],
  'need_plugin' => [],
  'action' => [
    [
      'module' => 'money',
      'name' => 'money_edit',
      'title' => '资金账户修改',
      'remark' => '资金账户修改',
      'rule' => '',
      'log' => '[user|get_nickname] 修改了会员资金账户：[details]',
      'status' => 1,
    ],
    [
      'module' => 'money',
      'name' => 'withdraw_edit',
      'title' => '提现审核',
      'remark' => '提现审核',
      'rule' => '',
      'log' => '[user|get_nickname] 审核了会员提现：[details]',
      'status' => 1,
    ],
    [
      'module' => 'money',
      'name' => 'recharge_edit',
      'title' => '充值审核',
      'remark' => '充值审核',
      'rule' => '',
      'log' => '[user|get_nickname] 审核了会员充值：[details]',
      'status' => 1,
    ],
    [
      'module' => 'money',
      'name' => 'transfer_add',
      'title' => '后台转账',
      'remark' => '后台转账',
      'rule' => '',
      'log' => '[user|get_nickname] 向会员转账：[details]',
      'status' => 1,
    ],
  ],
  'database_prefix' => 'lmq_',
];
