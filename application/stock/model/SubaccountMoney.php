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
class SubaccountMoney extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_SUBACCOUNT_MONEY__';

    //关联子账户基础表定义
    public function subaccount()
    {
        return $this->belongsTo('Subaccount');
    }

    /**
     * 查看子账户的资金状况
     * @param $id  子账户ID
     * @author 路人甲乙
     * @return mixed
     */
        public static function getMoneyByID($id=null)
    {
        $data_list = self::where('stock_subaccount_id',$id)
            ->field('*', false)
            ->find();

            if(!is_null($data_list)){
                $data_list['min_commission']=money_convert($data_list['min_commission']);
                $data_list['avail']=money_convert($data_list['avail']);
                $data_list['available_amount']=money_convert($data_list['available_amount']);
                $data_list['freeze_amount']=money_convert($data_list['freeze_amount']);
                $data_list['return_money']=money_convert($data_list['return_money']);
                $data_list['borrow_money']=money_convert($data_list['borrow_money']);
                $data_list['deposit_money']=money_convert($data_list['deposit_money']);
                $data_list['stock_addfinancing']=money_convert($data_list['stock_addfinancing']);
                $data_list['stock_addmoney']=money_convert($data_list['stock_addmoney']);
                $data_list['stock_drawprofit']=money_convert($data_list['stock_drawprofit']);
            }
        return $data_list;
    }

    /*
     * 更新子账户资金表
     * $data 更新数据
     * @param $id  子账户ID
     */
    public static function saveSubMoney($data,$id){
           return self::where('stock_subaccount_id',$id)->update($data);
    }


}