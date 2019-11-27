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
use app\common\controller\Common;
use app\money\model\Money;
use app\member\model\Member as MemberModel;
use app\member\model\Bank as BankModel;
use think\Db;
use think\request;
use app\apicom\model\JWT;

class Member extends Common
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

        if(!MID) ajaxmsg('登陆后才能操作',0);

    }
    //用户中心首页数据接口 登录后获取的用户信息 未登录初始化 返回 data = 0
    public function index()
    {
        $money = Money::getMoney(MID);

        $money['account'] = bcdiv($money['account'],100,2);
        $money['freeze'] = bcdiv($money['freeze'],100,2);
        $money['operate_account'] = bcdiv($money['operate_account'],100,2);
        $money['bond_account'] = bcdiv($money['bond_account'],100,2);
        $money['total'] = bcdiv($money['total'],100,2);

        $data['money'] = $money;

        $msg_num = Db('member_message')->where(['mid'=>MID,'status'=>0])->count();
        $mobile =  Db('member')->alias('m')->field('id,mobile,agent_id,head_img')->where(['id'=>MID,'status'=>1])->find();
        $agent_num = Db('member')->where(['agent_far'=>MID,'status'=>1])->count();

        $other['msg_num'] = $msg_num;
        $other['mobile'] = $mobile['mobile'];
        $other['agent_id'] = $mobile['agent_id'];
        $other['link_m'] = $agent_num;

        if($mobile['head_img']){
            $saveFile = str_replace("'\'",'/',$mobile['head_img']);
            $other['head_img'] =  'http://'.$_SERVER['HTTP_HOST'].$saveFile;
        }else{
            $other['head_img'] = '';
        }

        $token = array(
            "uid"=>$mobile['id'],
            'doHost'=>$_SERVER['HTTP_HOST'],
            'doTime'=>time(),
            "mobile" => $mobile['mobile'],
        );
        $jwt = JWT::encode($token, JWT_TOKEN_KEY);
        $other['token'] = $jwt;
        $data['info'] = $other;
        ajaxmsg('登陆信息',1,$data);
    }
    public function userInfo(){

        $mid=MID;
        $minfo = MemberModel::getMemberInfoByID($mid);
        unset($minfo['passwd']);
        unset($minfo['paywd']);
        if($minfo['id_card'] ==null) $minfo['id_card'] = '';
        if($minfo['name'] ==null) $minfo['name'] = '';
        $minfo['account'] =  bcdiv($minfo['account'],100,2);
        $minfo['freeze'] =  bcdiv($minfo['freeze'],100,2);
        $minfo['operate_account'] =  bcdiv($minfo['operate_account'],100,2);
        $minfo['bond_account'] = bcdiv($minfo['bond_account'],100,2);
        if($minfo['head_img']){
            $saveFile = str_replace("\\",'/',$minfo['head_img']);
            $minfo['head_img'] =  'http://'.$_SERVER['HTTP_HOST'].$saveFile;
        }else{
            $minfo['head_img'] = '';
        }

        $token = array(
            "uid"=>$minfo['id'],
            'doHost'=>$_SERVER['HTTP_HOST'],
            'doTime'=>time(),
            "mobile" => $minfo['mobile'],
        );
        $jwt = JWT::encode($token, JWT_TOKEN_KEY);
        $minfo['token'] = $jwt;
        ajaxmsg('用户信息',1,$minfo);
    }
    /**省市调用**/
    public  function getArea()
    {
        $reid = $this->request->param("reid");
        $area = get_area($reid);
        ajaxmsg('银行卡信息',1,$area);
    }
    /**
     * 用户银行卡操作
     */
    public function bankInfo(){

        $bank = config('web_bank');
        $rate_val = array();
        foreach ($bank as $k => $v){
            $arr = array(
                'id'=>$k,
                'name'=> preg_replace('/\|img/', '',$v),
            );
            array_push($rate_val,$arr);
        }
        $data['bank'] = $rate_val;
        $data['banks'] = Db('member_bank')->where(['mid'=>MID])->select();

        ajaxmsg('银行卡信息',1,$data);
    }
    /***
     *添加银行卡
     **/
    public function addBank()
    {
        $name = Db('member')->where(['id'=>MID])->value('name');
        if($name===null){
            ajaxmsg('您还没有实名认证',0);
        }
        $data = $this->request->post();
        $data['mid'] = MID;

        $res = BankModel::saveData($data);
        if($res['status']==1){
            ajaxmsg('添加成功',1);
        }else{
            ajaxmsg($res['message'],0);
        }
    }
    /*
     * 删除银行卡
     * id 要删除的银行卡id
     * mid 用户id
     */
    public function delBank(){
        $id = $this->request->param("id");
        $mid = $this->request->param("mid");
        if(MID){
            $res=BankModel::del_bank($id);
            if(!$res){
                ajaxmsg('删除失败',0);
            }
        }else{
            ajaxmsg('您要删除的银行卡不在您名下',0);
        }
        ajaxmsg('删除成功',1);
    }
    /*
     * 编辑银行卡
     * id 要编辑的银行卡id
     * mid 用户id
     */
    public function editBank(){
        $id=$this->request->param("id");
        $res=[];
        $res=BankModel::bankInfo($id);
        if(!$res){
            ajaxmsg('编辑失败',0);
        }elseif($res['mid']!==MID){
            ajaxmsg('您要编辑的银行卡不在您名下',0);
        }
        $name = Db('member')->where(['id'=>MID])->value('name');
        $data['name'] = $name;
        $data['bank_id'] = $id;
        $data['web_bank'] = config('web_bank');
        $data['bankinfo'] = $res;

        ajaxmsg('编辑信息',1,$data);
    }
    /*
     * 保存编辑数据
     */
    public function doEdit(){
        $data = $this->request->post();
        $mid = MID;
        $data['mid'] = $mid;
        $res = BankModel::upEdit($data);
        if($res['status']==1){
            ajaxmsg($res['message'],1);
        }else{
            ajaxmsg($res['message'],0);
        }
    }

}