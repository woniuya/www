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
use think\Log;
class Position extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__STOCK_POSITION__';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    /*
     * 存储持仓记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function add_position_broker($data,$lid,$user,$soure){
        $row=array();
        foreach ($data as $k => $value) {
            $row[$k]['lid'] = $lid;
            $row[$k]['soruce'] = $soure;
            $row[$k]['login_name'] = $user;
            $row[$k]['gupiao_code'] = $value[0];
            $row[$k]['gupiao_name'] = $value[1];
            $row[$k]['count'] = $value[2];
            $row[$k]['stock_count'] = $value[3];
            $row[$k]['canbuy_count'] = $value[4];
            $row[$k]['ck_price'] = $value[5];
            $row[$k]['buy_average_price'] = $value[6];
            $row[$k]['ck_profit_price'] = $value[7];
            $row[$k]['now_price'] = $value[8];
            $row[$k]['market_value'] = $value[9];
            $row[$k]['ck_profit'] = $value[10];
            $row[$k]['profit_rate'] = $value[11];
            $row[$k]['buying'] = $value[12];
            $row[$k]['selling'] = $value[13];
            $row[$k]['gudong_code'] = $value[14];
            $row[$k]['type'] = $value[15];
            $row[$k]['jigou_type'] = $value[15];
            $row[$k]['jiyisuo'] = $value[16]==0? "深交所":"上交所";
            $row[$k]['info'] = $value[17];;
        }
        $result = Db::name('stock_position_broker')->insertall($row,true);
        return $result;
    }
    /*
     * 返回子账号持仓
     * $sub_id 子账号
     */
    public function get_position($sub_id=""){
        if(!empty($sub_id)){
            $res=Db::name('stock_position')
                ->where(['sub_id'=>$sub_id])
                ->where(['buying'=>0])
                ->select();
        }else{
            $res=Db::name('stock_position')
                ->where(['buying'=>0])
                ->select();
        }
        if(count($res)===1){
            foreach ($res as $k =>$v){
                $info=z_market($v["gupiao_code"]);
                $res[$k]['now_price']=$info["current_price"];
                $res[$k]['code']=$res[$k]["gupiao_code"];
                $res[$k]['name']=$res[$k]["gupiao_name"];
                $res[$k]['market_value'] = $info["current_price"]*$v['stock_count'];//最新市值
                $res[$k]['ck_profit'] = round(($info["current_price"]-$v['buy_average_price'])*$v['stock_count'],2);//参考浮动盈亏
                $res[$k]['profit_rate'] = round($res[$k]['ck_profit']/($v['buy_average_price']*$v['stock_count'])*100,2);//盈亏比例
            }
        }elseif(count($res)>=2&&count($res)<80){
            $res=self::position_cha($res);
        }else{
            $arrnum=0;
            $ret=[];
            foreach ($res as $k =>$v){
                if($k%80==0&&$k!=0){
                    $arrnum+=1;
                }
                $ret[$arrnum][$k]=$v;
            }
            foreach ($ret as $k =>$v) {
                $ret[$k]=self::position_cha($ret[$k]);
            }
            foreach ($ret as $k =>$v){
                for($i=0;$i<=79;$i++){
                    $num=$k*80+$i;
                    if(empty($v[$num])){break;}
                    $res[$num]=$v[$num];
                }
            }
        }
        return $res;
    }
    public function position_cha($res){
        $code="";
        foreach ($res as $k =>$v){
            $code=$code.$v["gupiao_code"].',';
        }
        $code=substr($code,0,-1);
        $info=z_market_bat($code);
        foreach ($res as $k =>$v){
            foreach ($info as $kk =>$vv){
                if($res[$k]["gupiao_code"]===$vv["code"]){
                    $res[$k]['now_price']=$vv["current_price"];
                    $res[$k]['code']=$res[$k]["gupiao_code"];
                    $res[$k]['name']=$res[$k]["gupiao_name"];
                    $res[$k]['market_value'] = $vv["current_price"]*$v['stock_count'];//最新市值
                    $res[$k]['ck_profit'] = round(($vv["current_price"]-$v['buy_average_price'])*$v['stock_count'],2);//参考浮动盈亏
                    $res[$k]['profit_rate'] = round($res[$k]['ck_profit']/($v['buy_average_price']*$v['stock_count'])*100,2);//盈亏比例
                }
            }
        }
        return $res;
    }
    /*
     * 返回子账号持仓（带行情数据）
     * $sub_id 子账号
     */
    public function get_position_b($sub_id=""){
        if(!empty($sub_id)){
            $res=Db::name('stock_position')
                ->where(['sub_id'=>$sub_id])
                ->where(['buying'=>0])
                ->select();
        }else{
            $res=Db::name('stock_position')
                ->where(['buying'=>0])
                ->select();
        }
        if(count($res)===1){
            foreach ($res as $k =>$v){
                $info=z_market($v["gupiao_code"]);
                $res[$k]['now_price']=$info["current_price"];
                $res[$k]['market_value'] = $info["current_price"]*$v['canbuy_count'];//最新市值
                $res[$k]['ck_profit'] = round(($info["current_price"]-$v['buy_average_price'])*$v['canbuy_count'],2);//参考浮动盈亏
                $res[$k]['profit_rate'] = round($res[$k]['ck_profit']/($v['buy_average_price']*$v['canbuy_count'])*100,2);//盈亏比例
                $res_d1=Db::name('stock_delivery_order')
                    ->where(['sub_id'=>$sub_id,'gupiao_code'=>$v["gupiao_code"]])
                    ->where('deal_date','>',mktime(0,0))
                    ->where(['status'=>1])
                    ->where(['business_name'=>'证券买入'])
                    ->sum('volume');
                $res[$k]['canbuy_count']=$res[$k]['canbuy_count']-$res_d1;
                $res[$k]=array_merge($res[$k], $info);
            }
        }elseif(count($res)>=2&&count($res)<80){
            $code="";
            foreach ($res as $k =>$v){
                $code=$code.$v["gupiao_code"].',';
            }
            $code=substr($code,0,-1);
            $info=z_market_bat($code);
            foreach ($res as $k =>$v){
                foreach ($info as $kk =>$vv){
                    if($res[$k]["gupiao_code"]===$vv["code"]){
                        $res[$k]['now_price']=$vv["current_price"];
                        $res[$k]['market_value'] = $vv["current_price"]*$v['canbuy_count'];//最新市值
                        $res[$k]['ck_profit'] = round(($vv["current_price"]-$v['buy_average_price'])*$v['canbuy_count'],2);//参考浮动盈亏
                        $res[$k]['profit_rate'] = round($res[$k]['ck_profit']/($v['buy_average_price']*$v['canbuy_count'])*100,2);//盈亏比例
                        $res_d1=Db::name('stock_delivery_order')
                            ->where(['sub_id'=>$sub_id,'gupiao_code'=>$vv["code"]])
                            ->where('deal_date','>',mktime(0,0))
                            ->where(['status'=>1])
                            ->where(['business_name'=>'证券买入'])
                            ->sum('volume');
                        $res[$k]['canbuy_count']=$res[$k]['canbuy_count']-$res_d1;
                        $res[$k]=array_merge($res[$k], $vv);
                    }
                }
            }
        }
        return $res;
    }
    /*
     * 返回子账号和股票代码对应的持仓
     * $sub_id 子账号
     * $code 股票代码
     */
    public function get_code_position($sub_id,$code){
        $res=Db::name('stock_position')
            ->where(['sub_id'=>$sub_id,'gupiao_code'=>$code])
            ->where(['buying'=>0])
            ->find();
        if(empty($res)){return false;}
        $info=z_market($code);
        $res['now_price']=$info["current_price"];
        $res['market_value'] = $info["current_price"]*$res['canbuy_count'];//最新市值
        $res['ck_profit'] = round(($info["current_price"]-$res['buy_average_price'])*$res['canbuy_count'],2);//参考浮动盈亏
        $res['profit_rate'] = round($res['ck_profit']/($res['buy_average_price']*$res['canbuy_count'])*100,2);//盈亏比例
        return $res;
    }
    /*
     * 返回子账号和股票代码对应持仓的可卖数量
     * $sub_id 子账号
     * $code 股票代码
     */
    public function get_canbuy_count($sub_id,$code){
        $res=Db::name('stock_position')
            ->field('canbuy_count')
            ->where(['sub_id'=>$sub_id,'gupiao_code'=>$code])
            ->where(['buying'=>0])
            ->find();
        if(empty($res)){return 0;}
        $res_d1=Db::name('stock_delivery_order')
            ->where(['sub_id'=>$sub_id,'gupiao_code'=>$code])
            ->where('deal_date','>',mktime(0,0))
            ->where(['status'=>1])
            ->where(['business_name'=>'证券买入'])
            ->sum('volume');
        $res_d2=Db::name('stock_delivery_order')
            ->where(['sub_id'=>$sub_id,'gupiao_code'=>$code])
            ->where('deal_date','>',mktime(0,0))
            ->where(['status'=>0])
            ->where(['business_name'=>'证券卖出'])
            ->sum('volume');
        $res['canbuy_count']=$res['canbuy_count']-$res_d1-$res_d2;
        return $res['canbuy_count'];
    }
    /*
     * 添加卖出模拟持仓记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function sell_m_position($stockinfo,$count,$price,$sub_id,$model=2,$Trust_no,$commission,$Transfer,$stamp){
        $position_res = $this->get_code_position($sub_id, $stockinfo["code"]);
        if(empty($position_res)){return false;}
        $data=$position_res;
        $canbuy_count=$position_res['canbuy_count']-$count;
        $stock_count=$position_res['stock_count']-$count;
        $fee=$commission+$Transfer+$stamp;
        if($canbuy_count>0){
            if($model==1){
                $data['canbuy_count'] = $canbuy_count;
            }
            if($model==2){
                $new_price=round(($position_res['canbuy_count']*$position_res['buy_average_price']-$count*$price-$fee)/$canbuy_count,3);
                $data['count'] =$stock_count;
                $data['stock_count'] =$stock_count;
                $data['canbuy_count'] = $canbuy_count;
                $data['ck_price'] = $new_price;//参考成本价
                $data['buy_average_price'] = $new_price;//买入均价
                $data['ck_profit_price'] = $new_price;//参考盈亏成本价
                $data['now_price'] = $stockinfo["current_price"];//'当前价'
                $data['market_value'] = $stockinfo["current_price"]*$canbuy_count;//最新市值
                $data['ck_profit'] = round(($stockinfo["current_price"]-$new_price)*$canbuy_count,2);//参考浮动盈亏
                $data['profit_rate'] = round($data['ck_profit']/($new_price*$canbuy_count)*100,2);//盈亏比例
            }
            $result = Db::name('stock_position')
                ->where(['sub_id'=>$sub_id,'gupiao_code'=>$stockinfo["code"]])
                ->where(['buying'=>0])
                ->update($data);
            Log::write(date('y-m-d H:i:s',time())."::子账户".$sub_id."委托卖出".$count."股".$stockinfo["code"]."股票");
        }elseif($canbuy_count==0){
            if($model==2){
                $info=Db::name('stock_temp')
                    ->where(['sub_id'=>$sub_id])
                    ->where(['gupiao_code'=>$stockinfo["code"]])
                    ->where(['deal_no'=>null])
                    ->find();
                if(empty($info)) {
                    $result = Db::name('stock_position')
                        ->where(['sub_id' => $sub_id, 'gupiao_code' => $stockinfo["code"]])
                        ->where(['buying' => 0])
                        ->delete();
                }else{
                    $new_price=round(($position_res['stock_count']*$position_res['buy_average_price']-$count*$price-$fee)/$stock_count,3);
                    $data['count'] =$stock_count;
                    $data['stock_count'] =$stock_count;
                    $data['canbuy_count'] = 0;
                    $data['ck_price'] = $new_price;//参考成本价
                    $data['buy_average_price'] = $new_price;//买入均价
                    $data['ck_profit_price'] = $new_price;//参考盈亏成本价
                    $data['now_price'] = $stockinfo["current_price"];//'当前价'
                    $data['market_value'] = $stockinfo["current_price"]*$canbuy_count;//最新市值
                    $data['ck_profit'] = round(($stockinfo["current_price"]-$new_price)*$canbuy_count,2);//参考浮动盈亏
                    $data['profit_rate'] = round($data['ck_profit']/($new_price*$canbuy_count)*100,2);//盈亏比例
                    $result = Db::name('stock_position')
                        ->where(['sub_id'=>$sub_id,'gupiao_code'=>$stockinfo["code"]])
                        ->where(['buying'=>0])
                        ->update($data);
                }
            }elseif($model==1){
                $data['canbuy_count']=0;
                $result = Db::name('stock_position')
                    ->where(['sub_id'=>$sub_id,'gupiao_code'=>$stockinfo["code"]])
                    ->where(['buying'=>0])
                    ->update($data);
            }else{
                $result = false;
            }

        }else{
            $result = false;
        }
        return $result;
    }
    /*
     * 存储模拟持仓记录
     * $data 持仓数据
     * $sub_id 子账号
     * $lid 安全模式id号
     * $user 证券账户
     * $soure 证券来源
     */
    public function add_m_position($stockinfo,$count,$sub_id,$lid,$user,$soure,$ck_price,$model,$Trust_no){

        $position_res = $this->get_code_position($sub_id, $stockinfo["code"]);
        if(empty($position_res)){
            if($model==2){
                $data=array();
                $data[0]['sub_id'] = $sub_id;
                $data[0]['lid'] = $lid;
                $data[0]['soruce'] = $soure;
                $data[0]['login_name'] = $user;
                $data[0]['gupiao_code'] = $stockinfo["code"];
                $data[0]['gupiao_name'] = $stockinfo["name"];
                $data[0]['count'] = $count;
                $data[0]['stock_count'] =$count;
                $data[0]['canbuy_count'] = $count;
                $data[0]['ck_price'] = $ck_price;//参考成本价
                $data[0]['buy_average_price'] = $ck_price;//买入均价
                $data[0]['ck_profit_price'] = $ck_price;//参考盈亏成本价
                $data[0]['now_price'] = $stockinfo["current_price"];//'当前价'
                $data[0]['market_value'] = $stockinfo["current_price"]*$count;//最新市值
                $data[0]['ck_profit'] = round(($stockinfo["current_price"]-$ck_price)*$count,3);//参考浮动盈亏
                $data[0]['profit_rate'] = round($data[0]['ck_profit']/($ck_price*$count)*100,2);//盈亏比例
                $data[0]['trust_no'] = $Trust_no;//盈亏比例
                $data[0]['buying'] = 0;//买入成功
                $data[0]['selling'] = 0;//1、在途卖出
                $data[0]['gudong_code'] = "";//股东代码 无法模拟暂时空
                $data[0]['type'] = $stockinfo["exchange_code"];//帐号类别
                $data[0]['jigou_type'] = 1;
                $data[0]['jiyisuo'] = $stockinfo["exchange_code"]==0? "深交所":"上交所";//交易所
                $data[0]['info'] = "";
                $result = Db::name('stock_position')->insert($data[0],true);
            }elseif($model==1){
                $result=true;
            }else{
                $result=false;
            }
        }else{
            $canbuy_count=$position_res['canbuy_count']+$count;
            $new_price=round(($position_res['canbuy_count']*$position_res['buy_average_price']+$count*$ck_price)/$canbuy_count,3);

            if($model==1){
                return true;
            }
            if($model==2){
                $data=array();
                $data[0]['sub_id'] = $sub_id;
                $data[0]['lid'] = $lid;
                $data[0]['soruce'] = $soure;
                $data[0]['login_name'] = $user;
                $data[0]['gupiao_code'] = $stockinfo["code"];
                $data[0]['gupiao_name'] = $stockinfo["name"];
                $data[0]['count'] = $position_res['count']+$count;
                $data[0]['stock_count'] =$position_res['stock_count']+$count;
                $data[0]['canbuy_count'] = $position_res['canbuy_count']+$count;
                $data[0]['ck_price'] = $new_price;//参考成本价
                $data[0]['buy_average_price'] = $new_price;//买入均价
                $data[0]['ck_profit_price'] = $new_price;//参考盈亏成本价
                $data[0]['now_price'] = $stockinfo["current_price"];//'当前价'
                $data[0]['market_value'] = $stockinfo["current_price"]*$canbuy_count;//最新市值
                $data[0]['ck_profit'] = round(($stockinfo["current_price"]-$new_price)*$canbuy_count,3);//参考浮动盈亏
                $data[0]['profit_rate'] = round($data[0]['ck_profit']/($new_price*$canbuy_count)*100,2);//盈亏比例
                $data[0]['buying'] = 0;//成功
                $data[0]['selling'] = 0;//在途卖出
                $data[0]['gudong_code'] = "";//股东代码 无法模拟暂时空
                $data[0]['type'] = $stockinfo["exchange_code"];//帐号类别
                $data[0]['jigou_type'] = 1;
                $data[0]['jiyisuo'] = $stockinfo["exchange_code"]==0? "深交所":"上交所";//交易所
                $data[0]['info'] = "";
            }
            $result = Db::name('stock_position')->where(['id'=>$position_res['id']])->update($data[0]);

        }
        return $result;
    }


}