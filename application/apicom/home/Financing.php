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

namespace app\apicom\home;
use app\stock\model\Borrow as BorrowModel;
use app\member\model\Member as MemberModel;
use app\money\model\Record as RecordModel;
use app\money\model\Money as MoneyModel;
use app\stock\model\Addfinancing as AddfinancingModel;
use app\stock\model\Addmoney as AddmoneyModel;
use app\stock\model\Renewal as RenewalModel;
use app\stock\model\SubaccountRisk;
use app\market\model\Position;
use think\db;
/**
 * 我的配资控制器
 * @package app\member\home
 */
class Financing extends Common
{

    /**
     * 初始化方法
     * @author 路人甲乙
     */
    protected function _initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            ajaxmsg('站点已经关闭，请稍后访问',0);
        }
        $req=request();
        $token = $this->request->param("token");
        defined('MID') or define('MID', isLogin($token));
        $mid = MID;
        if(!$mid) ajaxmsg('登陆后操作',0);
    }

    /**
     * 我的配资页
     * @return [type] [description]
     */
    public function index()
    {
        $mid = MID;
        if(!$mid) ajaxmsg('登陆操查看',0);
        $offset = 10;
        //$search['member_id'] = MID;
        #*-----------搜索条件-----------------
        $curl = $_SERVER['REQUEST_URI'];

        $url_arr = parse_url($curl);

        //$surl = [$url_arr];
        $surl = [];
        if(!isset($url_arr['query'])){
            $url_arr['query'] = '';
        }
        parse_str($url_arr['query'], $surl); //array获取当前链接参数

        unset($surl['page']);

        $urlArr = ['time', 'type', 'status', 'from_time', 'to_time'];
        $searchUrl=array();
        foreach ($urlArr as $v) {
            $newpars = $surl;  //用新变量避免后面的连接受影响
            unset($newpars[$v], $newpars['type_list']);   //去掉公共参数，对掉当前参数
            unset($newpars[$v], $newpars['page']);
            foreach ($newpars as $skey => $sv) {
                if ($sv == "null") unset($newpars[$skey]); //去掉"全部"状态的参数,避免地址栏全满
            }

            $newurl = http_build_query($newpars);  //生成此值的链接,生成必须是即时生成

            $searchUrl[$v]['url'] = $newurl;
            $searchUrl[$v]['cur'] = empty($_REQUEST[$v]) ? "null" : $_REQUEST[$v];
        }

        //echo "<pre>";
        //print_r($newurl);f
        #*----------------搜索条件显示----------------------------
        $searchMap['time'] = array("null" => "全部", "2" => "最近七天", "3" => "1个月", "4" => "3个月");
        $searchMap['type'] = array("null" => "全部", "2" => "免息", "3" => "按天", "4" => "按周", "5" => "按月", "6" => "体验");
        $searchMap['status'] = array("null" => "全部", "2" => "审核中", "3" => "未通过", "4" => "操盘中", "5" => "已结束","6"=>"已逾期");
        #*------------------------------------------------
        //print_r($urlArr);配资类型 0:免费配资 1:按天配资 2:按周配资 3:按月配资 4:免费体验
        //配资状态 -1：待审核  0：未通过 1：操盘中  2：已结束
        $from_time=time();
        $flag="day";
        $s_flag=0;
        foreach ($urlArr as $v) {
            //print_r($v);
            if (isset($_REQUEST[$v]) && $_REQUEST[$v] <> 'null') {
                switch ($v) {
                    case 'time':
                        $time = $_REQUEST[$v];
                        if ($time == '2') {
                            $time = time() - 604800;
                        } elseif ($time == '3') {
                            $time = time() - 2592000;
                        } elseif ($time == '4') {
                            $time = time() - 7776000;
                        }
                        $search["sb.create_time"] = array("gt", $time);
                        break;
                    case 'type':
                        $type = $_REQUEST[$v];
                        if ($type == '2') {
                            $type = 5;
                            $flag="free";
                        } elseif ($type == '3') {
                            $type = 1;
                        } elseif ($type == '4') {
                            $type = 2;
                            $flag="week";
                        } elseif ($type == '5') {
                            $type = 3;
                            $flag="month";
                        } elseif ($type == '6') {
                            $type = 4;
                        }
                        $search['type'] = $type;
                        break;
                    case 'status':
                        $s_flag=6;
                        $status = $_REQUEST[$v];
                        if ($status == '2') {
                            $status = -1;
                        } elseif ($status == '3') {
                            $status = 0;
                        } elseif ($status == '4') {
                            $status = 1;
                            $search['sb.end_time']=['>',time()];
                        } elseif ($status == '5') {
                            $status = 2;
                        }elseif ($status == '6') {
                            $status = ['in','1,3'];
                            $search['sb.end_time']=['<=',time()];
                        }
                        $search['sb.status'] = $status;


                        break;
                    case 'from_time':
                        $from_time = strtotime($_REQUEST[$v]);
                        break;
                    case 'to_time':
                        $to_time = strtotime($_REQUEST[$v]);
                        $search["sb.create_time"] = array('between', array($from_time, $to_time));
                        break;
                    default:
                        break;
                }
            }
        }

        $data_list = BorrowModel::getBorrowinfo(MID, $offset, $search);//dump($data_list);
        $data_list->each(function($item, $key){
            $item->create_time = format_date($item->create_time);
            $item->end_time = format_date($item->end_time);
            $item->verify_time = format_date($item->verify_time);
            if($item->status == -1 || $item->status == 0){
                $item->end_time = '--';
                $item->verify_time = '--';
            }
            $item->init_money = $item->init_money/100;
            $item->borrow_money = $item->borrow_money/100;
            $item->borrow_interest = $item->borrow_interest/100;
            $item->deposit_money = $item->deposit_money/100;
            $item->loss_warn_money = $item->loss_warn_money/100;
            $item->loss_close_money = $item->loss_close_money/100;
            $item->avail = $item->avail/100;

        });

        ajaxmsg('我的配资列表',1,$data_list);
    }
    //自动续期状态切换。
    public function autorenewal(){
        $mid=MID;
        $res=request();
        $id = intval($res->param('id'));//borrow_id
        $binfo=BorrowModel::getBorrowById($id);
        if(empty($binfo)||$binfo['member_id']!=$mid){
            ajaxmsg('参数错误',0);
        }
        if(in_array($binfo['type'],[4,5,6])){
            ajaxmsg('此配资类型不允许自动续期',0);
        }
        if($binfo['status']===1){
            $data=[];
            $subid=$binfo['stock_subaccount_id'];
            if($subid===0){
                ajaxmsg('系统错误',0);
            }
            $subres=SubaccountRisk::getRiskByID($subid);
            if(empty($subres)){
                ajaxmsg('系统错误',0);
            }
            if($subres&&$subres['renewal']===1){
                $data['renewal']=0;
            }
            if($subres&&$subres['renewal']===0){
                $data['renewal']=1;
            }
            $res=Db::name('stock_subaccount_risk')->where(['stock_subaccount_id'=>$subid])->update($data);
            if($res){
                $subres['renewal']=$data['renewal'];
                ajaxmsg('切换成功',1,$subres['renewal']);
            }else{
                ajaxmsg('切换失败',0,$subres['renewal']);
            }
        }else{
            ajaxmsg('配资不在操盘中，不能续期！',0);
        }
    }
    //扩大配资手续费计算
    public function calculate_rate(){

        $res=request();
        $multiple = intval($res->param('multiple'));//倍率
        $rate = $res->param('rate');//费率
        $type = intval($res->param('type'));//1、按天 2、按周 3、按月 4、免费体验 5、免费
        $deposit_money = intval($res->param('deposit_money'));//保证金
        $endTime = $res->param('endTime');//到期时间 时间戳
        if($type==1&&time()>mktime(14,45)){
            $endTime=$endTime-86400;
        }
        $fee = calculate_rate($multiple,$rate,$type,$deposit_money,$endTime);

        ajaxmsg('扩大配资手续费计算',1,$fee);
    }
    //续期手续费计算
    public function calculate_rate_renewal(){
        $res=request();
        $multiple = intval($res->param('multiple'));//倍率
        $rate = $res->param('rate');//费率
        $type = intval($res->param('type'));//1、按天 2、按周 3、按月 4、免费体验 5、免费
        $deposit_money = intval($res->param('deposit_money'));//保证金
        $cyctime = $res->param('cyctime');//期数
        switch ($type){
            case 1:
                $start=date('Y-m-d',mktime(0,0));
                $endTime=getEndDay($start, $cyctime, festival());
                $endTime=strtotime($endTime);
                if(date('N', mktime(0,0))>5){
                    $endTime=$endTime+86400;
                }
                break;
            case 2:
                $endTime=mktime(0,0)+$cyctime*3600*24*7;
                break;
            case 3:
                $endTime=mktime(0,0)+$cyctime*3600*24*30;
                break;
            default:
                jaxmsg('请求参数错误',0);
        }
        $fee = calculate_rate($multiple,$rate,$type,$deposit_money,$endTime);
        ajaxmsg('续期手续费计算',1,$fee);
    }

    /**
     * 配资详情页
     * @return [type] [description]
     */
    public function details()
    {

        $mid=MID;
        $borrow_id = $this->request->get('id');
        $page = intval($this->request->param("page"));
        $page = $page ? $page : 1;
        $offset = ($page-1) * 10;

        $result = BorrowModel::getBorrowDetail($borrow_id);
        if($result['member_id'] != $mid)  ajaxmsg('非法请求',0);
        if(empty($result)){ ajaxmsg('配资未找到',0);}
        $sub_id=$result->stock_subaccount_id;
        $count=Db::name('stock_position')->where(['sub_id'=>$sub_id])->where(['buying'=>0])->count();
        if($count>0){
            // $this->assign('tbn',"查看实盘交易");
        }else{
            //  $this->assign('tbn',"去交易");
        }
        //扩大配资
        $map["uid"]=$mid;
        $map["borrow_id"]=$borrow_id;
        $order = "id desc";
        $addfinancing= AddfinancingModel::getAddfList2($map,$order=null);
        //$addfinancing= AddfinancingModel::getAddfList2($map,$order);
        //$addfinancing=  Db::name('stock_addfinancing')->where($map)->order($order)->select();
        foreach ($addfinancing as $k=>$v){
            $addfinancing[$k]['create_time']= getTimeFormt($v['add_time'],5);
            $addfinancing[$k]['create_time_m']= getTimeFormt($v['add_time'],6);
//            $addfinancing[$k]['money']= bcdiv($v['money'],100,2);
//            if($v['status']==1) $status_d = '审核通过';
//            elseif($v['status']==2) $status_d = '审核未通过';
//            else $status_d = '待审核';
//            $addfinancing[$k]['status']= $status_d;
        }

        //追加保证金
        //$addmoney = AddmoneyModel::getAddmoneyList2($map,$order);
       // $addmoney=  Db::name('stock_addmoney')->where($map)->order($order)->select();
        $addmoney = AddmoneyModel::getAddmoneyList2($map,$order=null);
        foreach ($addmoney as $k=>$v){
            $addmoney[$k]['create_time']= getTimeFormt( $v['add_time'],5);
            $addmoney[$k]['create_time_m']= getTimeFormt( $v['add_time'],6);
//            $addmoney[$k]['money']= bcdiv($v['money'],100,2);
//            if($v['status']==1) $status_d = '审核通过';
//            elseif($v['status']==2) $status_d = '审核未通过';
//            else $status_d = '待审核';
//            $addmoney[$k]['status']= $status_d;
        }
        //申请延期
       // $renewal=  Db::name('stock_renewal')->where($map)->order($order)->select();
        $renewal = RenewalModel::getRenewalList2($map,$order=null);
        foreach ($renewal as $k=>$v){
            $renewal[$k]['create_time']= getTimeFormt($v['add_time'],5);
            $renewal[$k]['create_time_m']= getTimeFormt($v['add_time'],6);
//            $renewal[$k]['borrow_fee']= bcdiv($v['borrow_fee'],100,2);
//            //$renewal[$k]['borrow_duration']= $v['borrow_duration'].$v[''];
//            if($v['status']==1) $status_d = '审核通过';
//            elseif($v['status']==2) $status_d = '审核未通过';
//            else $status_d = '待审核';
//            $renewal[$k]['status']= $status_d;
        }

        $map2["uid"]=$mid;
        $map2["borrow_id"]=$borrow_id;
        $rev_extraction=  Db::name('stock_drawprofit')->where($map2)->order($order)->select();
        foreach ($rev_extraction as $k=>$v){
            $rev_extraction[$k]['create_time']= getTimeFormt($v['add_time'],5);
            $rev_extraction[$k]['create_time_m']= getTimeFormt($v['add_time'],6);
            $rev_extraction[$k]['money']= bcdiv($v['money'],100,2);
            //状态 0：待审核 1：审核通过 2：审核未通过
            if($v['status']==1) $status_d = '审核通过';
            elseif($v['status']==2) $status_d = '审核未通过';
            else $status_d = '待审核';
            $rev_extraction[$k]['status']= $status_d;
        }

        $begin_brow = getTimeFormt($result['create_time'],5);
        $end_brow = getTimeFormt($result['end_time'],5);
        if($result['status'] ==-1 || $result['status'] =='0'){
            $result['create_time_end'] = '-- 至 --';
        }else{
            $result['create_time_end'] = $begin_brow.'至'.$end_brow;
        }

        $result['create_time'] = getTimeFormt($result['create_time'],5);
        $result['create_time_m'] = getTimeFormt($result['create_time'],6);

        $result['end_time_st'] = $result['end_time'];
        $result['end_time'] = format_date($result['end_time']);

        $result['verify_time'] = format_date($result['verify_time']);
        $result['init_money'] = $result['init_money'] / 100;
        $result['borrow_money'] = $result['borrow_money'] / 100;
        $result['borrow_interest'] = $result['borrow_interest'] / 100;
        $result['loss_warn_money'] = $result['loss_warn_money'] / 100;
        $result['loss_close_money'] = $result['loss_close_money'] / 100;
        $result['deposit_money'] = $result['deposit_money'] / 100;
        $result['avail'] = $result['avail'] / 100;

        $data['rev_extraction'] = $rev_extraction;
        $data['addfinancing'] = $addfinancing;
        $data['addmoney'] = $addmoney;
        $data['renewal'] = $renewal;
        $data['result'] = $result;

        ajaxmsg('查看实盘交易',1,$data);
    }

    /**
     * 扩大配资页面
     * @id 配资id
     * @deposit_money 扩大配资金额
     */
    public function expend()
    {
        $mid=MID;
        $minfo = MemberModel::getMemberInfoByID($mid);
        $amoney = $minfo['account']/100;
        $this->assign('amoney',$amoney);
        $res=request();
        $id=intval($res->param('id'));
        if(empty($id)){ ajaxmsg('系统错误',0);}
        $borrow_res=BorrowModel::getBorrowById($id,1);//返回正在使用的配资详情
        //防止超过最大最小配资额
        $gs=explode('|', config('money_range'));
        if($res->isPost()){
            $now = time();
            $closeTime = mktime(14,45);
            if($now >  $closeTime){
                ajaxmsg('为了避免计息，14:45以后，不能申请，请明天再来',0);
            }
            if($borrow_res['end_time']<time()){
                ajaxmsg('该配资已过期不能扩大配资',0);
            }
            $borrow_id=intval($res->post('id'));
            $deposit_money=intval($res->post('deposit_money'));
            if($borrow_id < 1 || $deposit_money < 1){
                ajaxmsg('传送数据非法，请重新申请',0);
            }
            if($deposit_money < $gs['0']){
                $msg = '最少配资金额'.$gs['0'].'元起';
                ajaxmsg($msg,0);
            }
            if($deposit_money % $gs['2'] != 0 ) {
                $msg = '配资金额必须是'.$gs['2'].'的整数倍';
                ajaxmsg($msg,0);
            }
            $financing = Db::name("stock_addfinancing")
                ->where("uid={$mid} and status=0")
                ->where(["borrow_id"=>$borrow_id])
                ->count();
            if($financing > 0){
                ajaxmsg('您当前已有追加配资申请，不能再次申请',0);
            }

            $financing = Db::name("stock_addfinancing")
                ->where("uid={$mid} and status=0 and borrow_id={$borrow_id}")
                ->count();
            if($financing > 0){
                ajaxmsg('您当前已有追加配资申请，不能再次申请',0);
               // $this->error('您当前有追加配资申请，不能申请续期');
            }

            //对应的配资是否正常
            $bsum=Db::name("stock_borrow")
                ->where("status=1")
                ->where(['member_id'=>$mid])
                ->sum('init_money');
            if($deposit_money+$bsum/100 > (int)$gs['1']){
                $msg = '最大配资金额不能超过'.$gs['1'].'元';
                ajaxmsg('您当前申请的配资不存在，不能追加配资',0);
            }
            $binfo = BorrowModel::getBorrowById($borrow_id);
            if(!$binfo){
                ajaxmsg('您当前申请的配资不存在，不能追加配资',0);
            }
            if($binfo['type'] == 5){
                ajaxmsg('免息配资不能追加配资',0);
            }
            if($binfo['type'] == 4){
                ajaxmsg('免费体验不能追加配资',0);
            }
            if($binfo['status']<>1){
                ajaxmsg('您当前申请的配资不在操盘中，不能扩大配资',0);
            }
            //扩大配资收取的利息需要一次性付清
            // 按月配资，每月按照30天计算
            $borrow_interest = calculate_rate($binfo['multiple'],$binfo['rate'],$binfo['type'],$deposit_money,$binfo['end_time']);//利息
            $sumFee = round($deposit_money+$borrow_interest,2);//总费用
            if($sumFee > $amoney){
                $msg = "帐户金额不足，您申请追加配资所需费用为{$sumFee}元";
                ajaxmsg($msg,1);
            }
            // 启动事务
            Db::startTrans();
            //冻结金额
            $money=Db::name("money")->lock(true)->where(['mid'=>$mid])->find();
            $info = '扩大配资申请，冻结金额';
            $freeze = $money['freeze'] + $sumFee*100;//冻结总费用
            $account = $money['account'] - $sumFee*100;
            $money_ret=MoneyModel::money_freeze($mid,$freeze,$account);
            // 更新资金日志表信息
            $record = new RecordModel;
            $record_ret = $record->saveData($mid, -abs($sumFee)*100, $account, 33, $info);
            $data['uid'] = $mid;
            $data['borrow_id'] = $borrow_id;
            $data['money'] = $deposit_money*100;//转成单位为分
            $data['borrow_interest'] = $borrow_interest*100;//转成单位为分
            $data['rate'] = $binfo['rate'];
            $data['last_deposit_money'] = $binfo['deposit_money'];
            $data['last_borrow_money'] = $binfo['borrow_money'];
            $data['status'] = 0;
            $data['add_time'] = time();
            $ret = Db::name("stock_addfinancing")->insert($data);
            if($money_ret&&$record_ret&&$ret){
                /* $msg = "会员". $minfo['name'] ."申请了扩大配资";
                sendsms_to_admin($msg); */
				
                $var  =  $minfo['mobile'];
                /*$adminmsg='用户{$var}申请了扩大配资';
                $params  = $minfo['mobile'];
                send_sms_member($adminmsg,$params);*/
                $content = \think\Config::get('sms_template')['stock_expend'];
                $content = str_replace(array("#var#"),array($var), $content);
                sendsms_mandao('', $content, '');
                Db::commit();
                ajaxmsg("申请成功",1);
            }else{
                Db::rollback();
                ajaxmsg("申请失败，请联系客服",0);
            }
        }
        ajaxmsg("申请失败，请联系客服",0);
    }

    /**
     * 增加保证金页面
     * @id 配资id
     * @$money 追加保证金金额 单位元
     */
    public function addmoney()
    {
        $mid=MID;
        $minfo = MemberModel::getMemberInfoByID($mid);
        // $this->assign('amoney',$minfo['account']/100);
        $res=request();
        $id=intval($res->param('id'));
        //标目前状态
        $binfo = BorrowModel::getBorrowById($id,1);
        if(empty($binfo)){
            ajaxmsg('配资不在操盘中，不能申请追加保证金',0);
        }
        $detailinfo=BorrowModel::getBorrowDetail($id);
        $p_res=Db::name('stock_position')
            ->where(['sub_id'=>$detailinfo->stock_subaccount_id])
            ->where(['buying'=>0])
            ->select();
        $sum=0;
        if(!empty($p_res)){
            foreach ($p_res as $k=>$v){
                $lis=z_market($v['gupiao_code']);
                if(!isset($lis['current_price'])){
                    $lis['current_price']=0;
                }
                $sum+=$lis['current_price']*$v['stock_count'];
            }
        }

        if($detailinfo->avail+$sum*100>$detailinfo->loss_warn_money){
            ajaxmsg('此配资目前不需要追加保证金',0);
        }

        //$this->assign('id',$id);
        if($res->isPost()){
            $money = intval($res->post('money'));
            if($id < 1 || $money <1){
                ajaxmsg('传送数据非法，请联系客服',0);
            }
            //当前是否正在申请
            $addMoneySql = Db::name("stock_addmoney");
            $addMoneyState = $addMoneySql->where("borrow_id={$id} and uid={$mid} and status=0")->count();
            if($addMoneyState){
                ajaxmsg('您已申请追加保证金申请，正在审核中，不能重复申请。',0);
            }
            //计算费用，申请保证金不能低于操盘资金的1% 先化成元再乘以1%所以要除10000
//            $fee = $binfo['init_money']/10000;
//            if($money < $fee){
//                ajaxmsg('追加保证金金额不能低于操盘资金的1%',0);
//            }

            //余额
            $amoney = $minfo['account'];
            if($amoney < $money*100){
                $msg = "余额不足，需付{$money}元";
                ajaxmsg($msg,0);
            }
            //冻结金额，后台审核
            $info = '追加保证金申请，冻结金额';
            Db::startTrans();
            $minfo_a=Db::name("money")->lock(true)->where(['mid'=>$mid])->find();
            $freeze = $minfo_a['freeze'] + $money*100;//冻结总费用
            $account = $minfo_a['account'] - $money*100;
            $money_ret=MoneyModel::money_freeze($mid,$freeze,$account);
            // 更新资金日志表信息
            $record = new RecordModel;
            $Record_Ret = $record->saveData($mid, -abs($money)*100, $account, 33, $info);//7:追加保证金
            $user_name = $minfo['name'];
            $moneyAdd['borrow_id'] = $id;
            $moneyAdd['uid'] = $mid;
            $moneyAdd['money'] = $money*100;
            $moneyAdd['status'] = 0;
            $moneyAdd['add_time'] = time();
            $moneyAdd['verify_time'] = 0;
            $retAddMoney = $addMoneySql->insert($moneyAdd);
            if($money_ret&&$Record_Ret && $retAddMoney){
              /*  $msg = "会员".$user_name."申请了追加保证金，追加金额".$money."元";
                sendsms_to_admin($msg);*/
               /* $adminmsg='会员{$var}申请了追加保证金,追加金额{$var}元';
                $params  = $minfo['mobile'].','.$money;
                send_sms_member($adminmsg,$params);*/
                $content = \think\Config::get('sms_template')['stock_addmoney'];
                $content = str_replace(array("#var#","#amount#"),array($minfo['mobile'],$money), $content);
                sendsms_mandao('', $content, '');
                Db::commit();
                ajaxmsg('申请成功',1);
            }else{
                Db::rollback();
                ajaxmsg('申请失败，请联系客服',0);
            }
        }
        ajaxmsg('申请失败，请联系客服',0);
    }

    /**
     * 提取盈利页面
     * @id 配资id
     * @$money 提取金额 单位元
     */
    public function drawprofit()
    {
        $mid=MID;
        $minfo = MemberModel::getMemberInfoByID($mid);
        $res=request();
        $id=intval($res->param('id'));
        $stockInfo = BorrowModel::getBorrowById($id);
        $sub_money=Db::name('stock_subaccount_money')->where(['stock_subaccount_id'=> $stockInfo["stock_subaccount_id"]])->find();

        if($sub_money==null){
            $this->error('系统错误，请联系管理员');
        }
        if($stockInfo['status']<>1){
            ajaxmsg('不在操盘中，不能申请提取盈利',0);
        }
        if($stockInfo['end_time']<time()){
            ajaxmsg('配资已到期不能提取盈利',0);
        }
        //免费体验没有提取盈利
        if($stockInfo['type']==4){
            ajaxmsg('免费体验不能提取盈利',0);
        }
        $sub_money['available_amount'] = $sub_money['available_amount']>0?$sub_money['available_amount']:$sub_money['available_amount']=0;
        if($sub_money['available_amount']<0){
            $sub_money['available_amount']=0;
        }

        $borrow_id = intval($res->post('id'));
        $result = BorrowModel::getBorrowDetail($borrow_id);
        $money = floatval($res->post('money'));
        if($result['available_amount']<0){
            $this->error('收益额小于零不能提取');
        }
        if($money < 100){
            ajaxmsg('提取金额不能小于100',0);
        }
        if($money>$result['return_money']){
            $this->error('提取金额不能大于收益额');
        }
        $drawprofitInfo =  Db::name("stock_drawprofit")
            ->field(true)
            ->where("borrow_id={$borrow_id} and uid={$mid}")
            ->where(['status'=>0])
            ->find();
        if(isset($drawprofitInfo['status'])&&$drawprofitInfo['status'] == 0){
            ajaxmsg('您当前已申请过提取盈利，正在审核中，不能再次申请',0);
        }
        //客户需求一天只能提取3次盈利
        $today = arrangeTime('day');
        $start_time = $today[0];//当天0点
        $end_time = $today[1];//当天23：59：59
        $where['add_time'] = array('between',array($start_time,$end_time));
        $where['borrow_id'] = intval($borrow_id);
        $where['uid'] = intval($mid);
        $where['status'] = array('in','0,1');
        $drawprofitInfoCount =  Db::name("stock_drawprofit")
            ->where($where)
            ->count();
        if($drawprofitInfoCount>=3){
            ajaxmsg('每天最多只能提取3次盈利',0);
        }
        if(!isset($sub_money["available_amount"])){
            ajaxmsg('无可提现金额',0);
        }
        if($sub_money["available_amount"]<$money*100){
            ajaxmsg('可提现金额不足',0);
        }
        //添加申请
        $data['borrow_id'] = $borrow_id;
        $data['uid'] = $mid;
        $data['borrow_money'] = $stockInfo['init_money'];
        $data['money'] = $money*100;
        $data['status'] = 0;
        $data['add_time'] = time();
        $ret =  Db::name("stock_drawprofit")->insert($data);

        if($ret){
           /* $user = $minfo['name'];
            $msg = "会员". $user ."申请了提取收益";
            sendsms_to_admin($msg);*/
          /*  $adminmsg='会员{$var}申请了提取收益';
            $params  = $minfo['mobile'];
            send_sms_member($adminmsg,$params);*/
            $content = \think\Config::get('sms_template')['stock_drawprofit'];
            $content = str_replace(array("#var#"),array($minfo['mobile']), $content);
            sendsms_mandao('', $content, '');
            ajaxmsg('申请成功，请等待审核',1);
        }else{
            ajaxmsg('申请失败',0);
        }
    }

    /**
     * 续期页面
     * @id 配资id
     * @duration 延长时间  根据配资类型分为 天 周 和月
     */
    public function renewal()
    {
        $mid=MID;
        $minfo = MemberModel::getMemberInfoByID($mid);
        // $this->assign('amoney',$minfo['account']/100);
        $res=request();
        $id=intval($res->param('id'));
        if(empty($id)){ ajaxmsg('系统错误',0);}
        $borrow_res=BorrowModel::getBorrowById($id,1);//返回正在使用的配资详情
        //$this->assign('bres',$borrow_res);
        //$this->assign('id',$id);
        if($res->isPost()){
            $borrow_id = intval($res->post('id'));
            $borrow_duration = intval($res->post('duration'));
            if($borrow_id < 1 || $borrow_duration < 1){
                ajaxmsg('传送数据非法，请重新申请',0);
            }
            $renewal = Db::name("stock_renewal")
                ->where(["status"=>0])
                ->where(["uid"=>$mid])
                ->where(["borrow_id"=>$borrow_id])
                ->count();
            if($renewal > 0){
                ajaxmsg('您当前已有申请增加续期，不能再次申请',0);
            }

            $financing = Db::name("stock_addfinancing")
                ->where("uid={$mid} and status=0 and borrow_id={$borrow_id}")
                ->count();
            if($financing > 0){
                ajaxmsg('您当前有追加配资申请，不能申请续期',0);
                //$this->error('您当前有追加配资申请，不能申请续期');
            }

            //对应的配资是否正常
            $binfo = BorrowModel::getBorrowById($borrow_id);
            if(!$binfo){
                ajaxmsg('您当前申请的配资不存在，不能增加续期',0);
            }
            //免费配资没有增加续期
            if($binfo['type'] == 4||$binfo['type'] == 5){
                ajaxmsg('免费配资和免费体验不能增加续期',0);
            }
            if($binfo['status']<>1 && $binfo['status'] !=3){
                ajaxmsg('你当前的配资不能申请续期',0);
                //$this->error('您当前申请的配资不在操盘中，不能增加续期');
            }
            //收取利息
            $peiziMoney = $binfo['borrow_money'];
            $multiple = $binfo['multiple'];
            switch ($binfo['type']){
                case 1:
                    $rateSet=config('day_rate');
                    $rate=$rateSet[$multiple]/100;
                    break;
                case 2:
                    $rateSet=config('week_rate');
                    $rate=$rateSet[$multiple]/100;
                    break;
                case 3:
                    $rateSet=config('month_rate');
                    $rate=$rateSet[$multiple]/100;
                    break;
                default:
                    $rate=0;
                    break;
            }
            //利息 * 时间 * 配资金额
            // 延期一次性计算利息
            $borrow_fee = bcmul($peiziMoney, $rate)*$borrow_duration;
            $amoney = $minfo['account'];
            if($borrow_fee > $amoney){
                ajaxmsg('您的余额不足，不能增加续期',0);
            }
            $infomoney=$borrow_fee/100;//转化为元
            $info = "申请配资续期，冻结续期利息{$infomoney}元";
            Db::startTrans();
            $money=Db::name("money")->lock(true)->where(['mid'=>$mid])->find();
            $freeze = $money['freeze'] + $borrow_fee;//冻结总费用
            $account = $money['account'] - $borrow_fee;
            $money_ret=MoneyModel::money_freeze($mid,$freeze,$account);
            $record = new RecordModel;
            $Record_Ret = $record->saveData($mid, -abs($borrow_fee), $account, 33, $info);//23:申请配资续期

            $data['uid'] = $mid;
            $data['borrow_id'] = $borrow_id;
            $data['borrow_fee'] = $borrow_fee;
            $data['borrow_duration'] = $borrow_duration;
            $data['status'] = 0;
            $data['type'] = 1;
            $data['add_time'] = time();
            $data['verify_time'] = 0;
            $ret = Db::name("stock_renewal")->insert($data);

            if($ret && $Record_Ret&&$money_ret){
                /*$msg = "会员". $minfo['name'] ."申请了配资延期";
                sendsms_to_admin($msg);*/
                /*$adminmsg='会员{$var}申请了配资延期';
                $params  = $minfo['mobile'];
                send_sms_member($adminmsg,$params);*/

                $content = \think\Config::get('sms_template')['stock_renewal'];
                $content = str_replace(array("#var#"),array($minfo['mobile']), $content);
                sendsms_mandao('', $content, '');

                Db::commit();
                ajaxmsg('申请成功，请等待审核',1);
            }else{
                Db::rollback();
                ajaxmsg('申请失败，请联系客服',0);
            }
        }
        ajaxmsg('申请失败，请联系客服',0);
    }

    /**
     * 提前终止页面
     */
    public function stop()
    {
        $mid=MID;
        $minfo = MemberModel::getMemberInfoByID($mid);
        $res=request();
        $id=intval($res->param('id'));
        $this->assign('id',$id);
        if($res->isPost()){
            $borrow_id = intval($res->post('id'));
            if($borrow_id < 1){
                ajaxmsg('传送数据非法，请重新申请',0);
            }
            $stopfinancing = Db::name("stock_renewal")
                ->where(["status"=>0])
                ->where(["uid"=>$mid])
                ->where(["borrow_id"=>$borrow_id])
                ->where(["type"=>2])
                ->count();
            if($stopfinancing > 0){
                ajaxmsg('您已提交过提前终止申请，不能再次申请',0);
            }
            //对应的配资是否正常
            $binfo = BorrowModel::getBorrowById($borrow_id);
            if(!$binfo){
                ajaxmsg('您当前申请的配资不存在，不能申请提前终止',0);
            }
            if($binfo['status']<>1){
                ajaxmsg('您当前申请的配资不在操盘中，不能申请提前终止',0);
            }
            if($binfo['end_time']<time()){
                ajaxmsg('该配资已过期，请先续期！',0);
            }
            $Position=new Position();
            $subPosition_info=$Position->get_position($sub_id=$binfo['stock_subaccount_id']);
            if (count($subPosition_info)!=0)  ajaxmsg('该用户还有未平仓的股票，请先平仓后再申请',0);
            $interest = Db::name("stock_detail")
                ->where(["status"=>0])
                ->where(["mid"=>$mid])
                ->where(["borrow_id"=>$borrow_id])
                ->sum("interest");
            //按月配资提前终止要付剩余利息的百分比
            if($binfo['type']==3){
                $stop_fee=config('stop_fee');
                $interestMoney = $interest * $stop_fee/100;
            }else{
                $interestMoney=0;
            }
            $amoney = $minfo['account'];
            if($interestMoney > $amoney){
                $msg = "帐户全额不足，您申请终止配资所需费用为{$interestMoney}元";
                ajaxmsg($msg,0);
            }
            Db::startTrans();
            //冻结金额
            if($interestMoney){
                $info = '终止配资申请，冻结金额';
                $money=Db::name("money")->lock(true)->where(['mid'=>$mid])->find();
                $freeze = $money['freeze'] + $interestMoney*100;//冻结总费用
                $account = $money['account'] - $interestMoney*100;
                $money_ret=MoneyModel::money_freeze($mid,$freeze,$account);
                $record = new RecordModel;
                $Record_Ret = $record->saveData($mid, -abs($interestMoney)*100, $account, 33, $info);//15:终止配资
            }
            $data['uid'] = $mid;
            $data['borrow_id'] = $borrow_id;
            $data['borrow_fee'] = $interestMoney*100;
            $data['status'] = 0;
            $data['type'] = 2;
            $data['add_time'] = time();
            $data['verify_time'] = 0;
            $ret = Db::name("stock_renewal")->insert($data);
            if(isset($money_ret)&&isset($Record_Ret)){
                if($money_ret&&$Record_Ret&&$ret){
                    /*$msg = "会员". $minfo['name'] ."申请了终止配资";
                    sendsms_to_admin($msg);*/
                    /*$adminmsg='会员{$var}申请了终止配资';
                    $params  = $minfo['mobile'];
                    send_sms_member($adminmsg,$params);*/
                    $content = \think\Config::get('sms_template')['stock_stop'];
                    $content = str_replace(array("#var#"),array($minfo['mobile']), $content);
                    sendsms_mandao('', $content, '');
                    Db::commit();
                    ajaxmsg('申请成功，请等待审核',1);
                }else{
                    Db::rollback();
                    ajaxmsg('申请失败，请联系客服',0);
                }
            }else{
                if($ret){
                    /*$msg = "会员". $minfo['name'] ."申请了终止配资";
                    sendsms_to_admin($msg);*/
                    /*$adminmsg='会员{$var}申请了终止配资';
                    $params  = $minfo['mobile'];
                    send_sms_member($adminmsg,$params);*/
                    $content = \think\Config::get('sms_template')['stock_stop'];
                    $content = str_replace(array("#var#"),array($minfo['mobile']), $content);
                    sendsms_mandao('', $content, '');
                    Db::commit();
                    ajaxmsg('申请成功，请等待审核',1);
                }else{
                    Db::rollback();

                }
            }
        }
    }

    /**
     * 渲染合同
     */
    public function contract()
    {
        $mid=MID;
        $res=request();
        $id=intval($res->param('id'));
        $binfo=BorrowModel::getBorrowById($id);
        $minfo = MemberModel::getMemberInfoByID($mid);
        //按天
        $unit="";
        if ($binfo['type']==1){
            $binfo['Interest']=$binfo['borrow_duration']*$binfo['borrow_money']*$binfo['rate']/100;
            $unit="天";
        }
        //按周
        if($binfo['type']==2){
            $binfo['Interest']=$binfo['borrow_duration']*$binfo['borrow_money']*$binfo['rate']/100;
            $unit="周";
        }
        //按月
        if ($binfo['type'] == 3){
            $binfo['Interest'] = $binfo['borrow_duration']*$binfo['borrow_money']*$binfo['rate']/100;
            $unit="月";
        }
        //免费体验和免息配资
        if ($binfo['type']==4||$binfo['type']==5){
            $binfo['Interest']=0;
            $unit="天";
        }
        $info=file_get_contents(config('data_backup_path')."contract.txt");
        $arr=array(
            "name"=>config('set_site_company_name'),//甲方：委托人
            "dizhi"=>config('web_site_address'),//甲方地址
            "borrowMoney"=>$binfo['init_money']/100,//委托金额
            "borrow_duration"=>$binfo['borrow_duration'],//配资周期
            "type"=>$unit,//配资类型
            "investor"=>$binfo['Interest'] / 100/$binfo['borrow_duration'],//总利息
            "user_name"=>isset($minfo['mobile'])?$minfo['mobile']:"138********",//乙方用户名
            "real_name"=>isset($minfo['name'])?$minfo['name']:"***",//
            "idcard"=>isset($minfo['id_card'])?$minfo['id_card']:"******************",//
            "WEB_URL"=>http().$_SERVER["HTTP_HOST"],//
            "web_name"=>config('web_site_title'),//
            "rate"=>$binfo['rate'],//
            "deposit_money"=>$binfo['deposit_money']/100,//
            "add_time"=>date('Y-m-d',$binfo['verify_time']),//
            "end_time"=>date("Y-m-d",$binfo['end_time']),//
        );
        $arr['user_name']=isset($minfo['mobile'])?$minfo['mobile']:"138********";
        $arr['real_name']=isset($minfo['name'])?$minfo['name']:"***";
        $arr['idcard']=isset($minfo['id_card'])?$minfo['id_card']:"******************";
        foreach ($arr as $k =>$v){
            $info=str_replace("[".$k."]",$v,$info);
        }

        ajaxmsg('合同',1,$info);
    }
}