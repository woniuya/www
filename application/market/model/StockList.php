<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author gs101
namespace app\market\model;
use think\Model;
use think\Db;
class StockList extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_LIST__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}