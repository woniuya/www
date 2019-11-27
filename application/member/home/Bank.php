<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 张继立 <404851763@qq.com>
// +----------------------------------------------------------------------

namespace app\member\home;

use app\member\model\Bank as BankModel;
/**
 * 前台首页控制器
 * @package app\member\home
 */
class Bank extends Common
{

    protected function _initialize()
    {
        parent::_initialize();

        $this->assign('active', 'bank');
    }

    /**
    * 首页
    * @return [type] [description]
    */
    public function index()
    {	
        $banks = BankModel::getBank(MID);
        $bank_logo = $this->bankres('logo');
        foreach ($banks as $key=>$val){
            $banks[$key]['logo']= $bank_logo[$val['bank']];
        }
        $this->assign('banks', $banks);
        $this->assign("mid", MID);
        return $this->fetch(); // 渲染模板
    }

    public function add()
    {
        $name = Db('member')->where(['id'=>MID])->where(['id_auth'=>1])->value('name');
        if($name===null){
            $this->error("您还没有实名认证或认证还没通过审核",url("profile/realname"));
        }
        $this->assign('name', $name);
        $result = $this->bankres();
        $this->assign('web_bank', $result);

        return $this->fetch();
    }

    public function doAddBank()
    {
        $data = $this->request->post();
        $check_code = check_sms_code($data['mobile'], $data['sms_code']);
        true !== $check_code &&  $this->error("验证码错误");
        $result = $this->validate($data, "Bank.create");
        if(true !== $result){
            $this->error($result);
        }
        $data['mid'] = MID;
        $res = BankModel::saveData($data);
        if($res['status']==1){
            $this->success($res['message'], URL('/member/bank'));
        }else{
            $this->error($res['message']);
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
        if(isset($mid)&&$mid = MID){
            $res=BankModel::del_bank($id);
            if(!$res){
                $this->error("删除失败");
            }
        }else{
            $this->error("您要删除的银行卡不在您名下");
        }
        $this->success("删除成功");
    }
    /*
     * 编辑银行卡
     * id 要编辑的银行卡id
     * mid 用户id
     */
    public function editbank(){
        $id=$this->request->param("id");
        $res=[];
        $res=BankModel::bankInfo($id);
        //dump($res);exit;
        if(!$res){
            $this->error("编辑失败");
        }elseif($res['mid']!==MID){
            $this->error("您要编辑的银行卡不在您名下");
        }
        $result = $this->bankres();
        $name = Db('member')->where(['id'=>MID])->value('name');
        $this->assign('name', $name);
        $this->assign('bank_id', $id);
        $this->assign('web_bank', $result);
        $this->assign('bankinfo',$res);
        return $this->fetch();
    }
    /*
     * 保存编辑数据
     */
    public function doEdit(){
        $data = $this->request->post();
        $check_code = check_sms_code($data['mobile'], $data['sms_code']);
        true !== $check_code &&  $this->error("验证码错误");
        $result = $this->validate($data, "Bank.create");
        if(true !== $result){
            $this->error($result);
        }
        $data['mid'] = MID;
        $res = BankModel::upEdit($data);
        if($res['status']==1){
            $this->success($res['message'], URL('/member/bank'));
        }else{
            $this->error($res['message']);
        }
    }
    public function sendsms()
    {
        $mobile = input('mobile');
        $tp = 'code';
        if(input('mobile')){
            $captcha = ['mobile'=>$mobile];
            // 验证码
            $result = $this->validate($captcha, 'Bank.sms');
            if(true !== $result){
                return ['status'=>0, 'message'=>$result];
            };
        }

        if(!check_sms_code($mobile)){
            return ['status' => 0, 'message' => "请间隔60秒再获取验证码！"];
        }else{
            //发送短信验证码
           // $res = send_sms($mobile, $template);
            $content = \think\Config::get('sms_template')['register'];
            $content = str_replace(array("#var#"),array($mobile), $content);
            $res = sendsms_mandao($mobile,$content,$tp);
            if($res!==true){
                return ['status'=>0, 'message'=>'短信发送失败！'];
            }else{
                return ['status'=>1, 'message'=>'短信发送成功！'];
            }

        }
    }
        //前端下拉菜单用到的
        public function  bankres($type=''){
            $data_list = Db('admin_config')->where('name', 'web_bank')->value('value');
            //值转数组
            $array = preg_split('/[,;\r\n]+/', trim($data_list, ",;\r\n"));
            if (strpos($data_list, '|')) {
                $res1 = array();
                $is = 0;
                foreach ($array as $key => $val) {
                    $res1[$is] = explode('|', $array[$key]);
                    foreach ($res1[$is] as $key1 => $val1) {
                        list($k, $v) = explode(':', $val1);
                        $res1[$is][$k] = $v;
                        unset($res1[$is][$key1]);
                    }
                    $is++;
                }
            }
            $result = array();
            if($type==''){
                foreach ($res1 as $k => $v) {
                    foreach ($v as $y=>$z){
                        $result[$y] = $z;
                        unset($result['img']);
                    }
                }

            }else{

                foreach ($res1 as $k => $v) {
                    foreach ($v as $y=>$z){
                        $result[$y] = $v['img'];
                        unset($result['img']);
                    }
                }

            }

          //  dump($result);exit;
            return $result;



    }

}