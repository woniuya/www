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

namespace app\stock\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use think\db;
use app\market\model\Position;

/**
 * 证券账户默认控制器
 * @package app\stock\admin
 */
class Bonus extends Admin
{
    /*
     * 当日除权除息股票公告
     */
    public function index()
    {
        $info=substr(qq_bonus(),13);
        $info=json_decode($info,true);
        $stock_record=$info['data']['076015']['data'];//登记日
        $ex_dividend=$info['data']['076016']['data'];//除权日
        if(!empty($stock_record)){
            foreach ($stock_record as $k=>$v){
                $stock_record[$k]['type']=1;//登记
            }
        }
        if(!empty($ex_dividend)){
            $stock_record=$stock_record==null?[]:$stock_record;
            foreach ($ex_dividend as $k=>$v){
                $v['type']=2;//除权除息
                array_push($stock_record,$v);
            }
        }
        $btn_detail = [
            'title' => '符合条件股票详情',
            'icon'  => 'fa fa-fw fa-search',
            'href'  => url('/stock/bonus/detail', ['code' => '__zqdm__','type'=>'__type__'])
        ];
        // 显示添加页面
        return ZBuilder::make('table')
            ->addColumns([
                ['zqdm', '股票代码'],
                ['zqjc', '股票名称'],
                ['zl', '类型'],
                ['sj', '内容'],
                ['date', '日期'],
                ['right_button', '操作', 'btn'],
            ])
            ->addRightButton('detail', $btn_detail) // 强制卖出
            ->setRowList($stock_record) // 设置表格数据
            ->setPageTitle("今日公告")
            ->fetch();
    }
    //命令行下返回参数
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
    /*
     * 判断是否为A股股票
     */
    public static function market_type($code){
        switch (substr($code,0,1)){
            case '0':return 1;break;
            case '3':return 1;break;
            case '6':return 2;break;
            default: return 5;break;//5、出错
        }
    }
    public function sell_surplus($code,$count,$broker,$price=0,$model=2){
        if(get_spapi()=='0'){
            $this->error("交易服务未启动，请启动交易服务！");
        }
        $etype=self::market_type($code);
        $p_data=[];
        if(self::config('web_real_api')==1) {
            $p_data=gs('queryTradeData',[$broker,2]);
        }
        if(self::config('web_real_api')==2){
            $p_data =Grid::grid_category_stock($broker);
        }

        if(!$p_data){
            Db::rollback();
            return ['status'=>0, 'message'=>'交易接口错误，无法成交'];
        }
        unset($p_data[0]);
        $p_res=null;
        foreach ($p_data as $k=>$v){
            if($v[0]==$code){
                $p_res=$p_data[$k];
                break;
            }
        }
        if(empty($p_res)){
            Db::rollback();
            return ['status'=>0, 'message'=>'不存在的股票持仓!'];
        }
        if($p_res[4]<$count){
            Db::rollback();
            return ['status'=>0, 'message'=>'可卖股票数量不足!'];
        }
        $residue=$count%100;
        if($residue>0){
            $count=$count-$residue;
        }
        $trade_id=$broker;
        if(self::config('web_real_api')==1) {
            $otype=2;//1买入 2卖出
            if($model==1){
                $ptype = 1;//1、限价委托
            }else{
                $ptype = 5;//5市价委托(上海五档即成剩撤/ 深圳五档即成剩撤)
            }
            $data = gs('commitOrder', [$trade_id, $code, $count, $etype, $otype, $ptype, $price]);
        }
        if(self::config('web_real_api')==2){
            $otype=1;//0->买入 1->卖出 2->融资买入 3->融券卖出 4->买券还券 5->卖券还款 6->现券还券 7->担保品买入 8->担保品卖出
            if($model==1){
                $ptype = 0;//0、限价委托
            }else{
                $ptype = 4;//0->上海限价委托 深圳限价委托1->市价委托（深圳对方最优价格）2->市价委托（深圳本方最优价格）3->市价委托（深圳即时成交剩余撤销）
                //4->市价委托（上海五档即成剩撤 深圳五档即成剩撤）5->市价委托（深圳全额成交或撤销）6->市价委托（上海五档即成转限价）
            }
            $data=Grid::grid_order($otype,$ptype,$code,$price,$count,$trade_id);
        }

        if(isset($data['error'])){
            Db::rollback();
            return ['status'=>0, 'message'=>$data['error']];
        }
        if(isset($data['ErrInfo'])){
            Db::rollback();
            return ['status'=>0, 'message'=>$data['ErrInfo']];
        }
    }
    /*
     * 零散股票管理
     * 除权除息造成的零散股票
     */
    public function surplus(){
        $map = $this->getMap();
        $list_rows1 = input('list_rows');
        $listRows = isset($list_rows1)?$list_rows1:20;
        // 获取排序
        $order = $this->getOrder();
        if(empty($order)){
            $order='p.id desc';
        }
        $res=Db::view('stock_bonus_surplus p',true)
            ->view('stock_subaccount s','sub_account','s.id=p.sub_id','left')
            ->where($map)
            ->where(['p.buying'=>0])
            ->order($order)
            ->paginate($listRows);
        $page = $res->render();
        $btn_close = [
            'title' => '卖出此零散股票',
            'icon'  => 'fa fa-fw fa-trash-o',
            'class' => 'btn btn-xs btn-default ajax-get confirm',
            'data-title' => '真的要卖出此股票吗？',
            'data-tips' => '请确认零散股票已集齐！',
            'href'  => url('sell_surplus', ['code'=>'__gupiao_code__','count'=>'__stock_count__','broker' => '__lid__',])
        ];
        //$code,$count,$broker,$price=0,$model=2
        return ZBuilder::make('table')
            ->setPageTitle('零散股票管理') // 设置页面标题
            //->addTopButton('custem',$btn_excel)
            ->setTableName('stock_position') // 设置数据表名
            ->addOrder('sub_id,gupiao_code,gupiao_name') // 添加排序
            ->addFilter('gupiao_code,soruce') // 添加筛选
            ->addRightButton('close', $btn_close) // 强制卖出
            ->setSearch([ 'sub_account' => '子账户']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['sub_account', '来源子账户'],
                ['lid', '交易账户名'],
                ['gupiao_code', '证券代码'],
                ['gupiao_name', '证券名称'],
                ['stock_count', '持仓数量'],
                ['buy_average_price', '买入均价'],
                ['now_price', '当前价'],
                ['market_value', '最新市值'],
                ['ck_profit', '参考浮动盈亏'],
                ['profit_rate', '盈亏比例'],
                ['jiyisuo', '交易所'],
                ['right_button', '操作', 'btn'],
            ])

            ->hideCheckbox()
            ->setRowList($res) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    /*
     * 待除权除息股票
     *
     */
    public function will(){
        $date_type="登记日";
        $res=Db::name('stock_bonus_position')
            ->where(['status'=>1])
            ->select();
        foreach ($res as $k=>$v){
            $ret=Db::view("stock_subaccount s",'sub_account,uid')
                ->view('member m','mobile,name','m.id=s.uid','left')
                ->where(['s.id'=>$v['sub_id']])
                ->find();//dump($ret);
            if(!empty($ret)){
                $res[$k]['mobile']=$ret['mobile'];
                $res[$k]['sub_account']=$ret['sub_account'];
                $res[$k]['uname']=$ret["name"];
                $res[$k]['zqjc']=$v['name'];
                $res[$k]['Iratio']=$v['date'];
            }
        }
        //halt($res);
        $title="待除权除息股票";
        return ZBuilder::make('table')
            ->addColumns([
                ['mobile', '手机号'],
                ['uname', '姓名'],
                ['sub_account', '子账号'],
                ['stock_count', '股票数量'],
                ['code', '股票代码'],
                ['zqjc', '股票简称'],
                ['bonus', '10股分红(元)'],
                ['song', '10股送股(股)'],
                ['zuan', '10股转股(股)'],
                ['Iratio', $date_type],
            ])
            ->setRowList($res) // 设置表格数据
            ->assign('empty_tips', '没有待除权除息的股票')
            ->setPageTitle($title)
            ->fetch();
    }
    /*
     * 历史除权除息记录
     */
    public function history(){
        $res=Db::name('stock_bonus_dividend')
            ->where('addtime','<',time())
            ->where('addtime','>',mktime(0,0,0)-86400*30)
            ->paginate();
        $title="近期除权除息记录";
        return ZBuilder::make('table')
            ->addColumns([
                ['mobile', '手机号'],
                ['uname', '姓名'],
                ['sub_account', '子账号'],
                ['stock_count', '股票数量'],
                ['code', '股票代码'],
                ['name', '股票简称'],
                ['bonus', '10股分红(元)'],
                ['song', '10股送股(股)'],
                ['zuan', '10股转股(股)'],
                ['date', '除权日'],
            ])
            ->setRowList($res) // 设置表格数据
            ->assign('empty_tips', '没有已除权除息的股票')
            ->setPageTitle($title)
            ->fetch();

    }
    /*
     * 当日公告对应股票持仓详情
     */
    public function detail(){
        $re=$this->request;
        $code=$re->param('code');
        $type=$re->param('type');//1、登记日2、除权除息
        $info=substr(qq_bonus(),13);
        $info=json_decode($info,true);
        $stock_record=$info['data']['076015']['data'];//登记日
        $ex_dividend=$info['data']['076016']['data'];//除权日
        $rul='((\\-|\\+)?\\d+(\\.\\d+)?)';
        switch ($type){
            case 1:
                $res=Db::view('stock_position p',true)
                    ->view("stock_subaccount s",'sub_account,uid','s.id=p.sub_id','left')
                    ->view("member m",'mobile,name','m.id=s.uid','left')
                    ->where(['p.gupiao_code'=>$code])
                    ->where(['p.buying'=>0])
                    ->select();
                $date_type="登记日";
                $ret=[];
                foreach ($stock_record as $kk=>$vv){
                    if($vv['zqdm']==$code){
                        $ret=Handle($vv,0,$rul);
                        break;
                    }
                }
                foreach ($res as $k=>$v){
                    $res[$k]['stock_count']=$v['stock_count'];
                    $res[$k]['sub_account']=$v['sub_account'];
                    $res[$k]['mobile']=$v['mobile'];
                    $res[$k]['uname']=$v['name'];
                    $res[$k]['code']=$v['gupiao_code'];
                    $res[$k]['zqjc']=$v['gupiao_name'];
                    $res[$k]['bonus']=$ret[0]['bonus'];
                    $res[$k]['song']=$ret[0]['song'];
                    $res[$k]['zuan']=$ret[0]['zuan'];
                    $res[$k]['Iratio']=date('Y-m-d',time());
                }
                $title="与代码".$code."对应的可登记股票详情";
                break;
            case 2:
                $res=Db::name('stock_bonus_position')
                    ->where(['code'=>$code])
                    ->select();
                $date_type="登记日";
                foreach ($res as $k=>$v){
                    $ret=Db::view("stock_subaccount s",'sub_account,uid')
                        ->view('member m','mobile,name','m.id=s.uid','left')
                        ->where(['s.id'=>$v['sub_id']])
                        ->find();
                    if(!empty($ret)){
                        $res[$k]['mobile']=$ret['mobile'];
                        $res[$k]['sub_account']=$ret['sub_account'];
                        $res[$k]['uname']=$ret['name'];
                        $res[$k]['zqjc']=$v['name'];
                        $res[$k]['Iratio']=$v['date'];
                    }
                }
                $title="与代码".$code."对应的除权详情";
                break;
            default:
                $res=[];
                break;
        }
        return ZBuilder::make('table')
            ->addColumns([
                ['mobile', '手机号'],
                ['uname', '姓名'],
                ['sub_account', '子账号'],
                ['stock_count', '股票数量'],
                ['code', '股票代码'],
                ['zqjc', '股票简称'],
                ['bonus', '10股分红(元)'],
                ['song', '10股送股(股)'],
                ['zuan', '10股转股(股)'],
                ['Iratio', $date_type],
                //['date', '派现额度'],
                //['Stockname', '股本基准日'],
            ])
            ->setRowList($res) // 设置表格数据
            ->assign('empty_tips', '没有与此代码对应的股票持仓')
            ->setPageTitle($title)
            ->fetch();

    }

}
