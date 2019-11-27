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


class Transfer extends Validate
{
    //定义验证规则
    protected $rule = [
        'mobile|会员手机号'=>'require|regex:/^[1][3,4,5,7,8][0-9]{9}$/',
        'money|转账金额' => 'require|number',
        'info|详情'  => 'require|length:6,255',
        
    ];

    //定义验证提示
    protected $message = [
        'mobile.require' => '手机格式不正确',
        'money.require' => '请输入转账金额',
        'info.require'    => '详情不能为空6-255个字符',
        
    ];

    //定义验证场景
    protected $scene = [
        
        'save'=>  [ 'mobile','money', 'info'],
    ];
}
