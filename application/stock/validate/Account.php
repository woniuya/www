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

namespace app\stock\validate;

use think\Validate;

/**
 * 证券信息验证器
 * @package app\stock\validate
 * @author 路人甲乙
 */
class Account extends Validate
{
    //定义验证规则
    protected $rule = [
        'stockjobber|券商名称' => 'require|chs',
        'lid|证券账户名' => 'require|alphaNum|unique:stock_account',
        'user|交易通名称'      => 'require',
        'pwd|交易通密码'     => 'require',
        'commission_scale|佣金比例'      => 'require|float|egt:0',//['regex'=>'^[0-9]\d*\.\d*|0\.\d*[0-9]\d*'],
        'min_commission|最低佣金'      => 'require|float|egt:0',//['regex'=>'^[0-9]\d*\.\d*|0\.\d*[0-9]\d*'],
    ];

    //定义验证提示
    protected $message = [
        'stockjobber.require'     => '请输入券商名称',
        'lid.unique'     => '证券账户名已存在',
        'lid.require'       => '请输入证券账户名',
        'user.require'    => '交易通账户不能为空',
        'pwd.require'     => '密码不能为空',
        //'commission_scale.regex'  => '佣金比例必须为正浮点数 如：8.0',
        'commission_scale.egt'  => '佣金比例必须为正数',
        'min_commission.egt'  => '最低佣金必须为正数',
    ];
    //定义验证场景
    protected $scene = [
        //新增
        'insert'  =>  ['stockjobber','lid', 'user', 'pwd','commission_scale','min_commission'],
        //更新
        'update'  =>  ['user', 'pwd','commission_scale','min_commission'],
    ];
}
