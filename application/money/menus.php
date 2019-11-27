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
    'title' => '资金',
    'icon' => 'fa fa-fw fa-money',
    'url_type' => 'module_admin',
    'url_value' => 'money/index/index',
    'url_target' => '_self',
    'online_hide' => 0,
    'sort' => 100,
    'status' => 1,
    'child' => [
      [
        'title' => '资金管理',
        'icon' => '',
        'url_type' => 'module_admin',
        'url_value' => '',
        'url_target' => '_self',
        'online_hide' => 0,
        'sort' => 100,
        'status' => 1,
        'child' => [
          [
            'title' => '资金列表',
            'icon' => 'fa fa-fw fa-list',
            'url_type' => 'module_admin',
            'url_value' => 'money/index/index',
            'url_target' => '_self',
            'online_hide' => 0,
            'sort' => 99,
            'status' => 1,
          ],
          [
            'title' => '充值管理',
            'icon' => 'fa fa-fw fa-reply',
            'url_type' => 'module_admin',
            'url_value' => 'money/recharge/index',
            'url_target' => '_self',
            'online_hide' => 1,
            'sort' => 100,
            'status' => 1,
            'child' => [
              [
                'title' => '充值审核',
                'icon' => '',
                'url_type' => 'module_admin',
                'url_value' => 'money/recharge/edit',
                'url_target' => '_self',
                'online_hide' => 0,
                'sort' => 100,
                'status' => 1,
              ],
            ],
          ],
          [
            'title' => '提现管理',
            'icon' => 'fa fa-fw fa-share',
            'url_type' => 'module_admin',
            'url_value' => 'money/withdraw/index',
            'url_target' => '_self',
            'online_hide' => 0,
            'sort' => 100,
            'status' => 1,
            'child' => [
              [
                'title' => '提现审核',
                'icon' => '',
                'url_type' => 'module_admin',
                'url_value' => 'money/withdraw/edit',
                'url_target' => '_self',
                'online_hide' => 0,
                'sort' => 100,
                'status' => 1,
              ],
            ],
          ],
          [
            'title' => '提盈管理',
            'icon' => 'fa fa-fw fa-external-link',
            'url_type' => 'module_admin',
            'url_value' => '',
            'url_target' => '_self',
            'online_hide' => 0,
            'sort' => 100,
            'status' => 1,
          ],
          [
            'title' => '清算明细',
            'icon' => 'fa fa-fw fa-random',
            'url_type' => 'module_admin',
            'url_value' => 'money/record/index',
            'url_target' => '_self',
            'online_hide' => 0,
            'sort' => 100,
            'status' => 1,
          ],
          [
            'title' => '转账管理',
            'icon' => 'fa fa-fw fa-sign-in',
            'url_type' => 'module_admin',
            'url_value' => 'money/transfer/index',
            'url_target' => '_self',
            'online_hide' => 0,
            'sort' => 100,
            'status' => 1,
            'child' => [
              [
                'title' => '发起转账',
                'icon' => '',
                'url_type' => 'module_admin',
                'url_value' => 'money/transfer/add',
                'url_target' => '_self',
                'online_hide' => 0,
                'sort' => 100,
                'status' => 1,
              ],
            ],
          ],
        ],
      ],
    ],
  ],
];
