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
class Grid extends Model{
    const URL="http://127.0.0.1:";
//网格持仓
    public static function grid_position($code="",$trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/positions".$code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function idtoport($trade_id){
        $grid_port=config('grid_port');
        if($trade_id>count($grid_port)){
            return false;
        }else{
            return $grid_port[$trade_id];
        }
    }
    //资金
    public static function grid_funds($trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/funds");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
//返回股份
    public static function grid_category_stock($trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/query?category=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output=str_replace("\u0009",",",$output);
        $output=str_replace("\u000a",",",$output);
        $output=substr($output,1,-1);
        $res=explode(',',$output);
        $n=0;
        $ret=[];
        foreach ($res as $k=>$v){
            if($k%18===0&&$k!=0){
                $n++;
                $ret[$n][0]=$v;
            }else{
                $i=$k%18;
                $ret[$n][$i]=$v;
            }

        }
        return $ret;
    }
//返回当日委托
    public static  function grid_category_trust($trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/query?category=2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output=str_replace("\u0009",",",$output);
        $output=str_replace("\u000a",",",$output);
        $output=substr($output,1,-1);
        $res=explode(',',$output);
        $n=0;
        $ret=[];
        foreach ($res as $k=>$v){
            if($k%19===0&&$k!=0){
                $n++;
                $ret[$n][0]=$v;
            }else{
                $i=$k%19;
                $ret[$n][$i]=$v;
            }

        }
        return $ret;
    }
//当日成交
    public static  function grid_category_deal($trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/query?category=3");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output=str_replace("\u0009",",",$output);
        $output=str_replace("\u000a",",",$output);
        $output=substr($output,1,-1);
        $res=explode(',',$output);
        $n=0;
        $ret=[];
        foreach ($res as $k=>$v){
            if($k%18===0&&$k!=0){
                $n++;
                $ret[$n][0]=$v;
            }else{
                $i=$k%18;
                $ret[$n][$i]=$v;
            }
        }
        return $ret;
    }
//当日可撤
    public static  function grid_category_cancel($trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/query?category=4");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output=str_replace("\u0009",",",$output);
        $output=str_replace("\u000a",",",$output);
        $output=substr($output,1,-1);
        $res=explode(',',$output);
        $n=0;
        $ret=[];
        foreach ($res as $k=>$v){
            if($k%16===0&&$k!=0){
                $n++;
                $ret[$n][0]=$v;
            }else{
                $i=$k%16;
                $ret[$n][$i]=$v;
            }
        }
        return $ret;
    }
//股东代码
    public static  function grid_category_stockholder($trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/query?category=5");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $output=str_replace("\u0009",",",$output);
        $output=str_replace("\u000a",",",$output);
        $output=substr($output,1,-1);
        $res=explode(',',$output);
        $n=0;
        $ret=[];
        foreach ($res as $k=>$v){
            if($k%4===0&&$k!=0){
                $n++;
                $ret[$n][0]=$v;
            }else{
                $i=$k%4;
                $ret[$n][$i]=$v;
            }
        }
        return $ret;
    }
    /*
     * $category表示委托种类:0->买入 1->卖出 2->融资买入 3->融券卖出 4->买券还券 5->卖券还款 6->现券还券 7->担保品买入 8->担保品卖出
     * $priceType表示报价方式:0->上海限价委托 深圳限价委托1->市价委托（深圳对方最优价格）2->市价委托（深圳本方最优价格）3->市价委托（深圳即时成交剩余撤销）
     * 4->市价委托（上海五档即成剩撤 深圳五档即成剩撤）5->市价委托（深圳全额成交或撤销）6->市价委托（上海五档即成转限价）
     * $stkCode表示证券代码，一般是六位数字，如601988
     * $price表示委托价格，支持二至四位小数的精度
     * $quantity表示委托数量，一般情况下是整数100的倍数。
     */
    public static  function grid_order($category,$priceType,$stkCode,$price,$quantity,$trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $ch = curl_init();
        $data = array(
            "category" => $category,
            "type"     => $priceType,
            "symbol"   => $stkCode,
            "price"    => $price,
            "quantity" => $quantity,
        );
        curl_setopt($ch, CURLOPT_URL, self::URL.$port."/api/order?".http_build_query($data));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, []);
        $output = curl_exec($ch);
        curl_close($ch);
        $output=substr($output,1,-1);
        return $output;
    }
    /*
     * 撤单
     * 参数1. {exchangeId}表示交易所类别: 沪市->1，深市->0 (招商证券普通账户深市->2)
     * 参数2. {wtOrder}表示下单时返回的委托编号
     *
     */
    public static  function grid_cancel($exchangeid,$wtorder,$trade_id=1){
        $port=Grid::idtoport($trade_id);
        if(!$port){
            return ['status'=>0, 'msg'=>'没找到对应的端口号'];
        }
        $curl = curl_init();
        //设置抓取的url
        $data = array(
            "exchangeid" => $exchangeid,
            "wtorder"     => $wtorder,
        );
        curl_setopt($curl, CURLOPT_URL, self::URL.$port."/api/cancel?".http_build_query($data));
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, []);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        $data=substr($data,1,-1);
        return $data;
    }
    /*
     * 判断是否为A股股票
     */
    public static function market_type($code){
        switch (substr($code,0,1)){
            case '0':return 1;break;
            case '3':return 1;break;
            case '6':return 2;break;
            default: return 5;break;
        }
    }



}