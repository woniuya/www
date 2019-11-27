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

namespace app\apicom\validate;

use think\Validate;


class Withdraw extends Validate
{
    //定义验证规则
    protected $rule = [
        //'money|提现金额' => 'require|unique:member',
        'remark|备注'  => 'require|length:6,255',
        'status|充值状态'=>'require', 
        'money|提现金额' => 'require',
        'paywd|支付密码' => 'require',
        'bank_id|提现银行卡' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'money.require' => '请输入提现金额',
        'remark.require'    => '备注不能为空6-255个字符',
        'status.require' => '请选择提现状态',
       //  'money.regex'=>'充值金额只能是100的整倍数'
    ];

    //定义验证场景
    protected $scene = [
        //更新
        'update'  =>  [ 'status', 'remark'],
        'create'  =>  ['money', 'paywd', 'bank_id'],
    ];
}
