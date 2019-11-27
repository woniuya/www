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

namespace app\member\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\member\model\Member as MemberModel;
use think\Db;
use think\Hook; 
use think\Cache;

/**
 * 会员管理控制器
 * @package app\member\admin
 */
class Identity extends Admin
{
    /**
     * 
     * @author 张继立 <404851763@qq.com>
     * @return mixed
     */
    public function index()
    {

        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        if(empty($map)){

            $map['id_card']=['<>',''];
            $map['name']=['<>',''];

        }
        $order = $this->getOrder(); 
        empty($order) && $order = 'auth_time desc';
        // 数据列表
        $data_list = MemberModel::where($map)->order($order)->paginate();
        // 分页数据
        $page = $data_list->render();

        if(empty($_SERVER["QUERY_STRING"])){
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],0,-5)."_export";
        }else{
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"],0,-5)."_export?".$_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url,'','')
        ];

        return ZBuilder::make('table')
            ->setSearch(['name' => '姓名', 'id_card' => '身份证']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['id_card', '身份证号'],
                ['id_auth', '账户状态', [0=>'处理中', 1=>'通过', 2=>'错误']],
                ['auth_time', '实名申请时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])

            ->hideCheckbox()
            ->setTableName('member')
            ->addTopButton('custem',$btn_excel)
            ->addRightButtons('edit',[], true) // 批量添加右侧按钮
            ->addOrder('id,id_auth,reg_time')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    public function index_export(){
        // 获取查询条件
        $map = $this->getMap();

        if(empty($map)){
            $map['id_auth']=0;
            $map['id_card']=['<>',''];
            $map['name']=['<>',''];

        }

        $order = $this->getOrder();

        empty($order) && $order = 'auth_time desc';

        $xlsData = MemberModel::where($map)->order($order)->paginate();

        $id_auth_arr=['0'=>'处理中','1'=>'通过','2'=>'错误'];

           foreach ($xlsData as $k=>$v){
               $v['id_auth']=$id_auth_arr[$v['id_auth']];
               $v['auth_time']=date('Y-m-d H:i',$v['auth_time']);
           }

        $title="实名认证";
        $arrHeader = array('ID','手机号','姓名','身份证号','账户状态','实名申请时间');
        $fields = array('id','mobile','name','id_card','id_auth','auth_time');
        export($arrHeader,$fields,$xlsData,$title);

    }

    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
//            // 验证
//            $result = $this->validate($data, 'Member.update_card');
//             //验证失败 输出错误信息
//            if(true !== $result) $this->error($result);
            if($data["id_auth"]==0){
                $data["name"]=null;
                $data["id_card"]=null;
                $data["card_pic"]=null;
                $data["card_pic_back"]=null;
                $data["card_pic_hand"]=null;
            }
            if (MemberModel::update($data)) {
                $member = MemberModel::get($data['id']);
                Hook::listen('member_id_auth', $member);
                // 记录行为
                action_log('member_id_auth', 'member', $member['id'], UID, $member['mobile']);

                //身份认证通过-短信通知
                if($data["id_auth"]==1){
                    //发送短信验证码
/*                  $content = \think\Config::get('sms_template')['stock_realname_pass'];
                    $content = str_replace(array("#var#"),array($mobile), $content);
                    sendsms_mandao($mobile,$content,'user');*/

                    $contentarr  = getconfigSms_status(['name'=>'stock_realname_pass']);
                    $content = str_replace(array("#var#"),array($data['mobile']), $contentarr['value']);

                    if($contentarr['status']==1){
                        sendsms_mandao($data['mobile'],$content,'user');
                    }
                }else{
                    $contentarr  = getconfigSms_status(['name'=>'stock_realname_fail']);
                    $content = str_replace(array("#var#"),array($data['mobile']), $contentarr['value']);
                    if($contentarr['status']==1){
                        sendsms_mandao($data['mobile'],$content,'user');
                    }

                }

                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info = MemberModel::where('id', $id)->field('password', true)->find();
        if($info['card_pic']){
        $info['card_pic_hidden'] = '/uploads/images/'.$info['card_pic'];
        }else{
            $info['card_pic_hidden']='';
        }
        if($info['card_pic_back']) {
            $info['card_pic_back_hidden'] = '/uploads/images/' . $info['card_pic_back'];
        }else{
            $info['card_pic_back_hidden']='';
        }
        if($info['card_pic_hand']) {
            $info['card_pic_hand_hidden'] = '/uploads/images/' . $info['card_pic_hand'];
        }else{
            $info['card_pic_hand_hidden']='';
        }
        //$info['url']='/uploads/images/'.$info['card_pic'];
        /*$addr=$_SERVER["DOCUMENT_ROOT"].'/uploads/images/'.$info['card_pic'];
        if(!empty($info["card_pic"])&&file_exists($addr)){
            $myurl='<div class="form-group col-md-12 col-xs-12 " id="form_group_id_card"><label class="col-xs-12" for="id_card">身份证号</label><div class="col-sm-12"><div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="'. PUBLIC_PATH .'uploads/images/'.$info['card_pic'].'"data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="'.PUBLIC_PATH .'uploads/images/'.$info['card_pic'].'"></a></div></div></div>';
           // $myurl = '<div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="'. PUBLIC_PATH .'uploads/images/'.$info['card_pic'].'"data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="'.PUBLIC_PATH .'uploads/images/'.$info['card_pic'].'"></a></div>';
        }else{
            $myurl ='<span></span>';
        }*/
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['hidden', 'mobile', '用户名/手机号',''],
                ['static', 'mobile', '用户名/手机号',''],
                ['static', 'id_card', '身份证号'],
                ['hidden', 'id_card', '身份证号'],
                ['static', 'name', '姓名', ''],
                ['hidden', 'name', '姓名', ''],
                ['hidden', 'card_pic_hidden', '身份证正面照', ''],
                ['hidden', 'card_pic_back_hidden', '身份证背面照', ''],
                ['hidden', 'card_pic_hand_hidden', '手持身份证照', ''],
                ['radio', 'id_auth', '账户状态', '', ['拒绝','通过']]
            ])
            ->setExtraHtml($myurl, 'form_top')
            ->js('realname')
            ->setFormData($info) // 设置表单数据
            ->fetch();   
    }
    
}