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
class Subaccount extends Validate
{
    //定义验证规则
    protected $rule = [
        'sub_account|子账户名称' => 'require|chsDash|unique:stock_subaccount',
        'sub_pwd|子账户密码' => 'require|alphaDash',
        'agent_id|所属上级' => 'require|number',
        'account_id|所属证券账户' => 'require|number',
    ];

    //定义验证提示
    protected $message = [
        'sub_account.require'     => '请输入子账户名称',
        'sub_account.unique'     => '该子账户名称已存在',
        'sub_account.chsDash'     => '该子账户名称格式只能是汉字、字母、数字和下划线_及破折号-',
        'sub_pwd.require'       => '请输入子账户密码',
        'sub_pwd.alphaDash'    => '该子账户名称格式只能是字母、数字和下划线_及破折号-',
        'agent_id.require'       => '请选择所属代理商',
        'account_id.require'       => '请选择证券账户名',
    ];
    //定义验证场景
    protected $scene = [
        //更新
        'insert'  =>  ['sub_account', 'sub_pwd','account_id','agent_id'],
        //更新
        'update'  =>  ['sub_account'],
    ];
}
