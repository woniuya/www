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
class Delivery extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_DELIVERY_ORDER__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    /*
     * 存储交割单记录
     * $data 持仓数据
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function add_delivery_order($data,$lid,$user,$soure){
        $row=array();
        foreach ($data as $k => $value) {
            $row[$k]['lid'] = $lid;
            $row[$k]['soruce'] = $soure;
            $row[$k]['login_name'] = $user;
            $row[$k]['deal_date'] = $value[0];//成交日期
            $row[$k]['business_name'] = $value[1];//业务名称
            $row[$k]['gupiao_code'] = $value[2];//证券代码
            $row[$k]['gupiao_name'] = $value[3];//证券名称
            $row[$k]['deal_price'] = $value[4];//成交价格
            $row[$k]['volume'] = $value[5];//成交数量
            $row[$k]['amount'] = $value[6];//剩余数量
            $row[$k]['residual_quantity'] = $value[7];//成交金额
            $row[$k]['liquidation_amount'] = $value[8];//清算金额
            $row[$k]['residual_amount'] = $value[9];//剩余金额
            $row[$k]['stamp_duty'] = $value[10];//印花税
            $row[$k]['transfer_fee'] = $value[11];//过户费
            $row[$k]['commission'] = $value[12];//净佣金
            $row[$k]['transaction_fee'] = $value[13];//交易规费
            $row[$k]['front_desk_fee'] = $value[14];//前台费用
            $row[$k]['trust_no'] = $value[15];//委托编号
            $row[$k]['deal_no'] = $value[16];//成交编号
            $row[$k]['gudong_code'] = $value[17];//股东代码
            $row[$k]['type'] = $value[18];//帐号类别
            $row[$k]['add_time'] = strtotime($value[0]);//成交日期时间戳
        }
        $result = Db::name('stock_delivery_order_broker')->insertall($row,true);
        return $result;
    }

    /*
     * 返回对应交割单
     * $sub_id 子账号
     */
    public function get_delivery_order($sub_id,$beginday="",$endday=""){
        if(empty($endday)){$endday=time();}else{$endday=strtotime($endday)+86400;}
        if(empty($beginday)){$beginday=strtotime(date("Y-m-d",time()));}else{$beginday=strtotime($beginday);}
        $res=Db::name('stock_delivery_order')
            ->where(['sub_id'=>$sub_id])
            ->where(['status'=>1])
            ->where('deal_date','>=',$beginday)
            ->where('deal_date','<=',$endday)
            ->order('id desc')
            ->select();
        return $res;
    }
    /*
     * 返回子账号和股票代码对应的交割单
     * $sub_id 子账号
     * $code 股票代码
     */
    public function get_code_delivery_order($sub_id,$code){
        $res=Db::name('stock_delivery_order')
            ->where(['sub_id'=>$sub_id,'gupiao_code'=>$code])
            ->where(['status'=>1])
            ->order('id desc')
            ->select();
        return $res;
    }

    /*
     * 添加卖出模拟交割单记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function sell_m_delivery_order($stockinfo,$count,$price,$sub_id,$lid,$user,$soure,$commission,$Transfer,$Trust_no,$stamp,$avail,$amount,$model){
        $data=array();
        $data[0]['sub_id'] = $sub_id;
        $data[0]['lid'] = $lid;
        $data[0]['soruce'] = $soure;
        $data[0]['login_name'] = $user;
        $data[0]['gupiao_code'] = $stockinfo["code"];
        $data[0]['gupiao_name'] = $stockinfo["name"];
        $data[0]['deal_date'] = time();
        $data[0]['business_name'] = "证券卖出";
        $data[0]['deal_price'] = $price;
        $data[0]['volume'] = $count;
        $data[0]['amount'] = $amount;
        $data[0]['residual_quantity'] = $count*$price;//成交金额
        $data[0]['liquidation_amount'] = $count*$price-$commission-$Transfer-$stamp;//清算金额
        $data[0]['residual_amount'] = $avail;
        $data[0]['stamp_duty'] = $stamp;
        $data[0]['transfer_fee'] = $Transfer;
        $data[0]['commission'] = $commission;
        $data[0]['transaction_fee'] = 0;
        $data[0]['front_desk_fee'] = 0;
        $data[0]['trust_no'] = $Trust_no;
        $data[0]['deal_no'] = $Trust_no+521;
        $data[0]['gudong_code'] = "";//股东代码 无法模拟暂时空
        $data[0]['type'] = $stockinfo["exchange_code"];//帐号类别
        if($model==1){
            $data[0]['status'] = 0;//状态 0、未确认 1、已确认
        }else{
            $data[0]['status'] = 1;//状态 0、未确认 1、已确认
        }
        $result = Db::name('stock_delivery_order')->insertall($data,true);
        return $result;

    }
    /*
     * 存储模拟交割单记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function add_m_delivery_order($stockinfo,$count,$price,$sub_id,$lid,$user,$soure,$commission,$Transfer,$Trust_no,$avail,$amount,$model){
            $data=array();
            $data[0]['sub_id'] = $sub_id;
            $data[0]['lid'] = $lid;
            $data[0]['soruce'] = $soure;
            $data[0]['login_name'] = $user;
            $data[0]['gupiao_code'] = $stockinfo["code"];
            $data[0]['gupiao_name'] = $stockinfo["name"];
            $data[0]['deal_date'] = time();
            $data[0]['business_name'] = "证券买入";
            $data[0]['deal_price'] = $price;
            $data[0]['volume'] = $count;
            $data[0]['amount'] = $amount;
            $data[0]['residual_quantity'] = $count*$price;
            $data[0]['liquidation_amount'] = $count*$price+$commission+$Transfer;//清算金额
            $data[0]['residual_amount'] = $avail;
            $data[0]['stamp_duty'] = 0;
            $data[0]['transfer_fee'] = $Transfer;
            $data[0]['commission'] = $commission;
            $data[0]['transaction_fee'] = 0;
            $data[0]['front_desk_fee'] = 0;
            $data[0]['trust_no'] = $Trust_no;
            $data[0]['deal_no'] = $Trust_no+521;
            $data[0]['gudong_code'] = "";//股东代码 无法模拟暂时空
            $data[0]['type'] = $stockinfo["exchange_code"];//帐号类别
            if($model==1){
                $data[0]['status'] = 0;//状态 0、未确认 1、已确认
            }else{
                $data[0]['status'] = 1;//状态 0、未确认 1、已确认
            }
            $result = Db::name('stock_delivery_order')->insertall($data,true);
        return $result;
    }


}