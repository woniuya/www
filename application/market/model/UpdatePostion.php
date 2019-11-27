<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// 
namespace app\market\model;
use think\Model;
use think\Db;
class UpdatePostion extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBCOUNT_SELF__';
    /*
     * 拖动置顶更新位置
     * $settop 排序最终结果字符串
     */
    public function update_slef_postion($settop){
        if(empty($settop)) return false;
        else{
            $sort_arr = explode(",", $settop);//1|0
            foreach ($sort_arr as $k => $v){
                $sort_v = explode("|", $v);
                $id = intval($sort_v[0]);
                $sort = intval($sort_v[1]);
               Db::name('stock_subaccount_self')->where(["id"=>$id])->update(['sort'=>$sort]);
            }
        }
        return true;
    }
}