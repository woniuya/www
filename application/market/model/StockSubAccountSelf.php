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
class StockSubAccountSelf extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT_SELF__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    /*
     * 添加我的自选
     * $uid 会员id
     * $name 股票名称
     * $code 股票代码
     * $sub_id 子账户id
     */
    public function addmyselect($uid,$name,$code,$sub_id=0){
        $data['uid']=$uid;
        $data['sub_id']=$sub_id;
        $data['gupiao_name']=$name;
        $data['gupiao_code']=$code;
        $data['creat_time']=time();
        $data['add_ip']=get_client_ip(1,false);
        $result = self::create($data);
        return $result;
    }
    /*
     * 删除我的自选
     * @id 我的自选股票id
     */
    public function delmyselect($id){
        $result = self::destroy($id);
        return $result;
    }
    /*
     * 查找我的自选
     */
    public function myadd($uid,$code){
        $data=Db::name('stock_subaccount_self')
            ->where('uid='.$uid)
            ->where('gupiao_code='.$code)
            ->find();
        return $data;
    }
    /*
     * 删除我的自选
     */
    public function delmyselectbycode($uid,$code){
        $data=Db::name('stock_subaccount_self')
            ->where('uid='.$uid)
            ->where('gupiao_code='.$code)
            ->delete();
        return $data;
    }
}