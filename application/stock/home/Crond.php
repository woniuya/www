<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 孟辉 <1690611599@qq.com>
// +----------------------------------------------------------------------
namespace app\stock\home;
use app\index\controller\Home;
use app\market\model\StockSubAccount;
use app\market\model\SubAccountMoney;
use app\money\model\Record;
use app\market\home\Heart;
use app\stock\model\Renewal;
use app\money\model\Money;
use app\member\model\Member;
use app\member\model\MemberMessage as MemberMessageModel;
use app\market\model\Grid;
use app\market\model\Position;
use app\market\model\StockSubAccountRisk;
use app\stock\model\Borrow;
use think\Log;
use think\Db;
/*
 * 定时任务
 */
class Crond extends Home
{
    public static function config($parm){
        $name=Db::name("admin_config")->where(["name"=>$parm])->value("value");
        if(is_numeric($name)){return $name;}
        $name = strtolower($name);
        $name = str_replace(array("\r\n", "\r", "\n"), ",", $name);
        $name = explode(',', $name);
        $res=[];
        foreach ($name as $k =>$v){
            $tmd=explode(':', $v);
            $res[$k+1]=$tmd[1];
        }
        return $res;
    }
    public static function send_sms($mobile, $template,$info = null){
        // 读取一条短信插件
        $sms_plugin = Db('admin_plugin')->where("name like '%Sms' and status = 1")->find();
        //$develop_mode=config('develop_mode');//判断是否开发模式
        if(!$sms_plugin){ // 如果没有开启短信接口则发送默认验证码 0000
            $arr['code']=md5('000000');
            $arr['time']=time();
            setRegSmsCache($mobile,$arr);
            $res = true;
        }else{
            $str = trim($info);
            $res = plugin_action($sms_plugin['name'], $sms_plugin['name'], 'sendSms', [$mobile, $str]);
            if($res == false) return false;
        }
        return $res;
    }
    public static function sendsms_to_admin($msg)
    {
        $mobile=$avatar = Db::name('admin_user')->where('username','admin')->value('mobile');
        $res=self::send_sms($mobile,"",$msg);
        return $res;
    }
    /*
     * 股权登记
     */
    public static function register(){
        $binfo=Db::name("stock_bonus")->where('addtime',">=",mktime(0,0,0))->where(['type'=>1])->find();
        if(!empty($binfo)){
            return ;
        }
        $info=substr(qq_bonus(),13);
        $info=json_decode($info,true);
        $stock_record=$info['data']['076015']['data'];//登记日
        $res=[];
        $rul='((\\-|\\+)?\\d+(\\.\\d+)?)';
        foreach ($stock_record as $k=>$v) { //echo $k."</br>";
            $res[$k]=Handle($v,$k,$rul)[$k];
            if($res[$k]==false){
                unset($res[$k]);
                continue;
            }
            $res[$k]['date'] = $v['date'];
            $res[$k]['code'] = $v['zqdm'];
            $res[$k]['name'] = $v['zqjc'];
            $res[$k]['info'] = "登记日";
            $res[$k]['type'] = 1;
            $res[$k]['addtime'] = time();
            $p_info=Db::name("stock_position")->where(["gupiao_code"=>$res[$k]['code']])->select();
            if(empty($p_info)){
                continue;
            }else{
                $data=$res[$k];
                foreach ($p_info as $kk=>$vv){
                    $data['lid'] = $vv['lid'];
                    $data['soruce'] = $vv['soruce'];
                    $data['login_name'] = $vv['login_name'];
                    $data['trust_no'] = $vv['trust_no'];//盈亏比例
                    $data['type'] = $vv['type'];//帐号类别
                    $data['jigou_type'] = $vv['jigou_type'];
                    $data['jiyisuo'] = $vv['jiyisuo'];//交易所
                    $data['sub_id']=$vv['sub_id'];
                    $data['position_id']=$vv['id'];
                    $data['status']=1;//状态，1、未除权
                    $data['stock_count']=$vv['stock_count'];
                    $data['buy_average_price']=$vv['buy_average_price'];
                    $bp_res=Db::name("stock_bonus_position")->insert($data);//dump($bp_res);
                }
                unset($data);
            }
        }
        $result=Db::name("stock_bonus")->insertAll($res);

    }
    /*
     * 除权日
     */
    public static function ex_dividend(){
        $binfo=Db::name("stock_bonus")->where('addtime',">=",mktime(0,0,0))->where(['type'=>2])->find();
        if(!empty($binfo)){
            return ;
        }
        $info=substr(qq_bonus(),13);
        $info=json_decode($info,true);
        $stock_record=$info['data']['076016']['data'];//除权
        $res=[];
        $rul='((\\-|\\+)?\\d+(\\.\\d+)?)';
        foreach ($stock_record as $k=>$v) {
            $res[$k]=Handle($v,$k,$rul)[$k];
            if($res[$k]==false){
                unset($res[$k]);
                continue;
            }
            $res[$k]['date'] = $v['date'];
            $res[$k]['code'] = $v['zqdm'];
            $res[$k]['name'] = $v['zqjc'];
            $res[$k]['info'] = "除权日";
            $res[$k]['type'] = 2;
            $res[$k]['addtime'] = time();
            $bp_info=Db::name("stock_bonus_position")->where(["code"=>$res[$k]['code']])->where(["status"=>1])->select();
            if(empty($bp_info)){
                continue;
            }else{
                $subaccount=new SubAccountMoney();
                foreach ($bp_info as $kk=>$vv){
                    Db::startTrans();
                    $submoney_info = Db::name("stock_subaccount_money")->where(['stock_subaccount_id' => $vv['sub_id']])->lock()->find();
                    $sm_res=$sp_res=$rec_1=$zp_res=$rec_2=$bp_res=$bp_ret =true;
                    if($vv['bonus']>0) {
                        if (!empty($submoney_info)) {
                            //除息公式 10股红利/10*100（转为分）*登记股票数
                            $fee = $vv['bonus'] * 10 * $vv['stock_count'];
                            $sm_res=$subaccount->up_moneylog($vv['sub_id'],$fee,13,0,0,$v['zqdm']);
                            $p_info = Db::name("stock_position")->where(['id' => $vv['position_id']])->find();
                            if(!empty($p_info)){
                                $p1data['ck_price']=($p_info['buy_average_price']*$p_info['stock_count']-$vv['bonus']*$vv['stock_count']/10)/$p_info['stock_count'];
                                $p1data['buy_average_price']=$p1data['ck_price'];
                                $p1data['ck_profit_price']=$p1data['ck_price'];
                                $bp_ret = Db::name("stock_position")->where(['id' => $vv['position_id']])->update($p1data);
                                unset($p1data);
                            }
                         }
                    }
                    if($vv['song']>0){
                        $p_info = Db::name("stock_position")->where(['id' => $vv['position_id']])->find();
                        if(!empty($p_info)){
                            $bcount=$vv['song']/10*$vv['stock_count'];
                            $p2data['stock_count']=$p_info['stock_count']+$bcount;
                            $p2data['count']=$p_info['stock_count']+$bcount;
                            $p2data['canbuy_count']=$p_info['stock_count']+$bcount;
                            $p2data['ck_price']=$p_info['buy_average_price']*$p_info['stock_count']/$p2data['stock_count'];
                            $p2data['buy_average_price']=$p2data['ck_price'];
                            $p2data['ck_profit_price']=$p2data['ck_price'];
                            $sp_res = Db::name("stock_position")->where(['id' => $vv['position_id']])->update($p2data);
                            $info="(=)用户股票".$vv["code"]."除权除息送股共计".$bcount."股";
                            $rec_1=$subaccount::record($vv['sub_id'],0,$submoney_info['avail'],14,$info);
                        }else{
                            $bcount=$vv['zuan']/10*$vv['stock_count'];
                            $p2data['sub_id'] = $vv['sub_id'];
                            $p2data['lid'] = $vv['lid'];
                            $p2data['soruce'] = $vv['soruce'];
                            $p2data['login_name'] = $vv['login_name'];
                            $p2data['gupiao_code'] = $vv["code"];
                            $p2data['gupiao_name'] = $vv["name"];
                            $p2data['stock_count']=$bcount;
                            $p2data['count']=$bcount;
                            $p2data['canbuy_count']=$bcount;
                            $p2data['ck_price']=0;
                            $p2data['buy_average_price']=$p2data['ck_price'];
                            $p2data['ck_profit_price']=$p2data['ck_price'];
                            $p2data['now_price'] = $vv['buy_average_price'];//'当前价'
                            $p2data['market_value'] = $vv['buy_average_price']*$bcount;//最新市值
                            $p2data['ck_profit'] = 0;//参考浮动盈亏
                            $p2data['profit_rate'] = 0;//盈亏比例
                            $p2data['trust_no'] = $vv['trust_no'];//盈亏比例
                            $p2data['buying'] = 0;//买入成功
                            $p2data['selling'] = 0;//1、在途卖出
                            $p2data['gudong_code'] = "";//股东代码 无法模拟暂时空
                            $p2data['type'] = $vv['type'];//帐号类别
                            $p2data['jigou_type'] =$vv['jigou_type'];
                            $p2data['jiyisuo'] = $vv['jiyisuo'];//交易所
                            $p2data['info'] = "";
                            $zp_res = Db::name("stock_position")->insert($p2data);
                            $info="(=)用户股票".$vv["code"]."除权除息送股共计".$bcount."股";
                            $rec_1=$subaccount::record($vv['sub_id'],0,$submoney_info['avail'],14,$info);
                        }
                        unset($p2data);
                        unset($bcount);
                    }
                    if($vv['zuan']>0){
                        $p_info = Db::name("stock_position")->where(['id' => $vv['position_id']])->find();
                        if(!empty($p_info)){
                            $b3count=$vv['zuan']/10*$vv['stock_count'];
                            $p3data['stock_count']=$p_info['stock_count']+$b3count;
                            $p3data['count']=$p_info['stock_count']+$b3count;
                            $p3data['canbuy_count']=$p_info['stock_count']+$b3count;
                            $p3data['ck_price']=$p_info['buy_average_price']*$p_info['stock_count']/$p3data['stock_count'];
                            $p3data['buy_average_price']=$p3data['ck_price'];
                            $p3data['ck_profit_price']=$p3data['ck_price'];
                            $zp_res = Db::name("stock_position")->where(['id' => $vv['position_id']])->update($p3data);
                            $info="(=)用户股票".$vv["code"]."除权除息转增共计".$b3count."股";
                            $rec_2=$subaccount::record($vv['sub_id'],0,$submoney_info['avail'],14,$info);
                        }else{
                            $bcount=$vv['zuan']/10*$vv['stock_count'];
                            $p3data['sub_id'] = $vv['sub_id'];
                            $p3data['lid'] = $vv['lid'];
                            $p3data['soruce'] = $vv['soruce'];
                            $p3data['login_name'] = $vv['login_name'];
                            $p3data['gupiao_code'] = $vv["code"];
                            $p3data['gupiao_name'] = $vv["name"];
                            $p3data['stock_count']=$bcount;
                            $p3data['count']=$bcount;
                            $p3data['canbuy_count']=$bcount;
                            $p3data['ck_price']=0;
                            $p3data['buy_average_price']=$p3data['ck_price'];
                            $p3data['ck_profit_price']=$p3data['ck_price'];
                            $p3data['now_price'] = $vv['buy_average_price'];//'当前价'
                            $p3data['market_value'] = $vv['buy_average_price']*$bcount;//最新市值
                            $p3data['ck_profit'] = 0;//参考浮动盈亏
                            $p3data['profit_rate'] = 0;//盈亏比例
                            $p3data['trust_no'] = $vv['trust_no'];//盈亏比例
                            $p3data['buying'] = 0;//买入成功
                            $p3data['selling'] = 0;//1、在途卖出
                            $p3data['gudong_code'] = "";//股东代码 无法模拟暂时空
                            $p3data['type'] = $vv['type'];//帐号类别
                            $p3data['jigou_type'] =$vv['jigou_type'];
                            $p3data['jiyisuo'] = $vv['jiyisuo'];//交易所
                            $p3data['info'] = "";
                            $zp_res = Db::name("stock_position")->insert($p3data);
                            $info="(=)用户股票".$vv["code"]."除权除息转增共计".$bcount."股";
                            $rec_2=$subaccount::record($vv['sub_id'],0,$submoney_info['avail'],14,$info);
                        }
                        unset($p3data);
                        unset($bcount);
                    }
                    $data=$vv;
                    $ex_data['status']=2;
                    $ex_res=Db::name("stock_bonus_position")->where(["id"=>$vv['id']])->update($ex_data);
                    unset($data['id']);
                    $m_info=Db::view("stock_subaccount s",'sub_account,uid')
                        ->view('member m','mobile,name','m.id=s.uid','left')
                        ->where(['s.id'=>$vv['sub_id']])
                        ->find();
                    $trade_time=Db::name("stock_deal_stock")
                        ->where(['sub_id'=>$vv['sub_id']])
                        ->where(['gupiao_code'=>$vv["code"]])
                        ->where(['flag2'=>'证券买入'])
                        ->max('deal_date');
                    if(empty($trade_time)){
                        $data['trade_time']=time()-86400;
                    }else{
                        $data['trade_time']=$trade_time;
                    }
                    $data['date']=$v['date'];
                    $data['addtime']=time();
                    $data['status']=2;
                    $data['ex_count']=0;
                    $data['mobile']=$m_info['mobile'];
                    $data['sub_account']=$m_info['sub_account'];
                    $data['uname']=$m_info['name'];
                    $bp_res=Db::name("stock_bonus_dividend")->insert($data);
                    if($sm_res&&$sp_res&&$rec_1&&$zp_res&&$rec_2&&$bp_res&&$ex_res&&$bp_ret){
                        Db::commit();
                    }else{
                        Db::rollback();
                    }
                }
            }
        }
        $result=Db::name("stock_bonus")->insertAll($res);
    }


    public static function ex(){
        if(time()>mktime(16,0,0)){
            self::register();
        }
        if(time()<mktime(8,0,0)){
            self::ex_dividend();
        }
        //日志定时清除，注释不执行，需要则打开
        if(time()>mktime(23,0,0)){
            //self::wipeCache();
        }
    }
    //清空缓存
    public static function wipeCache()
    {
        $dirs = (array) glob(constant('LOG_PATH') . '*');
        foreach ($dirs as $dir) {
            array_map('unlink', glob($dir . '/*.log'));
        }
        array_map('rmdir', $dirs);
    }
    /*
     * 日处理任务
     */
    public static function day(){
        self::ex();
        //自动续期定时任务
        $b_res=Db::view('stock_borrow b')
            ->view('stock_subaccount_risk r', 'renewal', 'r.stock_subaccount_id=b.stock_subaccount_id', 'left')
            ->view('money m','account','m.mid=b.member_id','left')
            ->where(['b.status'=>1])
            ->select();
        $borrow=new Borrow();
        $position = new Position();
        //$res=$position->field('sub_id')->where(['buying'=>0])->group('sub_id')->select();
        if(!empty($b_res)){
            foreach ($b_res as $k=>$v){
                $type_arr=[4,5,6];
                if(in_array($v['type'],$type_arr)&&$v['end_time']<=time()&&$v['end_time']!=0&&$v['status']!=2){
                    //if(time()<mktime(14,45,0)||time()>mktime(15,0,0)){continue;}
                    switch ($v['type']){
                        case 4:
                            $typeinfo="试用配资";
                            break;
                        case 5:
                            $typeinfo="免息配资";
                            break;
                        case 6:
                            $typeinfo="模拟操盘";
                            break;
                        default:
                            $typeinfo="免息配资";
                            break;
                    }
                    $subname=Db::name('stock_subaccount')->where(['id'=>$v['stock_subaccount_id']])->find();
                    //self::sendsms_to_admin($typeinfo."子账户:".$subname['sub_account'].",已到期，已强制平仓");
                    //$name=Db::name('member')->where(['id'=>$subname['uid']])->find();
                    //self::send_sms($name['mobile'],'','您的'.$typeinfo.'子账户:'.$subname['sub_account'].',已到期，已强制平仓');
                    $res=$position->where(['sub_id'=>$v['stock_subaccount_id']])->where(['buying'=>0])->select();
                    if(empty($res)){
                        $time=date('Y-m-d H:i:s',time()).'子账户“'.$v['stock_subaccount_id'].'”持仓为空'.PHP_EOL;
                        file_put_contents("/test.txt", $time, FILE_APPEND);
                        continue;
                    }else{
                        $time=date('Y-m-d H:i:s',time()).'子账户“'.$v['stock_subaccount_id'].'”持仓不为空'.PHP_EOL;
                        file_put_contents("/test.txt", $time, FILE_APPEND);
                    }
                    foreach ($res as $key => $val) {
                        $sell_res = $borrow->savesell($val['sub_id'], $val['gupiao_code'], $val['canbuy_count'], $sys = 1);
                        $sell_res['message'] = iconv("UTF-8", "GB2312//IGNORE", $sell_res['message']);
                        if(isset($sell_res['status'])&&$sell_res['status']==0){
                            Log::write('卖出时产生错误：'.$sell_res['message'],'notice');
                        }else{
                            Log::write($typeinfo."子账户:".$subname['sub_account'].",已到期，已强制平仓");
                        }
                    }
                    continue;
                }
                if($v['end_time']<=time()&&$v['renewal']===1&&$v['end_time']!=0){
                    //收取利息
                    $peiziMoney = $v['borrow_money'];
                    $multiple = $v['multiple'];
                    $borrow_duration=1;
                    switch ($v['type']){
                        case 1:
                            $rateSet=self::config('day_rate');
                            $rate=$rateSet[$multiple]/100;
                            break;
                        case 2:
                            $rateSet=self::config('week_rate');
                            $rate=$rateSet[$multiple]/100;
                            break;
                        case 3:
                            $rateSet=self::config('month_rate');
                            $rate=$rateSet[$multiple]/100;
                            break;
                        default:
                            $rate=0;
                            break;
                    }
                    //利息 * 时间 * 配资金额
                    // 延期一次性计算利息
                    $minfo=Member::getMemberInfoByID($v['member_id']);
                    $borrow_fee = bcmul($peiziMoney, $rate)*$borrow_duration;
                    $amoney = $minfo['account'];
                    if($borrow_fee > $amoney){
                        Db::startTrans();
                        $bdata['status']=3;//
                        $bs_res=Db::name("stock_borrow")->where(['id'=>$v['id']])->update($bdata);
                        $rdata['prohibit_open']=0;
                        $rdata['prohibit_close']=0;
                        $risk_res=Db::name("stock_subaccount_risk")->where(['stock_subaccount_id'=>$v['stock_subaccount_id']])->update($rdata);
                        if($bs_res&&$risk_res){
                            Db::commit();
                            Log::write("配资".$v['order_id']."资金不足无法续期进入逾期状态",'info');
                        }else{
                            Db::rollback();
                            Log::write("配资".$v['order_id']."资金不足无法续期进入逾期状态失败",'info');
                        }
                        continue;//余额不足先跳过
                    }
                    $infomoney=$borrow_fee/100;//转化为元
                    $sdata['borrow_duration'] = $borrow_duration+$v['borrow_duration'];
                    $sdata['borrow_interest'] = $v['borrow_interest']+$borrow_fee;
                    Db::startTrans();
                    $sdata['end_time'] = Renewal::getAddTime($v['type'], $v['end_time'], $borrow_duration);
                    $borrow_res=Db::name("stock_borrow")->where("id={$v['id']}")->update($sdata);
                    $minfo_a=Db::name("money")->lock(true)->where(['mid'=>$v['member_id']])->find();
                    $account = $minfo_a['account'] - $borrow_fee;
                    $money_ret=Money::money_freeze($v['member_id'],$minfo_a['freeze'],$account);//直接扣费
                    $info = "配资".$v['order_id']."自动续期，扣除续期利息{$infomoney}元";
                    $record = new Record;
                    $Record_Ret = $record->saveData($v['member_id'], -abs($borrow_fee), $account, 34, $info);//23:配资续期

                    if ($Record_Ret&&$money_ret&&$borrow_res) {
                        //self::send_sms($minfo['mobile'],'', $info);
                        $MemberMessageModel = new MemberMessageModel();
                        $MemberMessageModel->addInnerMsg($minfo_a['mid'],'自动续期成功通知',$info);//站内信
                        Db::commit();
                        //根据佣金比例分配佣金 用户id 配资id 配资管理费
                        $BorrowModel = new Borrow();
                        $res_agent = $BorrowModel->agentToRateMoney($v['member_id'],$v['id'],$infomoney,4);
                        Log::write("配资".$v['order_id']."自动续期成功",'info');
                    }else{
                        Db::rollback();
                        Log::write("配资".$v['order_id']."自动续期失败",'info');
                    }

                }elseif($v['end_time']<=time()&&$v['renewal']===0&&$v['end_time']!=0){
                    Db::startTrans();
                    $bdata['status']=3;//
                    $bs_res=Db::name("stock_borrow")->where(['id'=>$v['id']])->update($bdata);
                    $rdata['prohibit_open']=0;
                    $rdata['prohibit_close']=1;
                    $risk_res=Db::name("stock_subaccount_risk")->where(['stock_subaccount_id'=>$v['stock_subaccount_id']])->update($rdata);
                    if($bs_res&&$risk_res){
                        Db::commit();
                        Log::write("配资".$v['order_id']."进入逾期状态",'info');
                    }else{
                        Db::rollback();
                        Log::write("配资".$v['order_id']."进入逾期状态失败",'info');
                    }
                }
            }
        }
        //按月配资自动扣费
        $now = time();
        $stock_list = Db::view('stock_detail sd',true)
            ->view('stock_borrow b', 'stock_subaccount_id', 'sd.borrow_id=b.id', 'left')
            ->where(["sd.status" =>0])
            ->select();
        $tempArr = [];
        $submoney=new SubAccountMoney();
        $record=new Record();
        foreach($stock_list as $k=>$v){
            if($v['deadline'] < $now){
                $tempArr[$k] = $v;
            }
        }
        //执行还款
        foreach ($tempArr as $k => $v){
            // 扣除手续费，优先从余额扣除，如果余额不足，从保证金里扣除
            Db::startTrans();
            $user_money = Db::name("money")
                ->field('freeze,account')
                ->lock(true)
                ->where("mid = ".$v['mid'])
                ->find();
            //if($user_money['account'] >= $v['interest']){
                $data['account'] = $user_money['account'] - $v['interest']*100;
                $sub_ret=true;
//            }else{
//                $data['account'] =0;
//                $sub_id=$v['stock_subaccount_id'];
//                $sub_res=$submoney->get_account_money($sub_id);
//                $affect_money = $sub_res['deposit_money'] + $user_money['account'] - $v['interest']*100;
//                $sub_ret =$submoney->up_moneylog($sub_id,$affect_money,7);
//            }
            //更新用户资金
            $updateMoney = Db::name('money')->where("mid=".$v['mid'])->update($data);
            //增加用户变动记录
            $info = "按月配资手续费自动扣款";
            $record_ret=$record->saveData($v['mid'], $v['interest']*100, $data['account'], 32, $info);
            if($record_ret && $updateMoney&&$sub_ret){
                $d_data['status'] = 1;
                $d_data['repayment_time'] = $now;
                $stock_detail_update = Db::name("stock_detail")->where("id=".$v['id'])->update($d_data);
                if($stock_detail_update){
                    Db::commit();
                    Log::write("会员".$v['id']."自动扣除手续费成功",'info');
                }else{
                    Db::rollback();
                    Log::write("会员".$v['id']."自动扣除手续费失败",'info');
                }
            } else {
                Db::rollback();
                Log::write("会员".$v['id']."自动扣除手续费失败",'info');
            }
        }
        return ;
    }
    /*
     * 分处理任务
     */
    public static function minute(){
        if(!yan_time($last_time=15.1)){
            Log::write('非交易时间');
            return;
        }
        //自动平仓开始
        $position = new Position();
        $res=Db::name("stock_position")->field('sub_id')->where(['buying'=>0])->group('sub_id')->select();
        $moneymodel = new SubAccountMoney();
        $risk = new StockSubAccountRisk();
        $borrow=new Borrow();
        if(!empty($res)){
            foreach($res as $k=>$v){
                $sub_id=$v['sub_id'];
                $binfo=Db:: name("stock_borrow")->where(['stock_subaccount_id'=>$sub_id,'type'=>4])->find();
                if(!empty($binfo)){continue;}
                $moneyinfo = $moneymodel->get_account_money($sub_id);
                $risk_res = $risk->get_risk($sub_id);
                if($risk_res['autoclose']===0){continue;}//如果关闭了自动平仓则跳过
                $position_res = $position->get_position($sub_id);
                $sum = 0;
                $sw=0;//异常价格开关
                if (count($position_res) >= 2) {
                    $code = "";
                    $counts = array();
                    foreach ($position_res as $key => $val) {
                        $code = $code . $val['gupiao_code'] . ",";
                        $counts[$val['gupiao_code']] = $val['stock_count'];
                    }
                    $code = substr($code, 0, -1);
                    $p_res = z_market_bat($code);
                    foreach ($p_res as $key => $vv) {
                        if($vv["current_price"]<=0){$sw=1;break;}
                        $sum += $counts[$vv["code"]] * $vv["current_price"];
                    }
                } elseif (count($position_res) == 1) {
                    $code = $position_res[0]['gupiao_code'];
                    $p_res = z_market($code);
                    if($p_res["current_price"]<=0){$sw=1;}
                    $sum = $p_res["current_price"] * $position_res[0]['stock_count'];
                }
                if($sw==1){continue;}
                //止损线=保证金*止损比例+配资金额-市值-可用余额-累计追加保证金 不考虑累计提取盈利
                $p_rate = $moneyinfo["deposit_money"] * $risk_res['loss_close'] / 100 + $moneyinfo["borrow_money"] - $sum * 100 - $moneyinfo["avail"] -$moneyinfo["freeze_amount"];//- $moneyinfo["stock_addmoney"];
                if ($p_rate >0) {
                    $subname=Db::name('stock_subaccount')->where(['id'=>$sub_id])->find();
                    $order_id_sms = Db::name('stock_borrow')->where('stock_subaccount_id',$v['sub_id'])->value('order_id');
                    //sendsms_to_admin("子账户:".$subname['sub_account'].",达止损线，已强制平仓");
                    $name=Db::name('member')->where(['id'=>$subname['uid']])->find();
                    $content = \think\Config::get('sms_template')['stock_loss_close'];
                    $content =  str_replace(array("#var#","#order_id#"),array($name['mobile'],$order_id_sms), $content);
                    $num=1;
                    foreach ($position_res as $key => $val) {
                        $trust_info=Db::name("stock_trust")
                            ->where(['sub_id'=>$v['sub_id']])
                            ->where(['gupiao_code'=>$val['gupiao_code']])
                            ->where('add_time','>=',mktime(9,30,0))
                            ->find();
                        if(!empty($trust_info)){
                            Log::write('子账号:'.$v['sub_id'].'的'.$val['gupiao_code']."用户已委托卖出，不能重复委托");
                        }
                        $sell_res=$borrow->savesell($val['sub_id'],$val['gupiao_code'],$val['canbuy_count'],$sys=1);
                        if(!isset($sell_res['status'])){continue;}
                        if($sell_res['status']==0){
                            Log::write('卖出子账号:'.$v['sub_id'].'的'.$val['canbuy_count']."股".$val['gupiao_code'].'股票时产生错误：'.$sell_res['message'],'notice');
                        }elseif($num==1&&$sell_res['status']==1){
                            sendsms_mandao($name['mobile'],$content,'user');
                            $num+=1;
                        }
                    }
                }
            }
        }
        //自动平仓结束
        return ;
    }

    public static function detail($code='600000'){
        $d = fenxi($code);
        $url="http://qt.gtimg.cn/q=" . $d;
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "http://qt.gtimg.cn/q=" . $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // $output = curl_exec($ch);
        // curl_close($ch);

        $output=file_get_contents($url);
        $t2 = explode('~', mb_convert_encoding($output, "utf-8", "gbk"));
        $t2['29'] = explode('|',$t2['29']);
        $ret=[];
        foreach ($t2['29'] as $k =>$v){
            $ret[$k]=explode('/',$v);
            $tmd[$k]=$ret[$k][1];
            $ret[$k][1]=$ret[$k][2];
            $ret[$k][2]=$tmd[$k];
        }
        if($ret==[]){return null;}
        return $ret;
    }
    public static function detail_($code='600000'){
        $d = fenxi($code);
        $randm=time().mt_rand(100,999);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://vip.stock.finance.sina.com.cn/quotes_service/view/CN_TransListV2.php?num=10&symbol=".$d."&rn=".$randm);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $tmd=explode(';',$output);
        if(count($tmd)==3){return null;}
        unset($tmd[0]);
        unset($tmd[11]);
        unset($tmd[12]);
        $res=[];
        $ret=[];
        $time=date('h:i:s',time()-12);
        foreach ($tmd as $k=>$v){
            $num=strpos($v,'Array(')+7;
            $res[$k]=explode(',',str_replace('\'','',substr($v,$num,-2)));
            if($res[$k][0]>$time) {
                $ret[$k][0] = trim($res[$k][0]);
                $ret[$k][1] = trim($res[$k][1]);
                $ret[$k][2] = trim($res[$k][2]);
                $ret[$k][3] = trim($res[$k][3]);
            }
        }
        if($ret==[]){return null;}
        return $ret;
    }
    /*
     * 限价交易处理任务+
     */
    public static function temp()
    {
        if(!yan_time(15.2)){
            Log::write('非交易时间');
            return;
        }
        $data = Db::name('stock_temp')
            ->where("trust_date", ">=", time() - 154001)
            ->where(["deal_no" => null])
            ->select();

        //没有未经处理的任务
        if ($data === false || count($data) === 0) {
            Log::write('没有未经处理的委托');
            return;
        }
        $subaccount=new SubAccountMoney();
        foreach ($data as $k => $v) {
            if(self::config('trust_active_time')==0){
                if(time()>=mktime(15,0,0)){
                    $cancel_res=self::cancel_order($v);//撤单
                    if($cancel_res['status']){
                        Log::write('子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code'].'自动撤单成功');
                        echo '子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code']."自动撤单成功\n\r";
                        continue;
                    }else{
                        Log::write('子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code'].'自动撤单失败');
                        echo '子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code']."自动撤单失败:(\n\r";
                        continue;
                    }
                }
            }elseif((time()-$v['add_time'])/60>=self::config('trust_active_time')||time()>=mktime(15,0,0)){
                $cancel_res=self::cancel_order($v);//撤单
                if($cancel_res['status']){
                    Log::write('子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code'].'自动撤单成功');
                    echo '子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code']."自动撤单成功\n\r";
                    continue;
                }else{
                    Log::write('子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code'].'自动撤单失败');
                    echo '子账号'.$v['sub_id'].'的'.$v['flag2'].$v['trust_count'].'股'.$v['gupiao_code']."自动撤单失败:(\n\r";
                    continue;
                }
            }
            $res = self::detail_($v['gupiao_code']);
            if (empty($res)) {continue; }
            if ($v['flag2'] == "买入委托") {
                foreach ($res as $kk => $vv) {
                    if ($vv[2] <=$v['trust_price']) {
                        Db::startTrans();
                        $trust=[];
                        $trust['status']= "已成";
                        $trust['cancel_order_flag']= "1";
                        $trust['volume']= $v['volume'];
                        $trust['amount']= $vv[2]*$v['trust_count'];
                        $trust_res=Db::name('stock_trust')->where(['trust_no'=>$v['trust_no']])->update($trust);
                        $data['status']= "已成";
                        $data['cancel_order_flag']= "1";
                        $data['deal_no']= $v['trust_no']+521;
                        $demp_res=Db::name('stock_temp')->where(['id'=>$v['id']])->update($data);
                        $trade_money=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->value('liquidation_amount');
                        if(empty($trade_money)){
                            Db::rollback();
                            Log::write('买入确认时未查询到交易费用信息');
                            return;
                        }

                        $moneymodel = new SubAccountMoney();
                        $moneyinfo = $moneymodel->get_account_money($v['sub_id']);
                        $commission = round($v['trust_count']*$vv[2] * $moneyinfo['commission_scale'] / 10000,2);//佣金
                        if ($commission < $moneyinfo['min_commission']/100) {
                            $commission = $moneyinfo['min_commission']/100;
                        }
                        $type=self::typefenxi($v['gupiao_code']);
                        if ($type == 2) {//上海交易所
                            $Transfer = round($v['trust_count']*$vv[2] / 50000,2);//过户费,只有上海交易所收
                            if ($Transfer < self::config('transfer_fee')) {
                                $Transfer = self::config('transfer_fee');
                            }
                        } else {
                            $Transfer = 0;
                        }
                        $fee=$commission+$Transfer;
                        $dev_info=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->find();
                        $cha=$dev_info['commission']+$dev_info['transfer_fee']-$fee;
                        $Balance=round($v['trust_count']*($v['trust_price']-$vv[2])+$cha,2);
                        $sm_res=$subaccount->up_moneylog($v['sub_id'],$trade_money*100,6,$return_money=0,$Balance*100);
                        $deal=[];
                        $deal['status'] = "买入成交";//状态说明
                        $deal['cancel_order_flag'] = '1';//撤单标志 1、已成交
                        $deal['deal_price'] = $vv[2];
                        $deal['deal_time'] = date("H:i:s",time());
                        $deal['amount'] = $vv[2]*$v['trust_count'];
                        $deal_res=Db::name('stock_deal_stock')->where(['trust_no'=>$v['trust_no']])->update($deal);
                        $ptmd=Db::name('stock_position')->where(['sub_id'=>$v['sub_id'],'gupiao_code'=>$v['gupiao_code']])->find();

                        $delivery=[];
                        $delivery['status']='1';
                        $delivery['deal_price']=$vv[2];
                        $delivery['deal_date']=time();
                        $delivery['transfer_fee']=$Transfer;
                        $delivery['commission']=$commission;
                        $delivery['residual_quantity']=$deal['amount'];
                        $delivery['liquidation_amount']=$v['trust_count']*$vv[2]+$fee;
                        $delivery['residual_amount']=$dev_info['residual_amount']+$dev_info['liquidation_amount']-$delivery['liquidation_amount'];
                        if(!empty($ptmd)){
                            $delivery['amount']=$ptmd['canbuy_count']+$v['trust_count'];
                        }else{
                            $delivery['amount']=$v['trust_count'];
                        }
                        $delivery_res=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->update($delivery);
                        if(empty($ptmd)){
                            switch (substr($v['gupiao_code'], 0, 1)) {
                                case '0':  $d = 0;  break;
                                case '3':  $d = 0;  break;
                                case '1':  $d = 0;  break;
                                case '2':  $d = 0;  break;
                                case '6':  $d = 1;  break;
                                case '5':  $d = 1;  break;
                                case '9':  $d = 1;  break;
                                default :
                                    Db::rollback();
                                    echo "买入确认失败\n\r";
                                    continue;
                            }
                            $ck_price=round(($v['trust_count']*$vv[2]+$fee)/$v['trust_count'],3);
                            $position=array();
                            $position['sub_id'] = $v['sub_id'];
                            $position['lid'] = $v['lid'];
                            $position['soruce'] = $v['soruce'];
                            $position['login_name'] = $v['login_name'];
                            $position['gupiao_code'] = $v["gupiao_code"];
                            $position['gupiao_name'] = $v["gupiao_name"];
                            $position['count'] = $v['trust_count'];
                            $position['stock_count'] =$v['trust_count'];
                            $position['canbuy_count'] = $v['trust_count'];
                            $position['ck_price']=$ck_price;
                            $position['now_price']=$vv[2];
                            $position['buy_average_price']=$ck_price;
                            $position['ck_profit_price'] = $ck_price;//参考盈亏成本价
                            $position['market_value'] = $vv[2]*$v['trust_count'];//最新市值
                            $position['ck_profit'] = round(($vv[2]-$ck_price)*$v['trust_count'],2);//参考浮动盈亏
                            $position['profit_rate'] = round($position['ck_profit']/($ck_price*$v['trust_count'])*100,2);//盈亏比例
                            $position['buying']=0;
                            $position['selling'] = 0;//1、在途卖出
                            $position['gudong_code'] = "";//股东代码 无法模拟暂时空
                            $position['type'] = $d;//帐号类别
                            $position['jigou_type'] = 1;
                            $position['jiyisuo'] = $d==0? "深交所":"上交所";//交易所
                            $position['info'] = "";
                            $position_res = Db::name('stock_position')->insert($position,true);
                        }else{
                            $position=$ptmd;
                            $position['buying']=0;
                            $canbuy_count=$ptmd['canbuy_count']+$v['trust_count'];
                            $stock_count=$ptmd['stock_count']+$v['trust_count'];
                            $new_price=round(($ptmd['stock_count']*$ptmd['buy_average_price']+$v['trust_count']*$vv[2]+$fee)/($ptmd['stock_count']+$v['trust_count']),3);
                            $position['count'] = $stock_count;
                            $position['stock_count'] =$stock_count;
                            $position['canbuy_count'] = $canbuy_count;
                            $position['ck_price'] = $new_price;//参考成本价
                            $position['buy_average_price'] = $new_price;//买入均价
                            $position['ck_profit_price'] = $new_price;//参考盈亏成本价
                            $position['now_price'] = $vv[2];//'当前价'
                            $position['market_value'] = $vv[2]*$canbuy_count;//最新市值
                            $position['ck_profit'] = round(($vv[2]-$new_price)*$canbuy_count,2);//参考浮动盈亏
                            $position['profit_rate'] = round($position['ck_profit']/($new_price*$canbuy_count)*100,2);//盈亏比例
                            $position_res=Db::name('stock_position')
                                ->where(['sub_id'=>$v['sub_id']])
                                ->where(['gupiao_code'=>$v['gupiao_code']])
                                ->update($position);
                        }
                        if($sm_res&$trust_res&&$demp_res&&$deal_res&&$delivery_res&&$position_res){
                            Db::commit();
                            echo "买入确认成功\n\r";
                        }else{
                            Db::rollback();
                            echo "买入确认失败\n\r";
                        }
                        break;
                    } else {
                        continue;
                        //dump("买入价:".$v['trust_price'].'|现价:'.$vv[2].'|时间:'.$vv[0]);
                    }
                }
            }elseif($v['flag2'] == "卖出委托") {
                foreach ($res as $kk => $vv) {
                    if ($vv[2] >= $v['trust_price']) {
                        Db::startTrans();
                        $trust= [];
                        $trust['status']= "已成";
                        $trust['cancel_order_flag']= "1";
                        $trust['volume']= $v['trust_count'];//$v['volume'];
                        $trust['amount']= $vv[2]*$v['trust_count'];
                        $trust_res=Db::name('stock_trust')->where(['trust_no'=>$v['trust_no']])->update($trust);
                        $data['status']= "已成";
                        $data['cancel_order_flag']= "1";
                        $data['deal_no']= $v['trust_no']+521;
                        $demp_res=Db::name('stock_temp')->where(['id'=>$v['id']])->update($data);
                        $trade_money=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->value('liquidation_amount');
                        $stamp=round($v['trust_count']*$vv[2]*self::config('stamp_duty')/1000,2);//印花税
                        $moneymodel = new SubAccountMoney();
                        $moneyinfo = $moneymodel->get_account_money($v['sub_id']);
                        $commission = round($v['trust_count']*$vv[2] * $moneyinfo['commission_scale'] / 10000,2);//佣金
                        if ($commission < $moneyinfo['min_commission']/100) {
                            $commission = $moneyinfo['min_commission']/100;
                        }
                        $type=self::typefenxi($v['gupiao_code']);
                        if ($type == 2) {//上海交易所
                            $Transfer = round($v['trust_count']*$vv[2] / 50000,2);//过户费,只有上海交易所收
                            if ($Transfer < self::config('transfer_fee')) {
                                $Transfer = self::config('transfer_fee');
                            }
                        } else {
                            $Transfer = 0;
                        }
                        $fee=$commission+$Transfer+$stamp;
                        $dev_info=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->find();
                        $cha=$dev_info['commission']+$dev_info['transfer_fee']+$dev_info['stamp_duty']-$fee;
                        $Balance=round(($vv[2]-$v['trust_price'])*$v['trust_count']+$cha,2);
                        if(empty($trade_money)){
                            Db::rollback();
                            Log::write('卖出确认时未查询到交易费用信息');
                            return;
                        }
                        $type=5;//限价卖出股票回款
                        $ptmd=Db::name('stock_position')->where(['sub_id'=>$v['sub_id'],'gupiao_code'=>$v['gupiao_code']])->find();
                        //零散股票处理开始
                        $lll=$v['trust_count']-$v['volume'];
                        if($lll>0&&$lll%100>0&&!empty($ptmd)){
                            $bonus=$ptmd;
                            unset($bonus['sub_id']);
                            $count_tmd=$lll%100;
                            $bonus['count']=$count_tmd;
                            $bonus['stock_count']=$count_tmd;
                            $bonus['canbuy_count']=$count_tmd;
                            $bonus['ck_price']=$vv[2];
                            $bonus['buy_average_price']=$vv[2];
                            $bonus['ck_profit_price']=$vv[2];
                            $bonus['now_price']=$vv[2];
                            $bonus['market_value']=$vv[2]*$bonus['stock_count'];
                            $sp_info=Db::name('stock_bonus_surplus')->where(['gupiao_code'=>$v['gupiao_code']])->find();
                            if(empty($sp_info)){
                                $bonus_res=Db::name('stock_bonus_surplus')->insert($bonus);
                            }else{
                                $bonus['count']=$bonus['count']+$sp_info['count'];
                                $bonus['stock_count']=$bonus['stock_count']+$sp_info['stock_count'];
                                $bonus['canbuy_count']=$bonus['canbuy_count']+$sp_info['canbuy_count'];
                                $bonus['ck_price']=($vv[2]*$count_tmd+$sp_info['ck_price']*$sp_info['stock_count'])/$bonus['stock_count'];
                                $bonus['buy_average_price']=$bonus['ck_price'];
                                $bonus['ck_profit_price']=$bonus['ck_price'];
                                $bonus['now_price']=$vv[2];
                                $bonus['market_value']=$vv[2]*$bonus['stock_count'];
                                $bonus_res=Db::name('stock_bonus_surplus')->where(['gupiao_code'=>$v['gupiao_code']])->update($bonus);
                            }
                        }
                        //零散股票处理结束
                        $return_money=($vv[2]-$ptmd['ck_price'])*$v['trust_count']-$Transfer-$stamp-$commission;
                        $sm_res=$subaccount->up_moneylog($v['sub_id'],$trade_money*100,$type,$return_money*100,$Balance*100);
                        //扣除利息税开始
                        $div_info=Db::name("stock_bonus_dividend")->where(['sub_id'=>$v['sub_id']])
                            ->where(['code'=>$v['gupiao_code']])
                            ->find();
                        if(!empty($div_info)&&$div_info['ex_count']<$div_info['stock_count']){
                            $tmd=mktime(0,0,0)-$div_info['trade_time'];
                            //默认20%利息税
                            $rate=0.2;
                            //30天内收取10%
                            if($tmd>86400*30){
                                $rate=0.1;
                            }
                            if($tmd<=86400*365){
                                $ex_cha=$div_info['stock_count']- $div_info['ex_count'];
                                $tmd_count=$ex_cha<=$v['trust_count']?$ex_cha:$v['trust_count'];
                                $feen=round($tmd_count*$div_info['bonus']/10*$rate+$tmd_count*$div_info['song']/10*$vv[2]*$rate,2);

                                if($feen>0){
                                    $div_res=$subaccount->up_moneylog($v['sub_id'],$feen*100,15,0,0,$v['gupiao_code']);
                                    if($div_res){
                                        $div_data['ex_count']=$div_info['ex_count']+$tmd_count;
                                        $ret= Db::name("stock_bonus_dividend")
                                            ->where(['id'=>$div_info['id']])
                                            ->update($div_data);
                                    }
                                }else{
                                    //如果$fee=0说明是转增，没有分红和送股，不用扣利息税
                                }
                            }else{
                                //持有超一年，不用扣利息税
                            }
                        }
                        //扣除利息税结束
                        $deal = [];//状态说明
                        $deal['status'] = "卖出成交";//状态说明
                        $deal['cancel_order_flag'] = '1';//撤单标志 1、已成交
                        $deal['deal_price'] = $vv[2];
                        $deal['deal_time'] = date("H:i:s",time());
                        $deal['amount'] = $vv[2]*$v['trust_count'];
                        $deal_res=Db::name('stock_deal_stock')->where(['trust_no'=>$v['trust_no']])->update($deal);
                        $delivery=[];
                        $delivery['status']='1';
                        if(!empty($ptmd)){
                            $delivery['amount']=$ptmd['canbuy_count'];
                        }
                        $delivery['deal_price']=$vv[2];
                        $delivery['deal_date']=time();
                        $delivery['stamp_duty']=$stamp;
                        $delivery['transfer_fee']=$Transfer;
                        $delivery['commission']=$commission;
                        $delivery['residual_quantity']=$deal['amount'];
                        $delivery['liquidation_amount']=$v['trust_count']*$vv[2]-$fee;
                        $delivery['residual_amount']=$moneyinfo['avail']/100+$Balance;
                        $delivery_res=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->update($delivery);
                        if(empty($ptmd)){
                            $position_res=false;
                        }else{
                            $stock_count=$ptmd['stock_count']-$v['trust_count'];
                            if($stock_count==0){
                                $position_res=Db::name('stock_position')->where(['sub_id'=>$v['sub_id']])->where(['gupiao_code'=>$v['gupiao_code']])->delete();
                                log::write("子账户".$v['sub_id']."清空".$v['gupiao_code']);
                            }elseif($stock_count>0){
                                $new_price=round(($ptmd['stock_count']*$ptmd['buy_average_price']-$v['trust_count']*$vv[2]-$fee)/$stock_count,3);
                                $position = array();
                                $position['count'] = $stock_count;
                                $position['stock_count'] =$stock_count;
                                $position['canbuy_count'] = $ptmd['canbuy_count'];
                                $position['ck_price'] = $new_price;//参考成本价
                                $position['buy_average_price'] = $new_price;//买入均价
                                $position['ck_profit_price'] = $new_price;//参考盈亏成本价
                                $position['now_price'] = $vv[2];//'当前价'
                                $position['market_value'] = $vv[2]*$ptmd['canbuy_count'];//最新市值
                                $position['ck_profit'] = round(($vv[2]-$new_price)*$ptmd['canbuy_count'],3);//参考浮动盈亏
                                $position['profit_rate'] = round($position['ck_profit']/($new_price*$ptmd['canbuy_count'])*100,2);//盈亏比例
                                $position['selling']=0;
                                $position['buying']=0;
                                $position_res=Db::name('stock_position')
                                    ->where(['sub_id'=>$v['sub_id']])
                                    ->where(['gupiao_code'=>$v['gupiao_code']])
                                    ->update($position);
                            }else{
                                $position_res=false;
                            }
                        }
                        if($sm_res&$trust_res&&$demp_res&&$deal_res&&$delivery_res&&$position_res){
                            Db::commit();
                            echo "卖出确认成功\n\r";
                        }else{
                            Db::rollback();
                            echo "卖出确认失败\n\r";
                        }
                        break;
                    } else {
                        continue;
                        //dump("卖出价:".$v['trust_price'].'|现价:'.$vv[2].'|时间:'.$vv[0]);
                    }
                }
            }
        }
        return;
    }

    public static function cancel_order($v){
        $subaccount=new SubAccountMoney();
        Db::startTrans();
        $yes=false;
        $tempinfo=Db::name('stock_temp')->where(['trust_no'=>$v['trust_no']])->lock(true)->find();
        if(!empty($tempinfo)){
            $type=self::typefenxi($tempinfo['gupiao_code']);
            if($type==0){
                Db::rollback();
                return ['status' => 0, 'message' => '股票代码不对，撤单失败'];
            }
        }else{
            Db::rollback();
            return ['status' => 0, 'message' => '没找到对应委托，撤单失败'];
        }
        $affect_money=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->value('liquidation_amount');
        if($tempinfo['deal_no']==null){
            $trust['status']= "已撤";
            $trust['cancel_order_flag']= "1";
            $trust['cancel_order_count']= $v['trust_count'];
            $trust_res=Db::name('stock_trust')->where(['trust_no'=>$v['trust_no']])->update($trust);
            $deal_res=Db::name('stock_deal_stock')->where(['trust_no'=>$v['trust_no']])->delete();
            $delivery_res=Db::name('stock_delivery_order')->where(['trust_no'=>$v['trust_no']])->delete();
            $ret=Db::name('stock_temp')->where(['trust_no'=>$v['trust_no']])->delete();
            $position=Db::name('stock_position')->where(['sub_id'=>$v['sub_id']])->where(['gupiao_code'=>$tempinfo['gupiao_code']])->find();
            $subm_res=false;
            $position_in=false;
            if($tempinfo['flag2']=='买入委托'){
                //解冻并转入子账户可用余额
                $subm_res=$subaccount->up_moneylog($v['sub_id'],$affect_money*100,8);
                $position_in=true;
            }elseif($tempinfo['flag2']=='卖出委托'){
                $position['canbuy_count']=$position['canbuy_count']+$v['trust_count'];
                $position_in=Db::name('stock_position')->where(['sub_id'=>$v['sub_id']])->where(['gupiao_code'=>$tempinfo['gupiao_code']])->update($position);
                $subm_res=$subaccount->up_moneylog($v['sub_id'],$affect_money*100,9);
            }
            if($trust_res&&$deal_res&&$delivery_res&&$ret&&$subm_res&&$position_in){
                $yes=true;
            }else{
                Db::rollback();
                return ['status' => 0, 'message' => '撤单失败'];
            }
        }
        $submodel = new StockSubAccount();
        $res = $submodel->get_account_by_id($v['sub_id']);
        $broker = $submodel->get_broker($res['account_id']);
        $trade_id = $broker['id'];
        $res = Db::name('admin_plugin')->where(['name' => "GreenSparrow"])->find();
        if (!empty($res)&&$yes&&$broker['broker']!=-1) {
            $day_re=[];
            if(config('web_real_api')==1) {
                $day_re = gs('queryTradeData', [$broker['id'], 3]);
            }
            if(config('web_real_api')==2){
                $day_re =Grid::grid_category_trust($broker['id']);
            }
            unset($day_re[0]);
            $orderid="";
            foreach ($day_re as $kt=>$vt){
                if($vt[4]==$tempinfo['gupiao_code']&&$vt[9]==$tempinfo['trust_count']&&$vt[7]==$tempinfo['flag2']){
                    $orderid=$vt[10];//得到实盘委托编号
                }
            }
            $data = [];
            if(!empty($orderid)){
                if (config('web_real_api') == 1) {
                    $data = gs('cancelOrder', [$trade_id, $orderid, $type]);
                }
                if (config('web_real_api') == 2) {
                    //将交易所类型转换为网格模式
                    if($type=1){
                        $type=0;
                    }else{
                        $type=1;
                    }
                    $data = grid::grid_cancel($type, $orderid, $trade_id);
                }
            }
            if($data){
                Db::commit();
                return ['status' => 1, 'message' => '撤单成功'];
            }else{

            }

        } elseif($yes) {
            if($broker['broker']!=-1){
                Db::rollback();
                return ['status' => 0, 'message' => '请安装股票实盘交易插件'];
            }else{
                Db::commit();
                return ['status' => 1, 'message' => '撤单成功'];
            }
        }
        Db::rollback();
        return ['status' => 0, 'message' => '撤单失败'];
    }
    public static function typefenxi($code){
        switch (substr($code, 0, 1)) {
            case '0':  $d = 1;  break;
            case '3':  $d = 1;  break;
            case '1':  $d = 1;  break;
            case '2':  $d = 1;  break;
            case '6':  $d = 2;  break;
            case '5':  $d = 2;  break;
            case '9':  $d = 2;  break;
            default :  $d = 0;  break;
        }
        return $d;
}
    //实盘接口心跳
    public static function heart(){
        $heart=new Heart();
        $heart->heart();
        return;
    }
    public  function precautious_line(){
        //预警线= 配资金额+保证金*比例
        //  先搜索 配资表(条件操盘中) 搜子账号ID  去持仓表 查询股票数量 如何数量是0 不继续做判断
        // 搜到的股票数量 用z_market_bat 函数，查询  股票 返回值
        if(!yan_time($last_time=15.1)){
            Log::write('非交易时间');
            return;
        }
        $borrow_arr =  Db::name('stock_borrow')->where('status','1')->select();
        if(!empty($borrow_arr)){
            foreach ($borrow_arr as $key=>$val){
                $position_stock_arr = Db::name('stock_position')->where('sub_id',$val['stock_subaccount_id'])->field('gupiao_code,stock_count')->select();
                $market_val= '';
                if(!empty($position_stock_arr)){
                    foreach ($position_stock_arr as $value){
                        $current_price = z_market($value['gupiao_code'])['current_price']; //股票实时价格
                        $market_val += $value['stock_count']*$current_price;  //最新市值
                    }
                }else{
                    continue;
                    //echo "此配资无股票持仓\n\r";
                }
                if(!empty($market_val)){
                    $subaccount_money_arr = Db::name('stock_subaccount_money')->where('stock_subaccount_id',$borrow_arr[$key]
                    ['stock_subaccount_id'])->field('avail,freeze_amount,deposit_money,borrow_money')->find();
                    $subaccount_loss_warn = Db::name('stock_subaccount_risk')->where('stock_subaccount_id',$borrow_arr[$key]
                    ['stock_subaccount_id'])->value('loss_warn');
                    $subaccount_loss_warn= sprintf("%.2f",($subaccount_loss_warn/100));
                    $loss_warn = money_convert($subaccount_money_arr['borrow_money'])+(money_convert($subaccount_money_arr['deposit_money'])*
                            $subaccount_loss_warn);
                    $now_init_amount = $market_val+money_convert($subaccount_money_arr['avail'])+money_convert($subaccount_money_arr
                        ['freeze_amount']);
                    if($now_init_amount<$loss_warn && $borrow_arr[$key]['loss_warn_sms_send']==0){
                        $content = \think\Config::get('sms_template')['stock_loss_warn'];
                        $mobile[$key] = Db::name('member')->where('id',   $borrow_arr[$key]['member_id'])->value('mobile');
                        $content =  str_replace(array("#var#","#order_id#"),array($mobile[$key],$borrow_arr[$key]['order_id']), $content);
                        $res = sendsms_mandao($mobile[$key],$content,'user');
                        if($res){
                            Db::name('stock_borrow')->where('id',$borrow_arr[$key]['id'])->setField('loss_warn_sms_send',1);
                            echo "预警线提醒短信发送成功\n\r";
                        }
                    }else{
                        if($subaccount_money_arr && $loss_warn && $now_init_amount && $borrow_arr[$key]['loss_warn_sms_send']==1){
                            Db::name('stock_borrow')->where('id',$borrow_arr[$key]['id'])->setField('loss_warn_sms_send',0);
                            echo "该配资没有低于预警线\n\r";
                        }
                    }
                }else{
                    echo "最新总市值获取失败\n\r";
                }
            }
        }else{
            echo "无配资记录\n\r";
        }
    }

}

?>