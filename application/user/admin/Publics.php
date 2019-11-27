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

namespace app\user\admin;

use app\common\controller\Common;
use app\user\model\User as UserModel;
use think\Hook;

/**
 * 用户公开控制器，不经过权限认证
 * @package app\user\admin
 */
class Publics extends Common
{
    /**
     * 用户登录
     * @author 路人甲乙
     * @return mixed
     */
    public function signin()
    {
        if ($this->request->isPost()) {

            // 获取post数据
            $data       = $this->request->post();
            $rememberme = isset($data['remember-me']) ? true : false;

            // 登录钩子
            $hook_result = Hook::listen('signin', $data);
            if (!empty($hook_result) && true !== $hook_result[0]) {
                $this->error($hook_result[0]);
            }

            // 验证数据
            $result = $this->validate($data, 'User.signin');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result);
            }
            // 验证码
            if (config('captcha_signin')) {
                $captcha = $this->request->post('captcha', '');
                $captcha == '' && $this->error('请输入验证码');
                if (!captcha_check($captcha, '', config('captcha'))) {
                    //验证失败
                    //$this->error('验证码错误或失效');
                };
            }
//            $smscode=$this->request->post('smscode', '');
            //            $uname=$this->request->post('username', '');
            //            $phone=Db::name('admin_user')->where(['username'=>$uname])->value('mobile');
            //            if(empty($smscode)){
            //                // 验证码
            //                if (config('captcha_signin')) {
            //                    $captcha = $this->request->post('captcha', '');
            //                    $captcha == '' && $this->error('请输入验证码');
            //                    if(!captcha_check($captcha, '', config('captcha'))){
            //                        //验证失败
            //                        $this->error('验证码错误或失效');
            //                    };
            //                }
            //
            //                if(empty($phone)){$this->error("该用户没有填写手机号！");}
            //                if(!check_sms_code($phone)){$this->error("请间隔60秒再获取验证码！");};
            //                $res=send_sms($phone,"sms_tp02");
            //                if($res){
            //                    $this->success("短信验证码已发送");
            //                }else{
            //                    $this->error("短信验证码发送失败");
            //                }
            //            }else{
            //                if(!check_sms_code($phone,$smscode)){
            //                    $this->error('短信验证码错误或失效');
            //                };
            //            }

            // 登录
            $UserModel = new UserModel;
            $uid       = $UserModel->login($data['username'], $data['password'], $rememberme);
            if ($uid) {
                // 记录行为
                action_log('user_signin', 'admin_user', $uid, $uid);
                $this->success('登录成功', url('admin/index/index'));
            } else {
                $this->error($UserModel->getError());
            }
        } else {
            $hook_result = Hook::listen('signin_sso');
            if (!empty($hook_result) && true !== $hook_result[0]) {
                if (isset($hook_result[0]['url'])) {
                    $this->redirect($hook_result[0]['url']);
                }
                if (isset($hook_result[0]['error'])) {
                    $this->error($hook_result[0]['error']);
                }
            }

            if (is_signin()) {
                $this->redirect('admin/index/index');
            } else {
                return $this->fetch();
            }
        }
    }

    /**
     * 退出登录
     * @author 路人甲乙
     */
    public function signout()
    {
        $hook_result = Hook::listen('signout_sso');
        if (!empty($hook_result) && true !== $hook_result[0]) {
            if (isset($hook_result[0]['url'])) {
                $this->redirect($hook_result[0]['url']);
            }
            if (isset($hook_result[0]['error'])) {
                $this->error($hook_result[0]['error']);
            }
        }

        session(null);
        cookie('uid', null);
        cookie('signin_token', null);

        $this->redirect('signin');
    }
}
