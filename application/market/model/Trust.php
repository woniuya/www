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
class Trust extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_TRUST__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    /*
     * 存储委托记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function add_trust_broker($data,$lid,$user,$soure){
        $row=array();
        foreach ($data as $k => $value) {
            $row[$k]['lid'] = $lid;
            $row[$k]['soruce'] = $soure;
            $row[$k]['login_name'] = $user;
            $row[$k]['add_time'] = strtotime($value[0]);
            $row[$k]['trust_time'] = $value[1];
            $row[$k]['gudong_code'] = $value[2];
            $row[$k]['type'] = $value[3];
            $row[$k]['gupiao_code'] = $value[4];
            $row[$k]['gupiao_name'] = $value[5];
            $row[$k]['flag1'] = $value[6];
            $row[$k]['flag2'] = $value[7];
            $row[$k]['trust_price'] = $value[8];
            $row[$k]['trust_count'] = $value[9];
            $row[$k]['trust_no'] = $value[10];
            $row[$k]['volume'] = $value[11];
            $row[$k]['amount'] = $value[12];
            $row[$k]['cancel_order_count'] = $value[13];
            $row[$k]['status'] = $value[14];
            $row[$k]['cancel_order_flag'] = $value[15];
            $row[$k]['trust_date'] = strtotime($value[16]);
            if(isset($value[17])){
                $row[$k]['beizhu'] = $value[17];
            }else{$row[$k]['beizhu']="";}
            if(isset($value[18])){
                $row[$k]['info'] = $value[18];
            }else{$row[$k]['info'] ="";}
            $row[$k]['type_today'] = 1;
        }
        $result = Db::name('stock_trust_broker')->insertall($row,true);
        return $result;
    }
    /*
     * 存储模拟卖出委托记录
     */
    public function sell_m_trust($stockinfo,$count,$price,$sub_id,$lid,$user,$soure,$Trust_no,$broker,$model){
        $row=array();
        $row[0]['sub_id'] = $sub_id;
        $row[0]['lid'] = $lid;
        $row[0]['soruce'] = $soure;
        $row[0]['login_name'] = $user;
        $row[0]['add_time'] = strtotime(date('Y-m-d',time()));//委托日期
        $row[0]['trust_time'] = date('H:i:s',time());//委托时间
        $row[0]['gudong_code'] = "";//股东代码
        $row[0]['type'] = $stockinfo["exchange_code"];//帐号类别
        $row[0]['gupiao_code'] = $stockinfo['code'];//证券代码
        $row[0]['gupiao_name'] = $stockinfo['name'];//证券名称
        $row[0]['flag1'] = 1;//买卖标志1
        $row[0]['flag2'] = "卖出委托";//买卖标志2
        $row[0]['trust_price'] = $price;
        $row[0]['trust_count'] = $count;
        $row[0]['trust_no'] = $Trust_no;//委托编号
        $row[0]['cancel_order_count'] = 0;
        if($model==1){
            $row[0]['volume'] = 0;//成交数量
            $row[0]['amount'] = 0;//成交金额
            $row[0]['status'] = "已委托";//状态说明
            $row[0]['cancel_order_flag'] = 0;//撤单标志
        }else{
            $row[0]['volume'] = $count;//成交数量
            $row[0]['amount'] = $price*$count;//成交金额
            $row[0]['status'] = "已成";//状态说明
            $row[0]['cancel_order_flag'] = 1;//撤单标志
        }
        $row[0]['trust_date'] = strtotime(date('Y-m-d',time()));//委托日期
        $row[0]['beizhu'] = "";
        $row[0]['info'] = "";
        $row[0]['type_today'] = 1;
        if($broker['broker']!=-1||$model==1){
            $data=$row[0];
            $data['broker_id'] = $broker['id'];
            $data['add_time'] = time();
            $lll=$count%100;
            if($lll>0&&$broker['broker']!=-1){
                $data['volume']=$count-$lll;
            }else{
                $data['volume'] = $count;//成交数量
            }
            $data['amount'] = $price*$data['volume'];//成交金额
            $temp = Db::name('stock_temp')->insert($data,true);
        }elseif($model==2&&$broker['broker']!=-1){
            //市价委托零散股票处理开始
            $lll=$count%100;
            $ptmd=Db::name('stock_position')->where(['sub_id'=>$sub_id,'gupiao_code'=>$stockinfo['code']])->find();
            if($lll>0&&!empty($ptmd)){
                $bonus=$ptmd;
                unset($bonus['sub_id']);
                $count_tmd=$lll%100;
                $bonus['count']=$count_tmd;
                $bonus['stock_count']=$count_tmd;
                $bonus['canbuy_count']=$count_tmd;
                $bonus['ck_price']=$price;
                $bonus['buy_average_price']=$price;
                $bonus['ck_profit_price']=$price;
                $bonus['now_price']=$price;
                $bonus['market_value']=$price*$bonus['stock_count'];
                $sp_info=Db::name('stock_bonus_surplus')->where(['gupiao_code'=>$stockinfo['code']])->find();
                if(empty($sp_info)){
                    $bonus_res=Db::name('stock_bonus_surplus')->insert($bonus);
                }else{
                    $bonus['count']=$bonus['count']+$sp_info['count'];
                    $bonus['stock_count']=$bonus['stock_count']+$sp_info['stock_count'];
                    $bonus['canbuy_count']=$bonus['canbuy_count']+$sp_info['canbuy_count'];
                    $bonus['ck_price']=($price*$count_tmd+$sp_info['ck_price']*$sp_info['stock_count'])/$bonus['stock_count'];
                    $bonus['buy_average_price']=$bonus['ck_price'];
                    $bonus['ck_profit_price']=$bonus['ck_price'];
                    $bonus['now_price']=$price;
                    $bonus['market_value']=$price*$bonus['stock_count'];
                    $bonus_res=Db::name('stock_bonus_surplus')->where(['gupiao_code'=>$stockinfo['code']])->update($bonus);
                }
            }
            //零散股票处理结束
        }
        $result = Db::name('stock_trust')->insert($row[0],true);
        return $result;
    }
    /*
     * 存储模拟买入委托记录
     */
    public function add_m_trust($stockinfo,$count,$price,$sub_id,$lid,$user,$soure,$Trust_no,$broker,$model){
        $row=array();
        $row[0]['sub_id'] = $sub_id;
        $row[0]['lid'] = $lid;
        $row[0]['soruce'] = $soure;
        $row[0]['login_name'] = $user;
        $row[0]['add_time'] = strtotime(date('Y-m-d',time()));//委托日期
        $row[0]['trust_time'] = date('H:i:s',time());//委托时间
        $row[0]['gudong_code'] = "";//股东代码
        $row[0]['type'] = $stockinfo["exchange_code"];//帐号类别
        $row[0]['gupiao_code'] = $stockinfo['code'];//证券代码
        $row[0]['gupiao_name'] = $stockinfo['name'];//证券名称
        $row[0]['flag1'] = 0;//买卖标志1
        $row[0]['flag2'] = "买入委托";//买卖标志2
        $row[0]['trust_price'] = $price;
        $row[0]['trust_count'] = $count;
        $row[0]['trust_no'] = $Trust_no;//委托编号
        $row[0]['volume'] = 0;//成交数量
        $row[0]['amount'] = 0;//成交金额
        $row[0]['cancel_order_count'] = 0;
        if($model==1){
            $row[0]['cancel_order_flag'] = 0;//撤单标志 0、可撤
            $row[0]['status'] = "已委托";//状态说明
        }else{
            $row[0]['cancel_order_flag'] = 1;//撤单标志 1、已成
            $row[0]['volume'] = $count;//成交数量
            $row[0]['amount'] = $count*$price;//成交金额
            $row[0]['status'] = "已成";//状态说明
        }
        $row[0]['trust_date'] = strtotime(date('Y-m-d',time()));//委托日期
        $row[0]['beizhu'] = "";
        $row[0]['info'] = "";
        $row[0]['type_today'] = 1;
        if($model==1){
            $row[0]['broker_id'] = $broker['id'];
            $row[0]['add_time'] = time();
            $data=$row[0];
            $data['volume'] = $count;//成交数量
            $data['amount'] = $price*$count;//成交金额
            $temp = Db::name('stock_temp')->insert($data,true);
        }
        $result = Db::name('stock_trust')->insert($row[0],true);
        return $result;
    }
    /*
     * 添加可撤单委托
     *
     */
    public function add_cancel_trust($data,$sub_id,$lid,$user,$soure){
        $row=array();
        foreach ($data as $k => $value) {
            $row[$k]['sub_id'] = $sub_id;
            $row[$k]['lid'] = $lid;
            $row[$k]['soruce'] = $soure;
            $row[$k]['login_name'] = $user;
            $row[$k]['add_time'] = strtotime($value[0]);
            $row[$k]['trust_time'] = $value[1];
            $row[$k]['gudong_code'] = $value[2];
            $row[$k]['type'] = $value[3];
            $row[$k]['gupiao_code'] = $value[4];
            $row[$k]['gupiao_name'] = $value[5];
            $row[$k]['flag1'] = $value[7];
            $row[$k]['flag2'] = $value[8];
            $row[$k]['trust_price'] = $value[9];
            $row[$k]['trust_count'] = $value[10];
            $row[$k]['trust_no'] = $value[11];
            $row[$k]['volume'] = $value[12];
            $row[$k]['cancel_order_count'] = $value[13];
            $row[$k]['status'] = $value[6];
            $row[$k]['trust_date'] = strtotime($value[14]);
            $row[$k]['info'] = $value[15];
            $row[$k]['type_today_back'] = 1;
        }
        $result = Db::name('stock_trust')->insertAll($row,true);
        return $result;
    }
    /*
     * 返回子账号当日委托
     * $sub_id 子账号
     */
        public function get_trust_day($sub_id){
            $time=strtotime(date('y-m-d',time()));
            $res=Db::name('stock_trust')->where(['sub_id'=>$sub_id,'trust_date'=>$time])->select();
            return $res;
        }
    /*
     * 返回子账号委托
     * $sub_id 子账号
     */
    public function get_trust($sub_id,$beginday,$endday){
        if(empty($endday)){$endday=time();}else{$endday=strtotime($endday);}
        if(empty($beginday)){$beginday=strtotime(date("Y-m-d",time()));}else{$beginday=strtotime($beginday);}
        $res=Db::name('stock_trust')
            ->where(['sub_id'=>$sub_id])
            ->where('trust_date','>=',$beginday)
            ->where('trust_date','<=',$endday)
            ->select();
        return $res;
    }
    /*
     * 返回子账号当日当日可撤委托
     * $sub_id 子账号
     */
    public function get_cancel_trust($sub_id){
        $time=mktime(0,0,0);
        $res=Db::name('stock_trust')->where(['sub_id'=>$sub_id,'trust_date'=>$time,'status'=>'已委托'])->select();
        return $res;
    }
    /*
     * 存储历史委托记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soruce 证券来源
     */
    public function add_history_trust($data,$lid,$user,$soruce){
        $row=array();
        foreach ($data as $k => $value) {
            $row[$k]['lid'] = $lid;
            $row[$k]['soruce'] = $soruce;
            $row[$k]['login_name'] = $user;
            $row[$k]['add_time'] = strtotime($value[0]);
            $row[$k]['trust_time'] = $value[1];
            $row[$k]['gudong_code'] = $value[2];
            $row[$k]['type'] = $value[3];
            $row[$k]['gupiao_code'] = $value[4];
            $row[$k]['gupiao_name'] = $value[5];
            $row[$k]['flag1'] = $value[6];
            $row[$k]['flag2'] = $value[7];
            $row[$k]['trust_price'] = $value[8];
            $row[$k]['trust_count'] = $value[9];
            $row[$k]['trust_no'] = $value[10];
            $row[$k]['volume'] = $value[11];
            $row[$k]['amount'] = $value[12];
            $row[$k]['cancel_order_count'] = $value[13];
            $row[$k]['status'] = $value[14];
            $row[$k]['cancel_order_flag'] = "";
            $row[$k]['trust_date'] = $value[15];
            $row[$k]['beizhu']="";
            $row[$k]['info'] ="";
            $row[$k]['type_today'] = 1;
        }
        $result = Db::name('stock_trust_broker')->insertall($row,true);
        return $result;
    }

}