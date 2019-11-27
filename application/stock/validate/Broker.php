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
 * 券商类型验证器
 * @package app\stock\validate
 * @author 路人甲乙
 */
class Broker extends Validate
{
    //定义验证规则
    protected $rule = [
        'broker_id|券商ID' => 'require|number|unique:stock_broker',
        'broker_value|券商名称'  => 'require|chs|unique:stock_broker',
        'clienver|版本号'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'broker_id.require'     => '请输入券商类型ID 如：1代表长江证券',
        'broker_value.require'       => '请输入券商名称',
        'broker_value.unique'       => '券商名称已存在',
        'clienver.require'       => '请输入券商名称',
    ];
    //定义验证场景
    protected $scene = [

        //新增
        'insert'=>['broker_id','broker_value','clienver'],
        //更新
        'update'  =>  ['clienver'],
    ];
}
