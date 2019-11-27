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
class Index extends Admin
{
    /**
     * 首页
     * @author 张继立 <404851763@qq.com>
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = MemberModel::where($map)
            ->order($order)
            ->paginate();
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
        $btn_resetpayword = [
            'title' => '重置支付密码',
            'icon'  => 'fa fa-fw fa-key',
            'class' => 'btn btn-xs btn-default ajax-get confirm',
            'href'  => url('reset_payword', ['id' => '__id__'])
        ];
        return ZBuilder::make('table')
            ->setSearch(['name' => '姓名', 'id_card' => '身份证','mobile'=>'手机号']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['id_card', '身份证号'],
                ['create_time', '注册时间', 'datetime'],
                //['pid', '代理商', ''],
                ['status', '登录状态', 'switch'],
                ['is_del', '账户状态', [0=>'正常', 1=>'注销/删除']],
                ['right_button', '操作', 'btn']
            ])
            ->setTableName('member')
            ->addTopButtons('enable,disable,delete') // 批量添加顶部按钮
            ->addTopButton('custem',$btn_excel)
            ->addRightButtons(['edit', 'delete']) // 批量添加右侧按钮
            ->addRightButton('custom', $btn_resetpayword)
            ->addOrder('id,id_auth,reg_time,status,is_del')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }
    public function index_export(){
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'create_time,id desc';
        $xlsData = MemberModel::where($map)
            ->order($order)
            ->paginate();
        $is_del_arr = [0=>'正常', 1=>'注销/删除'];
          foreach ($xlsData as $k=>$v){
               $v['is_del'] = $is_del_arr[$v['is_del']];
               $v['create_time']=date('Y-m-d H:i',$v['create_time']);
           }
        $title="会员列表";
        $arrHeader = array('ID','手机号','姓名','身份证号','注册时间','代理商','登录状态','账户状态');
        $fields = array('id','mobile','name','id_card','create_time','pid','status','is_del');
        export($arrHeader,$fields,$xlsData,$title);
    }
    public function  reset_payword(){

        $id   = $this->request->isPost() ? input('post.id/a') : input('param.id');
        $member_mobile = MemberModel::where('id',$id)->value('mobile');
        $payword1 = substr($member_mobile,-6);
        $payword2 = MemberModel::setPaywdAttr($payword1);
        $result = Db('member')->where('mobile',$member_mobile)->setField('paywd', $payword2);
        if (false !== $result) {
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
        
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //$data['pid']=($data['pid']==="0")?null:$data['pid'];
            $data['id_card']=($data['id_card']==="")?null:$data['id_card'];
            $data['name']=($data['name']==="")?null:$data['name'];
            // 验证
            $result = $this->validate($data, 'Member.update');
            $members_card = MemberModel::where(['id'=>$data['id']])->value('id_card');
            if($data['id_card']!=$members_card){

                $memberhasid_card = MemberModel::where(['id_card'=>$data['id_card']])->value('id_card');

                if($memberhasid_card){
                    $this->error('身份证已存在');
                }
              if(!preg_match("/^[1-9][0-9]{5}(19|20)[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|31)|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}([0-9]|x|X)$/",$data['id_card'])){
                  $this->error('身份证格式不对');
              }
            }
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);
			
            // 如果没有填写密码，则不更新密码
            if ($data['passwd'] == '') {
                unset($data['passwd']);
            }
            if (empty($data['paywd'])) {
                unset($data['paywd']);
            }
			
           /**	
			//设置代理 不设置rate 默认后台配置rate
			 if ($data['agent_rate'] == 0) {
                $data['agent_rate'] = config('agent_back_rate');
            }
			//判断是否符合设置代理情况 已经是下级代理 则不能设置一级代理 
			 $user = MemberModel::get($data['id']);
				if(!$user['agent_id'] && !$user['agent_far']){ //需要修改注册位置 注册添加返佣期限限制 邀请用户填充上级代理
					 $this->error('该用户不能设置为搭理');
				}
			 $data['agent_time'] = 0;//取消返佣期限限制
			 **/
            if (MemberModel::update($data)) { 
                $member = MemberModel::get($data['id']);
                Hook::listen('member_edit', $member);
                // 记录行为
                action_log('member_edit', 'member', $member['id'], UID, $data['mobile']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info = MemberModel::where('id', $id)->find();
       /* $agent_res=Db::name("admin_user")->where(['status'=>1])->where('role','in',[2,3,4])->select();
        foreach ($agent_res as $k=>$v){
            $agent_list[$v['id']]=$v['username'];
        }*/
        switch ($info['agent_id'])
        {
            case 0:
                $agent_status = '普通用户';
            break;
            case 1:
                $agent_status = '一级代理商';
            break;
            case 2:
                $agent_status = '二级代理商';
                break;
            default:
                $agent_status = '三级代理商';
        }
        $info['agent_id'] = $agent_status;
        unset($info['passwd'], $info['paywd']);
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['text', 'mobile', '用户名/手机号', ''],
                ['text', 'id_card', '身份证号'],
                ['text', 'name', '姓名', ''],
                ['password', 'passwd', '登录密码', '不修改请留空。修改时必填，6-20位'],
                //['text', 'paywd', '支付密码', '不修改请留空。修改时必填，6-20位'],
                ['radio', 'status', '登录状态', '', ['禁用', '启用']],
                ['radio', 'is_del', '账户状态', '', ['正常', '注销/删除']],
                ['static', 'agent_id', '代理商状态', ''],
            ])
          //  ->addSelect('pid', '选择代理商', '请选择代理商', $agent_list, $info['pid'])
            ->setFormData($info) // 设置表单数据
            ->fetch();   
    }
    
    public function delete($record = [])
    {
        
        $table_name     = input('param.table');

        $ids   = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $ids   = (array)$ids;
        $field = input('param.field', 'is_del');
        $member_mobile = MemberModel::where('id', 'in', $ids)->column('mobile');

        $pk = Db('member')->getPk();
        $map[$pk] = ['in', $ids];
        $result = Db('member')->where($map)->setField($field, 1);

        if (false !== $result) {
            Cache::clear();
            // 记录行为日志
            if (!empty($member_mobile)) { 
                call_user_func_array('action_log', ['member_delete', 'member', $ids, UID, implode('、', $member_mobile)]);
            }
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }

    }
    
    public function enable($record = [])
    {
        return $this->setStatus('enable');    
    }
    
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }
    
    /**
     * 设置用户状态：禁用、启用
     * @param string $type 类型：enable/disable
     * @param array $record
     * @author 路人甲乙 <4853332099@qq.com>
     * @return mixed
     */
    public function setStatus($type = '', $record = [])
    {
        $table_name     = input('param.table');
        $ids            = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $member_id    = is_array($ids) ? '' : $ids;
        $member_status = MemberModel::where('id', 'in', $ids)->column('status');
        return parent::setStatus($type, ['member_'.$type, 'member', $member_id, UID, implode('、', $member_status)]);
    }
    
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $table    = input('post.table', ''); 
        $status  = MemberModel::where('id', $id)->value($field);
       // $status = Db::name('member')->where('id', $id)->value($field); 
        $details = '字段(' . $field . ')，原值(' . $status . ')，新值：(' . $value . ')';
        return parent::quickEdit(['member_edit', 'member', $id, UID, $details]);
    }
}