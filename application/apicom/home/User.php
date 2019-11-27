<?php
namespace app\apicom\home;
use app\common\controller\Common;
use app\member\model\Member as MemberModel;
use app\member\model\Invite as InviteModel;
use think\Db;
use util\Tree;
use think\request;
use app\apicom\model\JWT;

class User extends Common
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
        //define('MID', 123456);
        // 用户是否登录
        //ajaxmsg('用户登录状态',1,is_member_signin());
    }

    public function login()
    {
        if(!module_config("member.member_is_login")){
            ajaxmsg('系统关闭了登录',0);
        }

        if(MID) ajaxmsg('已经登录态',0);

        if ($this->request->isPost()) {
            // 获取post数据
            $data = $this->request->post();
            // 登录
            $MemberModel = new MemberModel;
            $mid = $MemberModel->login($data['mobile'], $data['password']);
            if ($mid) {
                $token = array(
                    "uid"=>$mid,
                    'doHost'=>$_SERVER['HTTP_HOST'],
                    'doTime'=>time(),
                    "mobile" => $data['mobile'],
                );
                $jwt = JWT::encode($token, JWT_TOKEN_KEY);
                $datas['token'] = $jwt;
                $datas['mobile'] = $data['mobile'];
                $datas['uid'] = $mid;
                ajaxmsg('登录成功',1,$datas);
            } else {
                $data = array();
                ajaxmsg($MemberModel->getError(),0,$data);
            }
        } else {

            if (MID) {
                ajaxmsg('已经是登陆态',0);
            } else {
                ajaxmsg('登陆异常',0);
            }
        }
    }
    //退出
    public function signout()
    {
        if($this->request->isAjax()) {
            $data = $this->request->post();
            if($data['action']=='signout'){
                session(null);
                ajaxmsg('退出成功',0);
            }
        }
    }

    /**
     * 注册获取短信 返回 短信信息token
     * 注册
     */
    public function register()
    {
        if(!module_config("member.member_is_register")){
            ajaxmsg('暂不开放注册',0);
        }
        $req=request();
        $tid_mobile=$req::instance()->param('recommend');
        $tid=substr($req::instance()->param('recom_id'),2,-3);
        if(!empty($tid_mobile)) {
            $tid_mobiles=MemberModel::getMemberInfoByMobile($tid_mobile);
           if(!empty($tid_mobiles)) $tid = $tid_mobiles['id'];
        }
        $tid = intval($tid);

        if($tid){
            $tlist=MemberModel::getMemberInfoByID($tid);//邀请人 信息
            if($tlist['mobile']){
                $recommend=$tlist['mobile'];
            }
        }

        if($this->request->isPost()){
            $data = $this->request->post();
            $check_code = check_sms_code($data['mobile'], $data['sms_code']);

            $mobile = trim($data['mobile']);//注册手机
            $isExists = Db::name('member')->where('mobile',$mobile)->count();
            if($isExists){
                ajaxmsg('该手机号已经注册',0);
            }

            //临时关闭验证短信
            true !== $check_code && ajaxmsg('验证码错误',0);
            $result = $this->validate($data, 'Member.create');
            true !== $result  && ajaxmsg($result,0);

            if($tid){
                if ($recommend == $data['mobile']) {
                    ajaxmsg('邀请人不能是自己',0);
                }
                //$r_res=MemberModel::getMemberInfoByID($tid);
                if(!$tlist['id']) ajaxmsg('请确认邀请人是否存在',0);
                $data['agent_far'] = $tlist['id'];
            }else{
                $data['agent_far'] = 0;
            }
            $res = MemberModel::saveData($data);
            if($tid){
                $m_res=MemberModel::getMemberInfoByMobile($data['mobile']);
                $data_r['id']=$m_res['id'];//被邀请人id
                $data_r['mid']=$tlist['id'];//邀请人id
                $Inv_res=InviteModel::saveData($data_r);//保存推荐关系数据
            }
            if(1 === $res['status']){
                $token = array(
                    "uid"=>$res['data']['id'],
                    'doHost'=>$_SERVER['HTTP_HOST'],
                    'doTime'=>time(),
                    "mobile" => $res['data']['mobile'],
                );
                $jwt = JWT::encode($token, JWT_TOKEN_KEY);
                $datas['token'] = $jwt;
                $datas['mobile'] = $res['data']['mobile'];
                $datas['uid'] = $res['data']['id'];
                ajaxmsg($res['message'],1,$datas);
            }else{
                ajaxmsg($res['message'],0);
            }
        }else{
            ajaxmsg('注册操作失败',0);
        }
    }
    /***
     ** 发送短信验证码
     ***/
    public function sendsms()
    {
        $mobile = input('mobile');
        $phonecode = input('phonecode');
        $data['mobile'] = $mobile;
        $data['captcha'] = $phonecode;
        $result = $this->validate($data, 'Member.reg');
        if(true !== $result){
            // 验证失败 输出错误信息
            ajaxmsg($result,0);
        }
        $tp = 'code';

        $ret=MemberModel::getMemberInfoByMobile($mobile);
        if(!empty($ret)) ajaxmsg('该手机已经注册会员，请更换手机！',0);

        if(input('captcha')){
            $captcha = ['captcha'=>input('captcha'), 'mobile'=>$mobile];
            // 验证码
            $result = $this->validate($captcha, 'Member.captcha');
            if(true !== $result){
                ajaxmsg($result,0);
            };
        }
        if(!check_sms_code($mobile)){
            ajaxmsg('请间隔60秒再获取验证码！',0);
        }

        //$res = send_sms($mobile, $template);
		$content = \think\Config::get('sms_template')['register'];
            $content = str_replace(array("#var#"),array($mobile), $content);
            $res = sendsms_mandao($mobile,$content,$tp);
        if($res){
            ajaxmsg('发送成功',1);
        }else{
            ajaxmsg('发送失败',0);
        }
    }

    /**
     * 密码找回验证码发送
     * @return array
     */
    public function sendPassSms()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            $mobile = $data['mobile'];
            $tp = 'code';
            // $captcha = $data['captcha'];

            //验证该手机号是否存在
            $isExists = Db::name('member')->where('mobile',$mobile)->value('id');

            //如果找回密码所填写的手机号不存在
            if(is_null($isExists)){
                ajaxmsg('该手机号不存在',0);
            }

            if(!check_sms_code($mobile)){
                ajaxmsg('请间隔60秒再获取验证码！',0);
            }

            //发送短信验证码
            //$res = send_sms($mobile, $template);
			$content = \think\Config::get('sms_template')['register'];
            $content = str_replace(array("#var#"),array($mobile), $content);
            $res = sendsms_mandao($mobile,$content,$tp);
            if($res){
                ajaxmsg('短信发送成功！',1);
            }else{
                ajaxmsg('短信发送失败！',0);
            }

        }else{
            ajaxmsg('需要提交数据',0);
        }
    }
    /**
     * 设置新密码
     */
    public function newpass()
    {

        if($this->request->isPost()){
            $data = $this->request->post();

          $mobile =$data['mobile'];
            if($mobile ==''){
                ajaxmsg('请求参数错误', 0);
            }

            $pwd = $data['password'];
            if($pwd ==''){
                ajaxmsg('请求参数错误', 0);
            }
            $MemberModel = new MemberModel;

            $where['mobile'] = $mobile;
            $result = $MemberModel::update(['passwd' => $pwd],$where);

            if($result!==false){
                ajaxmsg('密码重置成功！',1);
            }else{
                ajaxmsg('密码重置失败！请重试',0);
            }

        }else{
            ajaxmsg('需要提交数据',0);
        }
    }

    /**
     * 忘记登录密码 重置--下一步
     */
    public function next()
    {
        if($this->request->isPost()) {
            $data = $this->request->post();
            $mobile = $data['mobile'];
            $sms_code = $data['sms_code'];
            $check_code = check_sms_code($mobile, $sms_code);
            //验证短信
            if(true != $check_code){
                ajaxmsg('短信验证码错误或失效',0);
            }
            //session('sms_code',$sms_code );
            $_SESSION['sms_code']=$sms_code;
            // session::set('mobile',$mobile);
            $_SESSION['mobile']=$mobile;

            ajaxmsg('通过进入下一步',1);
        }else{
            ajaxmsg('请求参数错误',0);
        }
    }

}