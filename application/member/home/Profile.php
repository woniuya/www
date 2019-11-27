<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author 伊冰华 <2851336094@qq.com>
// +----------------------------------------------------------------------
namespace app\member\home;
use app\member\model\Member;
use think\helper\Hash;
use think\Request;
use think\Db;
use think\Exception;
class Profile extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->assign('active', 'profile');
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
        $this->assign('info',$datalist);
        return $this->fetch();
    }

    /**
     * [将Base64图片转换为本地图片并保存]
     * @param  [Base64] $base64_image_content [要保存的Base64]
     * @param  [目录] $path [要保存的路径]
     */
    public function uploads(){
        $base64_image_content = $_POST['imgData'];
        $path=ROOT_PATH . 'public' . DS . 'uploads'. DS .'images';
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = $path.DIRECTORY_SEPARATOR.date('Ymd',time()).DIRECTORY_SEPARATOR;
            if(!file_exists($new_file)){
                mkdir($new_file, 0700);
            }
            $new_file = $new_file.time().".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                $newPicPathArr= explode(DIRECTORY_SEPARATOR,$new_file);
                $file_path1 = array_slice($newPicPathArr, -2, 1);
                $file_path2 = array_pop($newPicPathArr);
                $newPicPath2 = $file_path1[0].'/'.$file_path2;
                $newPicPath1 = '/uploads/images';
                $newPicPath = $newPicPath1.'/'.$newPicPath2;
                $datas['head_img']=$newPicPath;
                Db::startTrans();
                try{
                    $result =  Db::name('member')->where('id',MID)->update($datas);
                    if($result === false){
                        Db::rollback();
                        return json([ 'status' => 0, 'message' => '参数错误1']);
                    }else{
                        Db::commit();
                        return json(['data' => $newPicPath, 'status' => 1, 'message' => '图片上传成功']);
                    }
                }catch(\Exception $e){
                    Db::rollback();
                    return json([ 'status' => 0, 'message' => $e->getMessage()]);
                }
            }else{
                return json([ 'status' => 0, 'message' => '图片上传失败']);
            }
        }else{
            return json([ 'status' => 0, 'message' => '参数错误']);
        }
    }


    /*
     * 紧急联系人
     * @captcha 验证码
     * @name 紧急联系人名
     * @mobile 紧急联系人手机
     */
    public function urgent(){
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
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
                        $this->error('修改失败');
                    }
                }else{
                    $this->error('修改失败');
                }
            }else{
                $this->error('参数错误');
            }
        }
        $data=member::getMemberInfoByID($mid);
        $mobile=$data['urgent_mobile'];
        $urgent_mobile=substr($mobile,0,3)."****".substr($mobile,-4,4);
        $this->assign('mobile',$urgent_mobile);
        $this->assign('name',$data['urgent_name']);
        return $this->fetch();
    }
    //获取短信验证码
    public function  getmobliecode(){
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $post=$this->request->post();
        $phone=$post['phone'];
        if(!check_sms_code($phone)){return json(['status' => 0, 'message' => "请间隔60秒再获取验证码！"]);};
        $res=send_sms($phone,"sms_tp02");
        if($res){
            return json([['status' => 1, 'message' => "短信验证码已发送"]]);
        }else{
            return json([['status' => 0, 'message' => "短信验证码发送失败"]]);
        }
    }

    /**
     * 修改手机号码
     * @return mixed
     * @captcha 验证码
     * @mobile 新手机号
     */
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
        if(empty($mid)){
            $mid=$this->isLogin(); //判断是否登录获取MID
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
                    $this->redirect('profile/telephone', ['step' => 2]); //跳转第二步
                }else{
                    $this->error('验证码错误');
                }
            }else{
                $this->error('参数错误');
            }
        }elseif($step==2 && !empty($new_mobile)){ //第二步
            $yz = ['mobile'=>$new_mobile];
            // 验证码
            $result1 = $this->validate($yz, 'Member.editMoblie'); //验证表单
            if(!empty($result1) && $result1 != true){

                $this->error($result1);
            }else{
                $info=Db::name('member')->where(['mobile' => $new_mobile])->find();
                if(empty($info)){
                    $this->error("新手号已注册或和原手机号相同");
                }
                if (check_sms_code($new_mobile, $captcha1)) {
                    $res = Db::name('member')->where(['id' => $mid])->update(['mobile' => $new_mobile]);
                    if ($res) {
                        /*$auth = session('member_auth');
                        $auth['mobile'] = $new_mobile;
                        session('member_auth', $auth);
                        session('member_auth_sign', data_auth_sign($auth));*/
                        session(null);
                        $this->success('修改成功', 'profile/index');
                    } else {
                        $this->error('修改失败');
                    }
                }else{
                    $this->error('验证码错误');
                }
            }
        }else{
            if($step==2){
                if(empty($_SESSION['lmq_admin_']["step_key"])){
                    $this->error('原手机号码未通过验证');
                }
                unset($_SESSION['lmq_admin_']["step_key"]) ;

            }
        }

        $info=substr($old_mobile,0,3)."****".substr($old_mobile,-4,4);
        $this->assign('info',$info);
        $this->assign('old_mobile',$old_mobile);
        $this->assign('step',$step);
        return $this->fetch();
    }
    public function sendsms()
    {
        $mobile = input('mobile');
        $tp = 'code';
        if(input('captcha')){
            $captcha = ['captcha'=>input('captcha'), 'mobile'=>$mobile];
            // 验证码
            $result = $this->validate($captcha, 'Member.captcha');
            if(true !== $result){
                return ['status'=>0, 'message'=>$result];
            };
        }
        if(!check_sms_code($mobile)){return ['status' => 0, 'message' => "请间隔60秒再获取验证码！"];};
        //$res = send_sms($mobile, $template);
        $contentarr  = getconfigSms_status(['name'=>'register']);
        $content = str_replace(array("#var#"),array($mobile), $contentarr['value']);
        if($contentarr['status']==1){
            $res = sendsms_mandao($mobile,$content,$tp);
        }
        return ['status'=>$res, 'message'=>'发送失败'];
    }

    /**
     * 实名认证
     * @return mixed
     */
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
                $this->error('您已经实名认证，不能更改');
            }
            if($datalist['name']!=null&&$datalist['id_card']!=null&&$datalist['id_auth']==0){
                $this->assign("is_verified2", 1);
                $this->error('您已经提交实名认证申请，请耐心等待。');
            }
            $result = $this->validate($post, 'Member.realname');
            if(true !== $result){
                $this->error($result);
            }
            if($post['logo1']==''or $post['logo2']==''or $post['logo3']==''){
                $this->error('请全部上传身份证照片');
            }
            $name=$post['name'];
            $id_card=$post['id_card'];
            $card_pic =$post['logo1'];
            $card_pic_back =$post['logo2'];
            $card_pic_hand =$post['logo3'];

            $res=Db::name('member')->where(['id'=>$mid])->update(['name' => $name,'id_card'=>$id_card,'card_pic'=>$card_pic,'card_pic_back'=>$card_pic_back,'card_pic_hand'=>$card_pic_hand,'id_auth'=>0,'auth_time'=>time()]);
            if($res){

                $contentarr  = getconfigSms_status(['name'=>'stock_realname']);
                $content = str_replace(array("#var#"),array($datalist['mobile']), $contentarr['value']);
                if($contentarr['status']==1){
                    sendsms_mandao('',$content,'');
                }
                $this->success('上传成功，请等待审核');
            }
        }
        if($datalist['name']!=null&&$datalist['id_card']!=null&&$datalist['id_auth']==0){
            $this->assign("is_verified2", 1);
        }else{
            $this->assign("is_verified2", '');
        }
        $url=http().$_SERVER["SERVER_NAME"];
        if(!empty($datalist['card_pic'])){
            $ava_img=$url . DS . 'uploads'. DS .'images'.DS.$datalist['card_pic'];
        }else{
            $ava_img = get_thumb($mid);
        }
        $datalist['avaImg'] = $ava_img;
        $telephone=config('web_site_telephone');
        $service_time=config('web_site_service_time');
        $this->assign('info',$datalist);
        $this->assign('url',$url);
        $this->assign("is_verified", $datalist['id_auth']);
        $this->assign('service_time',$service_time);
        $this->assign('telephone',$telephone);
        return $this->fetch();
    }
    //身份证图片上传
    public function upload(){

        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $datalist=member::getMemberInfoByID($mid);
        if($datalist['id_auth']==1){
            $this->error('您已经实名认证，不能更改');
        }
        $type = request()->post("type", "");
        // return json_encode(['data' =>$type, 'code' => 1, 'message' => '图片上传成功']);
        if($type=='realnameimg1') {
            $typefield = 'card_pic';
            $file_uploads = 'file_upload';
        }elseif($type =='realnameimg2'){
            $typefield = 'card_pic_back';
            $file_uploads = 'file_upload1';
        }elseif($type=='realnameimg3'){
            $typefield = 'card_pic_hand';
            $file_uploads = 'file_upload2';
        }
        $file_path = request()->post("file_path", "");
        /*if ($file_path == "") {
            return json(['data' => '', 'code' => 0, 'message' => '文件路径不能为空']);
        }*/

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($file_uploads);
        // 移动到框架应用根目录/public/uploads/ images目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'images');
        if($info){

            // 成功上传后 获取上传信息
            $data[$typefield]=$info->getSaveName();
            /*$map['id']=$mid;
            $res=Db::name('member')->where($map)->update($data);*/
            //if($res){
            return json_encode(['data' => $info->getSaveName(), 'code' => 1, 'message' => '图片上传成功']);
            //echo $info->getSaveName();
            /*}else{
                $this->error('保存失败');
            }*/
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            //echo $info->getFilename();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
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
                $this->error("新密码和确认密码不一致~");
            }
            $newpwd = Hash::make((string)$post['newpwd']);
            if(isset($post['oldpwd'])) {
                $c = Db::name('member')->where(["id"=>$mid])->find();
                $old = $post['oldpwd'];
                if($post['oldpwd']==$post['newpwd']){
                    $this->error("新密码与老密码不能一样~!");
                }
                if(empty($c['passwd'])){
                    $newid = Db::name('member')->where(["id"=>$mid])->update(['passwd'=>$newpwd]);
                    if($newid){
                        $this->success('设置成功');
                    }
                    else $this->error("设置失败，请重试~");
                }else{
                    if(Hash::check((string)$old, $c['passwd'])){
                        $newid = Db::name('member')->where(["id"=>$mid])->update(['passwd'=>$newpwd]);
                        if($newid){

                            $this->success('设置成功',url('/member/profile/'));

                        }
                        else $this->error("设置失败，请重试~");
                    }else{
                        $this->error("老密码错误，请重试~");
                    }
                }
            }else{
                $newid = Db::name('member')->where(["id"=>$mid])->update(['passwd'=>$newpwd]);
                if($newid) {
                    $this->success('设置成功',url('/member/profile/'));
                }
                else $this->error("设置失败，请重试~",0);
            }
        }
        return $this->fetch();
    }


    /**
     * 支付密码
     */
    public function paypass()
    {
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $post=$this->request->post();
        if(!empty($post)){
            #服务端验证支付密码
            $result = $this->validate($post['newpwd'], 'Member.password');
            if($result !== true) return $this->error($result);
            if($post['subpwd']!==$post['newpwd']){
                $this->error("新密码和确认密码不一致~");
            }
            $newpwd = Hash::make((string)$post['newpwd']);
            if(isset($post['oldpwd'])) {
                $c = Db::name('member')->where(["id"=>$mid])->find();
                $old = $post['oldpwd'];
                if(Hash::check((string)$old, $newpwd)){
                    $this->error("新密码与老密码不能一样~");
                }
                if(empty($c['paywd'])){
                    $newid = Db::name('member')->where(["id"=>$mid])->update(['paywd'=>$newpwd]);
                    if($newid){
                        $this->success('设置成功',url('/member/profile/'));
                    }
                    else $this->error("设置失败，请重试~");
                }else{
                    if(Hash::check((string)$old, $c['paywd'])){
                        $newid = Db::name('member')->where(["id"=>$mid])->update(['paywd'=>$newpwd]);
                        if($newid){
                            $this->success('设置成功',url('/member/profile/'));
                        }
                        else $this->error("设置失败，请重试~");
                    }else{
                        $this->error("原支付密码错误，请重试~");
                    }
                }
            }else{
                $newid = Db::name('member')->where(["id"=>$mid])->update(['paywd'=>$newpwd]);
                if($newid) {
                    $this->success('设置成功',url('/member/profile/'));
                }
                else $this->error("设置失败，请重试~",0);
            }
        }
        return $this->fetch();
    }

    public function getpaypass()
    {
        $this->assign('member', Db::name('member')->find(MID));
        return $this->fetch();
    }

    public function resetPayPass(){
        $data = $this->request->post();
        $validate = $this->validate($data, 'Member.getpaypass');
        if($validate !== true) return $this->error($validate);

        $check_code = check_sms_code($data['mobile'], $data['sms_code']);
        if(!$check_code) return $this->error("短信验证码错误");

        $newPayPassword = Hash::make((string)$data['paypass']);
        $update = Db::name("member")->where(['id' => MID])->update(['paywd' => $newPayPassword]);
        return $update !== null ? $this->success("修改成功") : $this->error('修改失败');
    }

    public function sendPayPassMsgCode()
    {
        if(session('resetPayPassCache') && time() - session('resetPayPassCache') < 60) return $this->error('请勿重复发送');
        $mobile = $this->request->param("mobile");
        $content = \think\Config::get('sms_template')['register'];
        $content = str_replace(array("#var#"),array($mobile), $content);
        $res = sendsms_mandao($mobile,$content,'code');
        session('resetPayPassCache', time());
        return $res ? $this->success('发送成功') : $this->error("发送失败");
    }
}