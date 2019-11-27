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

namespace app\stock\model;

use think\Model as ThinkModel;
use think\Db;

/**
 * 证券信息模型
 * @package app\stock\model
 */
class SubaccountRisk extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT_RISK__';

    //关联子账户基础表定义
    public function subaccount()
    {
        return $this->belongsTo('Subaccount');
    }

    /**
     * 查看子账户的风控设置
     * @param $id  子账户ID
     * @author 路人甲乙
     * @return mixed
     */
    public static function getRiskByID($id='')
    {
        $data_list = self::where('stock_subaccount_id',$id)->field('*', false)->find();
        return $data_list;
    }
}