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

namespace app\agents\validate;

use think\Validate;
class Agents extends Validate
{
    //定义验证规则
    protected $rule = [
        'agent_rate|分佣比例' => 'require|between:1,100',
        'agent_pro|代理状态' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'mobile.regex'     => '手机号不正确',
    ];

    //定义验证场景
    protected $scene = [
        //更新
        'create'  =>  [  'agent_rate', 'agent_pro'],
    ];
}
