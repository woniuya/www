<?php
// +----------------------------------------------------------------------
// | 版权所有 2017~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
namespace app\market\model;
use think\Model;
use think\Db;
class StockSubAccount extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    public function get_account_by_id($id){
        $result=Db::name('stock_subaccount')->field(true)->where(['id'=>$id])->select();
        if(empty($result))return null;
        return $result[0];
    }
    public function get_account_by_uid($uid){
        $result=Db::name('stock_subaccount')->field(true)->where(['uid'=>$uid])->select();
        if(empty($result))return null;
        return $result;
    }
    public function get_account_by_name($subaccount){
        $result=Db::name('stock_subaccount')->field(true)->where(['sub_account'=>$subaccount])->find();
        if(empty($result))return null;
        return $result;
    }

    public function get_broker($id){
        $result=Db::name('stock_account')->field(true)->where(['id'=>$id])->find();
        if(empty($result))return null;
        return $result;
    }
    //检查是否安装路人甲乙插件
    public static function checkGreenSparrow(){
        $res=Db::name('admin_plugin')->where(['name'=>"GreenSparrow"])->find();
        if(empty($res)){
            return ['status' => 0, 'message' => '请安装路人甲乙实盘交易插件'];
        }else{
            return ['status' => 1, 'message' => '已安装路人甲乙实盘交易插件'];
        }
    }
    /*
     * 删除我子账号
     * @id 我子账号id
     */
    public function delmyselect($id){
        $result = self::destroy($id);
        return $result;
    }

}