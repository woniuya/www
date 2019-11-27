<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------

namespace app\money\validate;

use think\Validate;


class Recharge extends Validate
{
    //定义验证规则
    protected $rule = [
        //'money|充值金额' => 'require|unique:member',
        'remark|备注'  => 'require|length:3,255',
        'status|充值状态'=>'require',
        'money|充值金额'=>'require|number',
        'charge_type|充值类型'=>'require',
        'transfer|充值人'=>'require|regex:/^[\x{4e00}-\x{9fa5}]{2,10}$/u',

    ];

    //定义验证提示
    protected $message = [
        'money.require' => '请输入充值金额',
        'money.number' => '充值金额必须为数字',
        'remark.require'    => '备注不能为空3-255个字符',
        'status.require' => '请选择充值状态',
        'transfer.require' => '请输入充值人',
        'transfer.regex' => '只能是1-5个汉字',
    ];

    //定义验证场景
    protected $scene = [
        //更新
        'update'  =>  [ 'status', 'remark'],
        'newdata' => ['money','charge_type','transfer'],
        'online' => ['money','charge_type'],
    ];
}
