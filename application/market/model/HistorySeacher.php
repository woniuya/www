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
class HistorySeacher extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_HISTTORY__';
    /*
     * 拖动置顶更新位置
     * $code 股票代码
     * $code_title 股票标题
     * $mid 用户id
     */
    public function add_histtory($code,$code_title,$mid){
        $mid = intval($mid);
        $code = htmlspecialchars($code);
        $code_title = htmlspecialchars($code_title);

        $findArr = Db::name('stock_history')->where("code = {$code}")->find();
        if(empty($findArr)){
            $row=array();
            $row['mid'] = $mid;
            $row['code'] = $code;
            $row['code_title'] = $code_title;
            $row['status'] = 1;//显示状态
            $row['count'] = 1;
            $row['add_time'] = strtotime(date('Y-m-d',time()));//委托日期
            $result = Db::name('stock_history')->insert($row,true);
        }else{
            $data['count'] = $findArr['count'] + 1;
            $result = Db::name('stock_history')->where("code = {$code}")->update($data);
        }
        return $result;
    }
   
}