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
class SubaccountMoney extends Validate
{
    //定义验证规则
    protected $rule = [
        'profit_share_scale|盈利分成比例'      => 'require|float|egt:0',
        'rate_scale|配资管理费分成比例'     => 'require|float|egt:0',
        'commission_scale|佣金比例'      => 'require|float|egt:0',//['regex'=>'^[0-9]\d*\.\d*|0\.\d*[0-9]\d*'],
        'min_commission|最低佣金'      => 'require|float|egt:0',//['regex'=>'^[0-9]\d*\.\d*|0\.\d*[0-9]\d*'],
    ];

    //定义验证提示
    protected $message = [
        'commission_scale.egt'  => '佣金比例必须为正数',
        'min_commission.egt'  => '最低佣金必须为正数',
        'rate_scale.egt'  => '配资管理费分成比例必须为正数',
        'profit_share_scale.egt'  => '盈利分成比例必须为正数',
    ];
    //定义验证场景
    protected $scene = [
        //更新
        'update'  =>  ['commission_scale', 'min_commission','rate_scale','profit_share_scale'],
    ];
}
