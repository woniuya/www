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


class Bank extends Validate
{
    //定义验证规则
    protected $rule = [
        'card|银行卡号'   => 'require|regex:^\d{15,19}$|unique:admin_bank',
        'bank_name|所属银行' => 'require',
        'payee|收款人' => 'require',
        'notes|说明' => 'require',
        'open_bank|开户行'  => 'require',

        
    ];

    //定义验证提示
    protected $message = [
        'card.regex' => '请输入15-19位卡号',
    ];

    //定义验证场景
    protected $scene = [
        //更新
        //'create'  =>  [ 'card' , 'bank_name','payee','notes','open_bank'],
        'create'  =>  [ 'bank_name','payee','notes'],
    ];
}
