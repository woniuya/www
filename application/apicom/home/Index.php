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
use think\Db;
use app\apicom\model\JWT;
use think\Request;

/**
 * 前台首页控制器
 */
class Index extends Common
{
    /**
     * 首页
     * @author 路人甲乙
     * @return mixed
     */
    protected function _initialize()
    {
		/*
			$token = array(
                     "uid"=>7,
                     "mobile" => '13853050743',
                    'doHost'=>$_SERVER['HTTP_HOST'],
                    'doTime'=>time(),
                 );
                 $jwt = JWT::encode($token, JWT_TOKEN_KEY);
                 $token = $jwt;
				 */
/**
                 $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjg5LCJkb0hvc3QiOiJ3d3cucHo4MS5jb20iLCJkb1RpbWUiOjE1MzQzMTY2MDIsIm1vYmlsZSI6IjE1MDk4MzU2MTk2In0.CDxMZ0cdTKs3RG2lOSvbYhSYqTqX-RsN_9aaQuZxmUk';
                 $decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256'));
				 print_r($decoded);
				 die;
*/

        parent::_initialize();
    }

    public function index()
    {
        ajaxmsg("请求路径错误",0);
    }

    public function getSlider(){

        $equipment = $this->request->param("equipment");
        $map['ifapp'] =0;
        if($equipment ==2) $map['ifapp'] =1;
        $map['status'] =1;
        $map['ifwap'] =1;
        $banner = Db::name('cms_slider')->where($map)->select();
        foreach ($banner as $key=>$val){
            $banner[$key]['img_url'] = 'http://'.$_SERVER['HTTP_HOST'].get_files_path($val['cover']);
        }
        ajaxmsg('获取成功',1,$banner);
    }
    public function getconf(){

        $trialSetting = explode('|', config('trial_set'));
        $data['money'] = $trialSetting[1];
        $data['deposit'] = $trialSetting[0];
        $data['duration'] = $trialSetting[2];
        $data['DivideInto'] =explode('|', config('free_set'))[2].'%';;
        $data['iftrade'] = yan_time();
        $data['kftime'] = config('web_site_service_time');
        $data['kfphone'] = config('web_site_telephone');
        $mid  =MID;
        if($mid){
            $msg_num = Db('member_message')->where(['mid'=>MID,'status'=>0])->count();
            $data['msg_num'] = $msg_num;
        }else{
            $data['msg_num'] = 0;
        }
        ajaxmsg('获取成功',1,$data);
    }
    /****获得最新行情**/
    public function sinasssj(){
        ajaxmsg('接口已弃用',0);
        $gupiao_sz = sina_sssj_a('sh000001');
        $gupiao_shenz = sina_sssj_a('399001');
        $gupiao_cy = sina_sssj_a('399006');
        $data['shangz'] = $gupiao_sz;
        $data['shenz'] = $gupiao_shenz;
        $data['chuangy'] = $gupiao_cy;

        ajaxmsg('最新行情',1,$data);
    }
    /*
     * 二维码
     */
    public function qrcode(){
       ajaxmsg('接口已弃用',0);
    }
    /*
     * app升级操作
     */
    public function upgrade(){
        $app_open = config('app_open');
        $data['app_open'] = $app_open;
        $data['app_name'] =config('app_name');
        $data['app_down_android'] = config('app_down_android');
        $data['power_android'] = config('power_android');
        $data['version_androd_name'] =config('version_androd_name');
        $data['version_androd_code'] = config('version_androd_code');
        $data['description_android'] = config('description_android');
        $data['down_ios'] = config('down_ios');
        $data['power_ios'] = config('power_ios');
        $data['version_ios'] = config('version_ios');
        $data['description_ios'] =config('description_ios');
        if ($app_open){
            ajaxmsg('app信息',1,$data);
        }else{
            ajaxmsg('后台暂停APP升级',0);
        }
    }
    /*
     * app wap 单页
     */
    public function getWapPage(){
        $data['regwap'] = http().$_SERVER["HTTP_HOST"].'/cms/document/detail/id/31.html?app=1';
        $data['protocol'] = http().$_SERVER["HTTP_HOST"].'/cms/document/detail/id/31.html?app=1';
        ajaxmsg('获取成功',1,$data);
    }
}