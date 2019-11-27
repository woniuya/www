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
class Deal_stock extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_DEAL_STOCK__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    /*
     * 存储成交记录
     * $data 成交数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function add_deal_stock_broker($data,$lid,$user,$soure){
        $row=array();
        foreach ($data as $k => $value) {
            $row[$k]['lid'] = $lid;
            $row[$k]['soruce'] = $soure;
            $row[$k]['login_name'] = $user;
            $row[$k]['deal_date'] = strtotime($value[0]);
            $row[$k]['deal_time'] = $value[1];
            $row[$k]['gupiao_code'] = $value[2];
            $row[$k]['gupiao_name'] = $value[3];
            $row[$k]['flag1'] = $value[4];
            $row[$k]['flag2'] = $value[5];
            $row[$k]['trust_price'] = $value[6];
            $row[$k]['trust_count'] = $value[7];
            $row[$k]['trust_no'] = $value[8];
            $row[$k]['deal_price'] = $value[9];
            $row[$k]['volume'] = $value[10];
            $row[$k]['amount'] = $value[11];
            $row[$k]['deal_no'] = $value[12];
            $row[$k]['gudong_code'] = $value[13];
            $row[$k]['type'] = $value[14];
            if(isset($value[15])){
                $row[$k]['status'] = $value[15];
            }else{
                $row[$k]['status'] = "普通成交";
            }
            if(isset($value[16])){
                $row[$k]['cancel_order_flag'] = $value[16];
            }else{
                $row[$k]['cancel_order_flag'] = "";
            }
            if(isset($value[17])){
                $row[$k]['info'] = $value[17];
            }else{
                $row[$k]['info'] = "";
            }
            $row[$k]['type_today'] = 1;//当日成交标识
        }
        $result = Db::name('stock_deal_stock_broker')->insertall($row,true);
        return $result;
    }

    /*
     * 返回子账号成交
     * $sub_id 子账号
     */
    public function get_deal_stock($sub_id,$beginday,$endday){

        if(empty($endday)){$endday=time();}else{$endday=strtotime($endday);}
        if(empty($beginday)){$beginday=strtotime(date("Y-m-d",time()));}else{$beginday=strtotime($beginday);}

        $res=Db::name('stock_deal_stock')
            ->where(['sub_id'=>$sub_id])
            ->where('status','<>',0)
            ->where('deal_date','>=',$beginday)
            ->where('deal_date','<=',$endday)
            ->order('id desc')
            ->select();

        if(!$res||count($res)===0){
            return $res;
        }
        $code="";
        foreach ($res as $k =>$v){
            $code=$code.$v["gupiao_code"].',';
        }
        $code=substr($code,0,-1);
        $info=z_market_bat($code);
        foreach($res as $k=>$item) {
            foreach ($info as $kk =>$vv) {
                if ($res[$k]["gupiao_code"] === $vv["code"]) {
                    $res[$k] = array_merge($res[$k], $vv);
                    break;
                }
            }
        }
        return $res;
    }
    /*
     * 返回交易账号当日成交
     * $sub_id 子账号
     */
    public function get_deal_stock_broker($lid){
        $time=strtotime(date('y-m-d',time()));
        $res=Db::name('stock_deal_stock_broker')->where(['lid'=>$lid,'deal_date'=>$time])->select();
        return $res;
    }
    /*
     * 存储模拟卖出成交记录
     * */
    public function sell_m_deal_stock($stockinfo,$count,$price,$sub_id,$lid,$user,$soure,$Trust_no,$model){
        $row=array();
        $row[0]['sub_id'] = $sub_id;
        $row[0]['lid'] = $lid;//交易账户
        $row[0]['soruce'] = $soure;//证券来源
        $row[0]['login_name'] = $user;//证券账户
        $row[0]['deal_date'] = strtotime(date('Y-m-d',time()));//成交日期
        $row[0]['deal_time'] = date('H:i:s',time());//成交时间
        $row[0]['gupiao_code'] = $stockinfo['code'];//证券代码
        $row[0]['gupiao_name'] = $stockinfo['name'];//证券名称
        $row[0]['flag1'] = 1;//买卖标志1
        $row[0]['flag2'] = "证券卖出";//买卖标志2
        $row[0]['trust_price'] = $price;//委托价格
        $row[0]['trust_count'] = $count;//委托数量
        $row[0]['trust_no'] = $Trust_no;//委托编号
        $row[0]['deal_price'] = $price;//成交价格
        $row[0]['volume'] = $count;//成交数量
        $row[0]['amount'] = $price*$count;//成交金额
        $row[0]['deal_no'] = $Trust_no+521;//成交编号
        $row[0]['gudong_code'] = "";//股东代码
        $row[0]['type'] = $stockinfo["exchange_code"];//帐号类别
        if($model==1){
            $row[0]['cancel_order_flag'] = '0';//撤单标志 0、可撤
            $row[0]['status'] = "0";//状态说明
        }else{
            $row[0]['cancel_order_flag'] = '1';//撤单标志 1、已成交
            $row[0]['status'] = "卖出成交";//状态说明
        }
        $row[0]['info'] = "";//保留信息
        $row[0]['type_today'] = 1;//当日成交标识
        $result = Db::name('stock_deal_stock')->insertall($row,true);
        return $result;
    }
    /*
     * 存储模拟买入成交记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function add_m_deal_stock($stockinfo,$count,$price,$sub_id,$lid,$user,$soure,$Trust_no,$model){
        $row=array();
        $row[0]['sub_id'] = $sub_id;
        $row[0]['lid'] = $lid;//交易账户
        $row[0]['soruce'] = $soure;//证券来源
        $row[0]['login_name'] = $user;//证券账户
        $row[0]['deal_date'] = strtotime(date('Y-m-d',time()));//成交日期
        $row[0]['deal_time'] = date('H:i:s',time());//成交时间
        $row[0]['gupiao_code'] = $stockinfo['code'];//证券代码
        $row[0]['gupiao_name'] = $stockinfo['name'];//证券名称
        $row[0]['flag1'] = 1;//买卖标志1
        $row[0]['flag2'] = "证券买入";//买卖标志2
        $row[0]['trust_price'] = $price;//委托价格
        $row[0]['trust_count'] = $count;//委托数量
        $row[0]['trust_no'] = $Trust_no;//委托编号
        $row[0]['deal_price'] = $price;//成交价格
        $row[0]['volume'] = $count;//成交数量
        $row[0]['amount'] = $price*$count;//成交金额
        $row[0]['deal_no'] = $Trust_no+521;//成交编号
        $row[0]['gudong_code'] = "";//股东代码
        $row[0]['type'] = $stockinfo["exchange_code"];//帐号类别
        if($model==1){
            $row[0]['status'] = "0";//状态说明
            $row[0]['cancel_order_flag'] = '0';//撤单标志 0、可撤
        }else{
            $row[0]['status'] = "买入成交";//状态说明
            $row[0]['cancel_order_flag'] = '1';//撤单标志 1、已成交
        }
        $row[0]['info'] = "";//保留信息
        $row[0]['type_today'] = 1;//当日成交标识
        $result = Db::name('stock_deal_stock')->insertall($row,true);
        return $result;
    }


}