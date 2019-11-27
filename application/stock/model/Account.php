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
use app\stock\model\AccountInfo as AccountInfoModel;
use think\Db;
use think\helper\Hash;

/**
 * 证券信息模型
 * @package app\stock\model
 */
class Account extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_ACCOUNT__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    //关联子账户资金表定义
    public function accountInfo()
    {
        return $this->hasOne('AccountInfo','stock_account_id');
    }

    /**
     * 获取证券账户列表
     * @param array $map 筛选条件
     * @param array $order 排序
     * @author 路人甲乙
     * @return mixed
     */
    public static function getList($map = [], $order = [])
    {
        $data_list = self::view('stock_account', true)
            ->where($map)
            ->order($order)
            ->paginate();
        return $data_list;
    }

    /**
     * 获取券商类型列表(下拉选择时使用)
     * @param array $map 筛选条件
     * @param array $order 排序
     * @author 路人甲乙
     * @return mixed
     */
    public static function getBrokerList($map = [], $order = [])
    {
        $data_list = cache('stock_broker_list');
        $list = [];
        if (!$data_list) {
            $data_list = Db::name('stock_broker')
                ->where('status', 1)
                ->where($map)
                ->order($order)
                ->column(true, 'id');
            foreach ($data_list as $k=>$v){
                $list[$v['broker_id']] = $v['broker_value'];
            }
            // 非开发模式，缓存数据
            if (config('develop_mode') == 0) {
                cache('stock_broker_list', $list);
            }
        }else{
            return $data_list;
        }
        return $list;
    }

    /**
     * 获取券商类型信息
     * @param array $id 传递参数
     * @author 路人甲乙
     * @return mixed
     */
    public static function getBroker($id)
    {
        $where['broker_id'] =$id;
        $where['status'] = 1;
        $data_list = Db::name('stock_broker')->where($where)->find();//->column(true, 'id');
        if(!$data_list){
            return null;
        }
        return $data_list;
    }


    /**
     * 新增券商基本信息
     * @param array $data 传递参数
     * @author 路人甲乙
     * @return mixed
     */
    public static function addAccount($data)
    {
        $result = false;

        $account = self::create($data);
        $resu = $account->accountInfo()->save($data);//关联更新证券账户资金详情表
        if ($resu) {
            //获取新增的证券账户ID并增加证券明细表记录
            $accountId = self::getLastInsID();
            $AccountInfo = AccountInfoModel::get($accountId);
            $info['soruce'] = !isset($account['stockjobber']) ? '模拟账户' : $account['stockjobber'];
            $info['lid'] = $account['lid'];
            $info['user'] = $account['user'];
            $res = $AccountInfo->save($info);

            if ($res !== false) {
                $result = true;
            }
        }else{
            $result = false;
        }
        return $result;
    }

    /*
     * 获取股票委托基本信息
     * @param array $lid 传递参数
     * @author 路人甲乙
     * @return mixed
     */
    public static function getTrust($lid,$user)
    {
        $data_list = cache('stock_trust');
        if (!$data_list) {
            $where['lid'] = $lid;
            $where['login_name'] = $user;
            $data_list = Db::name('stock_trust')->where($where)->paginate();

            if(!is_null($data_list)){
                // 非开发模式，缓存数据
                if (config('develop_mode') == 0) {
                    cache('stock_trust', $data_list);
                }
            }
        }
        return $data_list;
    }

    /*
     * 根据证券账户ID获取该证券账户信息
     * @param $id  子账户ID
     * @author 路人甲乙
     * @return mixed
     */
    public static function getAccountByID($id='')
    {
        $data_list = self::where('id', $id)->field('*', false)->find();
        return $data_list;
    }


    /*
     * 根据证券ID获取证券账户类型名称
     * @param $id  证券ID
     * @author 路人甲乙
     * @return mixed
     */
    public static function getBrokerNameByID($id='')
    {
        $name = self::where('id',$id)->column('stockjobber');
        return  $name[0];
    }
}