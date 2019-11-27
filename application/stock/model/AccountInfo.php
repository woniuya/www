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
use think\helper\Hash;
use think\Db;

/**
 * 证券信息模型
 * @package app\stock\model
 */
class AccountInfo extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_ACCOUNT_INFO__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    //关联子账户基础表定义
    public function account()
    {
        return $this->belongsTo('Account');
    }

    /**
     * 获取券商基本信息
     * @param array $id 传递参数
     * @author 路人甲乙
     * @return mixed
     */
    public static function getAccountInfo($id)
    {
        $data_list = cache('stock_account_info');
        if (!$data_list) {
            $where['id'] =$id;
            $lid = Db::name('stock_account')->where($where)->column('lid');
            if(!is_null($lid)){
                $whereInfo['lid'] =$lid[0];
                $data_list = self::where($whereInfo)
                    ->select();
                    //->paginate();
                if(!is_null($data_list)){
                    // 非开发模式，缓存数据
                    if (config('develop_mode') == 0) {
                        cache('stock_account_info', $data_list);
                    }
                }
            }
        }
        return $data_list;
    }

    public  function get_broker($id){
        $result=Db::name('stock_account')->field(true)->where(['id'=>$id])->find();
        if(!isset($result)){
            return null;
        }
        return $result;
    }
    /*
     * 返回实盘账户
     */
    public function getAllBroker(){
        $result=Db::name('stock_account')
            ->where(['type'=>1])
            ->select();
        if(!isset($result)){
            return null;
        }
        return $result;
    }

}