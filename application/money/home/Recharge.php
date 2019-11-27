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

namespace app\money\home;
use  app\member\home\Common;
use  app\money\model\Recharge as ChargeModel;
use think\Db;
use app\money\model\Record;

/**
 * 前台首页控制器
 * @package app\money\home
 */
class Recharge extends Common
{
    protected $debaoNotice_url="";
    protected $debaoReturn_url="";
    protected $debao_url="";
    protected function _initialize()
    {
        parent::_initialize();
        $this->assign('active', 'charge');
        //debao
        $this->debao_url = "https://pay.yuanruic.com/gateway?input_charset=UTF-8"; //得宝跳转地址
        $this->debaoReturn_url = http() . $_SERVER['HTTP_HOST'] . "/money/recharge/payDebaoReturn";//返回商户前台同步
        $this->debaoNotice_url = http() . $_SERVER['HTTP_HOST'] . "/index/index/payDebaoNotice";//返回商户后台异步
    }
   /**
    * 首页
    * @return [type] [description]
    */
    public function index()
    {
    	$money = \app\money\model\Money::getMoney(MID);
    	$account=Db::name("admin_bank")->where(['status'=>1])->select();
    	$this->assign('offline',config('web_site_account'));
    	$this->assign('online_recharge_switch',config('online_recharge_switch'));
    	$this->assign('service_time',config('web_site_service_time'));
    	$this->assign('service_telephone',config('web_site_telephone'));
    	$this->assign('account',$account);
    	$this->assign('money', $money);
        return $this->fetch();
    }

    public function online() {
        $money = \app\money\model\Money::getMoney(MID);
        $this->assign('money', $money);

        return $this->fetch();
    }


    public function doCharge()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            if($data['charge_type']=="transfer"){
                $result = $this->validate($data, "Recharge.newdata");
                if(true !== $result){
                    $this->error($result);
                }
            }else{
                $result = $this->validate($data, "Recharge.online");
                if(true !== $result){
                    $this->error($result);
                }
            }

            $money = $data['money'];
            if($money <= 0){
                $this->error('充值金额必须大于0');
            }
            $data['mid'] = MID;
            $type = $data['charge_type']; // transfer 线下转账支付
            $receipt_img = input('receipt_img');
            $charge_type_id = $data['charge_type_id'];
            $transfer = $data['transfer'];
            $line_bank = '时间：'.date('Y-m-d',time());
            $order_no = ChargeModel::saveData($money, MID, $type, $line_bank,$receipt_img,$charge_type_id,$transfer);
            if($order_no){
                if($type == 'transfer'){
                    $var = Db::name('member')->where('id',MID)->value('mobile');
                    $contentarr  = getconfigSms_status(['name'=>'stock_offline']);
                    $content = str_replace(array("#var#","#amount#"),array($var,$money), $contentarr['value']);
                    if($contentarr['status']==1){
                    sendsms_mandao('', $content, '');
                    }
                    $this->success('充值已提交，请耐心等待审核', url('@member/index/index'));
                }else{
                    switch ($type){
                        case "debao":
                            $this->debao_pay($order_no,$money);
                            break;
                    }
                }
            }else{
                $this->error('充值错误');
            }

        }
    }
    //debao start
    public function debao_pay($order_no,$money){
        $merchant_code = "200009997034";
        $private_key=config('private_key');
        $service_type ="direct_pay";//固定值
        $interface_version ="V3.0";
        $sign_type ="RSA-S";
        $input_charset = "UTF-8";
        $notify_url = $this->debaoNotice_url;
        //$order_no = $order_no;
        $order_time = date( 'Y-m-d H:i:s' );
        $order_amount = number_format($money, 2, ".", "");//"0.1";//充值金额
        $product_name ="debao_pay";//商品名称
        //以下参数为可选参数，如有需要，可参考文档设定参数值
        $return_url = $this->debaoReturn_url;//同步
        $pay_type = "";//支付类型小写，多选时请用逗号隔开b2c,plateform,dcard,express,weixin,alipay,alipay_scan
        $redo_flag = ""; //是否允许重复订单
        $product_code = "";//商品编号
        $product_desc = "";//商品描述
        $product_num = "";//商品数量
        $show_url = "";//商品展示URL
        $client_ip ="";//客户端IP
        $bank_code = "";//网银直连银行代码
        $extend_param = "";//业务扩展参数
        $extra_return_param = "";//回传参数

        /////////////////////////////   参数组装  /////////////////////////////////
        /**
        除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
         */
        $signStr= "";
        if($bank_code != ""){
            $signStr = $signStr."bank_code=".$bank_code."&";
        }
        if($client_ip != ""){
            $signStr = $signStr."client_ip=".$client_ip."&";
        }
        if($extend_param != ""){
            $signStr = $signStr."extend_param=".$extend_param."&";
        }
        if($extra_return_param != ""){
            $signStr = $signStr."extra_return_param=".$extra_return_param."&";
        }
        $signStr = $signStr."input_charset=".$input_charset."&";
        $signStr = $signStr."interface_version=".$interface_version."&";
        $signStr = $signStr."merchant_code=".$merchant_code."&";
        $signStr = $signStr."notify_url=".$notify_url."&";
        $signStr = $signStr."order_amount=".$order_amount."&";
        $signStr = $signStr."order_no=".$order_no."&";
        $signStr = $signStr."order_time=".$order_time."&";

        if($pay_type != ""){
            $signStr = $signStr."pay_type=".$pay_type."&";
        }
        if($product_code != ""){
            $signStr = $signStr."product_code=".$product_code."&";
        }
        if($product_desc != ""){
            $signStr = $signStr."product_desc=".$product_desc."&";
        }

        $signStr = $signStr."product_name=".$product_name."&";
        if($product_num != ""){
            $signStr = $signStr."product_num=".$product_num."&";
        }
        if($redo_flag != ""){
            $signStr = $signStr."redo_flag=".$redo_flag."&";
        }
        if($return_url != ""){
            $signStr = $signStr."return_url=".$return_url."&";
        }
        $signStr = $signStr."service_type=".$service_type;

        if($show_url != ""){
            $signStr = $signStr."&show_url=".$show_url;
        }
        //echo $signStr."<br>";
        /////////////////////////////   获取sign值（RSA-S加密）  /////////////////////////////////
        $merchant_private_key= openssl_get_privatekey($private_key);
        openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
        $sign = base64_encode($sign_info);
        // echo $sign;

        $submitdata['sign'] = $sign;
        $submitdata['merchant_code'] = $merchant_code;
        $submitdata['bank_code'] = $bank_code;
        $submitdata['order_no'] = $order_no;
        $submitdata['order_amount'] = $order_amount;
        $submitdata['service_type'] = $service_type;
        $submitdata['input_charset'] = $input_charset;
        $submitdata['notify_url'] = $notify_url;
        $submitdata['interface_version'] = $interface_version;
        $submitdata['sign_type'] = $sign_type;
        $submitdata['order_time'] = $order_time;
        $submitdata['product_name'] = $product_name;
        $submitdata['client_ip'] = $client_ip;
        $submitdata['extend_param'] = $extend_param;
        $submitdata['extra_return_param'] = $extra_return_param;
        $submitdata['pay_type'] = $pay_type;
        $submitdata['product_code'] = $product_code;
        $submitdata['product_desc'] = $product_desc;
        $submitdata['product_num'] = $product_num;
        $submitdata['return_url'] = $return_url;
        $submitdata['show_url'] = $show_url;
        $submitdata['redo_flag'] = $redo_flag;
        $this->createf($submitdata, $this->debao_url);
    }
    //同步
    public function payDebaoReturn()
    {
        $trade_status = trim($_POST['trade_status']);
        if($trade_status == 'SUCCESS'){
            $this->success("充值完成", url('member/index'));//__APP__ . "/member/");
        }else{
            $this->error("交易失败", url('member/index'));//__APP__ . "/member/");
        }

    }





    //上传凭证图片
    public function upload(){
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        
        // 移动到框架应用根目录/public/uploads/ images目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'images');
        if($info){
            echo $info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            //echo $info->getFilename();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }



    private function createf($data, $submitUrl)
    {
        header("Content-type: text/html; charset=utf-8");
        $inputstr = "";
        foreach ($data as $key => $v) {
            $inputstr .= '<input type="hidden"  id="' . $key . '" name="' . $key . '" value="' . $v . '"/>';
        }

        $form = '<form action="' . $submitUrl . '" name="pay" id="pay" method="POST">';
        $form .= $inputstr;
        $form .= '</form>';

        $html = '<!DOCTYPE html><html lang="en"><head><title>请不要关闭页面,支付跳转中.....</title></head><body><div>';
        $html .= $form;
        $html .= '</div>';
        $html .= '<script type="text/javascript">document.getElementById("pay").submit()</script>';
        $html .= '</body></html>';
        ob_clean();
        echo $html;
        exit;
    }

}