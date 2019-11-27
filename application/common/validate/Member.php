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

namespace app\common\validate;

use think\Validate;


class Member extends Validate
{
    //定义验证规则
    protected $rule = [
        'mobile|手机号' => 'require|regex:^1\d{10}',
        'password|密码' => 'require|regex:^[a-zA-Z\d_]{6,16}$',
    ];

    //定义验证提示
    protected $message = [
        'mobile.require' => "请输入手机号码",
        'mobile.regex' => '手机号不正确',

        'password.require' => '请输入密码',
        'password.regex' => '密码格式有误(a-zA-Z0-9_) 6-16个字符',
    ];

    //定义验证场景
    protected $scene = [
        //登录
        'signin' => ['mobile' => 'require', 'password'],
    ];
}
