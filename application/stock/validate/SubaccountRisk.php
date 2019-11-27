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
class SubaccountRisk extends Validate
{
    //定义验证规则
    protected $rule = [
        'loss_warn|预警线'      => 'require|float|between:0,100',
        'loss_close|平仓线'     => 'require|float|between:0,100',
        'position|个股持仓比例'      => 'require|float|between:0,100',//['regex'=>'^[0-9]\d*\.\d*|0\.\d*[0-9]\d*'],
    ];

    //定义验证提示
    protected $message = [
        'loss_warn.between'  => '预警线必须是0-100范围区间的数字',
        'loss_close.between'  => '平仓线必须是0-100范围区间的数字',
        'position.between'  => '个股持仓比例必须是0-100范围区间的数字',
    ];
    //定义验证场景
    protected $scene = [
        //更新
        'update'  =>  ['loss_warn', 'loss_close','position'],
    ];
}
