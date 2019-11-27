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
 * 证券类型模型
 * @package app\stock\model
 */
class Broker extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_BROKER__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取证券类型列表
     * @param array $map 筛选条件
     * @param array $order 排序
     * @author 路人甲乙
     * @return mixed
     */
    public static function getList($map = [], $order = [])
    {
        $data_list = self::view('stock_broker', true)
            ->where($map)
            ->order($order)
            ->paginate();
        return $data_list;
    }

}