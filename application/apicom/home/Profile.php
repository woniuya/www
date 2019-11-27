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
use  app\member\model\Member;
use think\helper\Hash;
use think\Request;
use think\Db;
class Profile extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $mid = MID;
		if(!$mid) ajaxmsg('登陆后才能操作',0);
    }
    /*
     * 个人资料
     */
    public function index()
    {
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $datalist=member::getMemberInfoByID($mid);
		ajaxmsg('个人资料信息',1,$datalist);
	
    }

    /*
     * 紧急联系人
     * @captcha 验证码
     * @name 紧急联系人名
     * @mobile 紧急联系人手机
     */
    public function urgent(){
        $mid=MID;
        if(!$mid){
            ajaxmsg('登陆后才能操作',0);
        }
        $post=$this->request->post();
        if(!empty($post)){
            $result = $this->validate($post, 'Member.urgent');
            if($result){
                $captcha=$post['captcha'];
                $name=$post['name'];
                $phone=$post['mobile'];
                if(check_sms_code($phone,$captcha)){
                    $res=Db::name('member')->where(['id'=>$mid])->update(['urgent_mobile' => $phone,'urgent_name'=>$name]);
                    if(!$res){
						ajaxmsg('修改失败',0);
                    }
                }else{
					ajaxmsg('修改失败',0);
                }
            }else{
				ajaxmsg('参数错误',0);
            }
        }
        $data=member::getMemberInfoByID($mid);
		ajaxmsg('设置成功',1,$data);
    }
    //获取短信验证码
    public function  getmobliecode(){
        $mid=MID;
        if(!$mid){
           ajaxmsg('登陆后才能操作',0);
        }
        $post=$this->request->post();
        $phone=$post['phone'];
        if(!check_sms_code($phone)){
			ajaxmsg('请间隔60秒再获取验证码！',0);
		};
        $res=send_sms($phone,"sms_tp02");
        if($res){
			ajaxmsg('短信验证码已发送',1);
        }else{
			ajaxmsg('短信验证码发送失败',0);
        }
    }

    /**
     * 修改手机号码
     * @return mixed
     * @captcha 验证码
     * @mobile 已注册手机号
     */
    public function telephone()
    {
        $mid=MID;//用户ID
        $data=member::getMemberInfoByID($mid);
        $old_mobile=$data['mobile'];//旧手机号
        if(!$mid){
             ajaxmsg('登陆后才能操作',0); //判断是否登录获取MID
        }
        $captcha1 = input('captcha'); //获取手机验证码
        $step = input('step'); //获取新手机号
        if(empty($step)){$step=1;}
        $new_mobile = input('new_mobile'); //获取新手机号
        if($captcha1 && $step==1){  //第一步
            session('step_key','lurenjiayi23101988');
            $result = $this->validate($captcha1, 'Member.captcha');//验证表单
            if($result){
                if(check_sms_code($old_mobile,$captcha1)){  //验证手机验证码
					ajaxmsg('验证通过,可进入第二部设置',1);
                }else{
					ajaxmsg('验证码错误',0);
                }
            }else{
				ajaxmsg('参数错误',0);
            }
        }elseif($step==2 && !empty($new_mobile)){ //第二步

            $yz = ['mobile'=>$new_mobile];
            // 验证码
            $result1 = $this->validate($yz, 'Member.editMoblie'); //验证表单

            if(!empty($result1) && $result1 != true){
				ajaxmsg($result1,0);
            }else{
                if (check_sms_code($new_mobile, $captcha1)) {
                    $res = Db::name('member')->where(['id' => $mid])->update(['mobile' => $new_mobile]);
                    if ($res) {
                        $auth = session('member_auth');
                        $auth['mobile'] = $new_mobile;
                        session('member_auth', $auth);
                        session('member_auth_sign', data_auth_sign($auth));
						ajaxmsg('修改成功',1);
                    } else {
						ajaxmsg('修改失败',0);
                    }
                }else{
					ajaxmsg('验证码错误',0);
                }
            }
        }
       ajaxmsg('修改成功',1);
    }
    public function sendsms()
    {
        $mobile = input('mobile');
        $tp = 'code';

        $ret=member::getMemberInfoByMobile($mobile);
        if(!empty($ret)) ajaxmsg('该手机已经注册会员，请更换手机！',0);

        if(!check_sms_code($mobile)){
            ajaxmsg('请间隔60秒再获取验证码!',0);
        }
           // $res = send_sms($mobile, $template);
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
     * 实名认证
     * @return mixed
     */
//    public function realname()
//    {
//        $mid=MID;
//        if(empty($mid)){
//            $mid=$this->isLogin();
//        }
//        $datalist=member::getMemberInfoByID($mid);
//        $post=$this->request->post();
//        if(!empty($post)){
//            if($datalist['id_auth']==1){
//				ajaxmsg('您已经实名认证，不能更改',0);
//            }
//            if($datalist['name']!=null&&$datalist['id_card']!=null&&$datalist['id_auth']==0){
//                //$this->assign("is_verified2", 1);
//                ajaxmsg('您已经提交实名认证申请，请耐心等待。',0);
////                $this->error('您已经提交实名认证申请，请耐心等待。');
//            }
//            $result = $this->validate($post, 'Member.realname');
//            $name=$post['name'];
//            $id_card=$post['id_card'];
//			if(!$name) ajaxmsg('真实姓名不能为空',0);
//			if(!$id_card) ajaxmsg('银行卡号不能为空',0);
//            $res=Db::name('member')->where(['id'=>$mid])->update(['name' => $name,'id_card'=>$id_card,'id_auth'=>0,'auth_time'=>time()]);
//            if($res){
//				ajaxmsg('上传成功，请等待审核',1);
//            }
//        }
//		ajaxmsg('提交资料不能为空',0);
//    }
    public function realname()
    {
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $datalist=member::getMemberInfoByID($mid);
        $post=$this->request->post();
        if(!empty($post)){
            if($datalist['id_auth']==1){
                ajaxmsg('您已经实名认证，不能更改',0);
            }
            if($datalist['name']!=null&&$datalist['id_card']!=null&&$datalist['id_auth']==0){
                //$this->assign("is_verified2", 1);
                ajaxmsg('您已经提交实名认证申请，请耐心等待。',0);
//                $this->error('您已经提交实名认证申请，请耐心等待。');
            }
            $result = $this->validate($post, 'Member.realname');
            $name=$post['name'];
            $id_card=$post['id_card'];
            $thumb = $post['thumb'];
            $thumb1 = $post['thumb1'];
            $thumb2 = $post['thumb2'];
            if(!$name) ajaxmsg('真实姓名不能为空',0);
            if(!$id_card) ajaxmsg('银行卡号不能为空',0);
            if(!$thumb) ajaxmsg('请上传正面身份份证图片',0);
            if(!$thumb1) ajaxmsg('请上传反面身份证图片',0);
            if(!$thumb2) ajaxmsg('请上传手持身份证图片',0);
            $res=Db::name('member')->where(['id'=>$mid])->update(['name' => $name,'id_card'=>$id_card,'id_auth'=>0,'auth_time'=>time()]);
            if($res){
                ajaxmsg('上传成功，请等待审核',1);
            }
        }
        ajaxmsg('提交资料不能为空',0);
    }
    //身份证图片上传
    public function upload(){
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $datalist=member::getMemberInfoByID($mid);
        if($datalist['id_auth']==1){
			ajaxmsg('您已经实名认证，不能更改',0);
        }
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ images目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'images');
        if($info){
            // 成功上传后 获取上传信息
            $data['card_pic']=$info->getSaveName();
            $map['id']=$mid;
            $res=Db::name('member')->where($map)->update($data);
            if($res){
                $info->getSaveName();
				ajaxmsg('上传成功',1);
            }else{
				ajaxmsg('保存失败',0);
            }

        }else{
			ajaxmsg($file->getError(),0);
		
        }
}

    /**
     * 修改密码
     */
    public function password()
    {
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $post=$this->request->post();
        if(!empty($post)){
            #服务端验证密码
            $result = $this->validate($post['newpwd'], 'Member.password');
            if($post['subpwd']!==$post['newpwd']){
				ajaxmsg('新密码和确认密码不一致',0);
            }
            $newpwd = Hash::make((string)$post['newpwd']);
            if(isset($post['oldpwd'])) {
                $c = Db::name('member')->where(["id"=>$mid])->find();
                $old = $post['oldpwd'];
                if($post['oldpwd']==$post['newpwd']){
					ajaxmsg('新密码与老密码不能一样',0);
                }
                if(empty($c['passwd'])){
                    $newid = Db::name('member')->where(["id"=>$mid])->update(['passwd'=>$newpwd]);
                    if($newid){
						ajaxmsg('设置成功',1);
                    }
					else ajaxmsg('设置失败，请重试',0);
                }else{
                    if(Hash::check((string)$old, $c['passwd'])){
                        $newid = Db::name('member')->where(["id"=>$mid])->update(['passwd'=>$newpwd]);
                        if($newid){
							ajaxmsg('设置成功',1);
                        }
                        ajaxmsg('设置失败，请重试',0);
                    }else{
						ajaxmsg('原支付密码错误，请重试',0);
                    }
                }
            }else{
                $newid = Db::name('member')->where(["id"=>$mid])->update(['passwd'=>$newpwd]);
                if($newid) {
					ajaxmsg('设置成功',1);
                }
               else ajaxmsg('设置失败，请重试',0);
            }
        }else{
			ajaxmsg('设置失败，请重试',0);
		}
    }

    /**
     * 支付密码
     */
    public function paypass()
    {
        $mid=MID;
        if(empty($mid)){
           ajaxmsg('登陆后才能进行操作',0);
        }
        $post=$this->request->post();
        if(!empty($post)){
            #服务端验证支付密码
            $result = $this->validate($post['newpwd'], 'Member.password');
            if($post['subpwd']!==$post['newpwd']){
				ajaxmsg('新密码和确认密码不一致',0);
            }
            $newpwd = Hash::make((string)$post['newpwd']);
            if(isset($post['oldpwd'])) {
                $c = Db::name('member')->where(["id"=>$mid])->find();
                $old = $post['oldpwd'];
                if(Hash::check((string)$old, $newpwd)){
					ajaxmsg('新密码与老密码不能一样',0);
                }
                if(empty($c['paywd'])){
                        $newid = Db::name('member')->where(["id"=>$mid])->update(['paywd'=>$newpwd]);
                        if($newid){
                           ajaxmsg('设置成功',1);
                        }
                        else ajaxmsg('设置失败，请重试',0);
                }else{
                    if(Hash::check((string)$old, $c['paywd'])){
                        $newid = Db::name('member')->where(["id"=>$mid])->update(['paywd'=>$newpwd]);
                        if($newid){
							ajaxmsg('设置成功',1);
                        }
                        else ajaxmsg('设置失败，请重试',0);
                    }else{
						ajaxmsg('原支付密码错误，请重试',0);
                    }
                }
            }else{
                $newid = Db::name('member')->where(["id"=>$mid])->update(['paywd'=>$newpwd]);
                if($newid) {
					ajaxmsg('设置成功',1);
                }
				else ajaxmsg('设置失败，请重试',0);
            }
        }else{
			ajaxmsg('设置失败，请重试',0);
		}
    }
    /*
     * 头像
     */
//    public function uploadImg(){
//
//        $mid=MID;
//        if(empty($mid)){
//            ajaxmsg('登陆后才能操作',0);
//        }
//
//        // 获取表单上传文件 例如上传了001.jpg
//        $file = request()->file('image');
//        // 移动到框架应用根目录/public/uploads/ images目录下
//        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'images');
//        if($info){
//            // 成功上传后 获取上传信息
//            $saveFile =$info->getSaveName();
//            $newPicPath1 = '/uploads/images';
//            $saveFile = $newPicPath1.'/'.$saveFile;
//            $newid = Db::name('member')->where(["id"=>$mid])->update(['head_img'=>$saveFile]);
//            $saveFile = str_replace("\\",'/',$saveFile);
//            $filePath = 'http://'.$_SERVER['HTTP_HOST'].'/'.$saveFile;
//            if($newid){
//                ajaxmsg('上传成功',1,$filePath);
//            }else{
//                ajaxmsg('保存失败',0);
//            }
//        }else{
//            ajaxmsg($file->getError(),0);
//        }
//    }
    public function uploadImg(){

        $mid=MID;
        if(empty($mid)){
            ajaxmsg('登陆后才能操作',0);
        }

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        $type = request()->param('type');
        // 移动到框架应用根目录/public/uploads/ images目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'images');
        if($info){
            // 成功上传后 获取上传信息
            $saveFile =$info->getSaveName();
            $newPicPath1 = '/uploads/images';
            $saveFile = $newPicPath1.'/'.$saveFile;
            if(!$type){
                $newid = Db::name('member')->where(["id"=>$mid])->update(['head_img'=>$saveFile]);
            }
            $saveFile = str_replace("\\",'/',$saveFile);
            $filePath = 'http://'.$_SERVER['HTTP_HOST'].'/'.$saveFile;
            if($type){
                if($filePath) ajaxmsg('上传成功',1,$filePath);
                else ajaxmsg('保存失败',0);
            }else{
                if($newid){
                    ajaxmsg('上传成功',1,$filePath);
                }else{
                    ajaxmsg('保存失败',0);
                }
            }

        }else{
            ajaxmsg($file->getError(),0);
        }
    }

    /*
     * END
     */

}