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

namespace app\money\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\money\model\Withdraw as WithdrawModel;
use think\Db;
use think\Hook; 
use think\Cache;

/**
 * 充值管理控制器
 * @package app\money\admin
 */
class Withdraw extends Admin
{
    /**
     * 充值列表
     * @author 张继立 <404851763@qq.com>
     * @return mixed
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();

        $noCheck = $this->request->get('noCheck');

        if(isset($noCheck)){
            $map['money_withdraw.status']=  $noCheck;
        }

        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = WithdrawModel::getAll($map, $order);
       // 分页数据 
        $page = $data_list->render();
        if(empty($_SERVER["QUERY_STRING"])){
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],0,-
                5)."_export";
        }else{
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"],0,-
                5)."_export?".$_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url,'','')
        ];

        return ZBuilder::make('table')
            ->setSearch(['order_no'=>'订单号', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
               
                ['order_no', '订单号'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['money', '金额'],
                ['bank', '银行信息'],
                ['status', '状态'],
                ['create_time', '申请时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])

            ->hideCheckbox()
            ->addTopButton('custem',$btn_excel)
            ->addRightButton('edit',[],['area' => ['800px', '90%'], 'title' => '提现审核']) // 批量添加右侧按钮
            ->replaceRightButton(['status' => '成功'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->addOrder('id,create_time,status,money,order_no')
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }
        public function index_export(){
        $map = $this->getMap();
        $noCheck = $this->request->get('noCheck');
        if(isset($noCheck)){
            $map['money_withdraw.status']=  $noCheck;
        }
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData =  WithdrawModel::getAll($map, $order);
     //$type_arr=['transfer'=>"线下转账","alipay"=>"支付宝"];
        foreach ($xlsData as $k=>$v){
           // $v['type']=$type_arr[$v['type']];
            // $v['status']=$status_arr[$v['status']];
            $xlsData[$k]['create_times']=date('Y-m-d H:i:s',$v["create_time"]);
        }
        $title="提现管理";
        $arrHeader = array('订单号','手机号','姓名','金额','银行信息','状态','申请时间');
        $fields = array('order_no','mobile','name','money','bank','status','create_times');
        export($arrHeader,$fields,$xlsData,$title);
    }
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null , '_close_pop');
     
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $result = $this->validate($data, 'Withdraw.update');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);
            $result_up = WithdrawModel::saveAudit();
            if(true !== $result_up){
                $this->error($result_up);   
            }else { 
                $this->success('审核处理成功', null, '_parent_reload');
            } 
            
        }
        $info = WithdrawModel::where('id', $id)->find();
        // dump($info);exit;
        $ishides ='';
        $info->money = money_convert($info->money);
        if($info->getData('status')==0){
            $status_arr = [1=>'成功', 2=>'失败'];
        }elseif($info->getData('status')==1){
            $status_arr = [3=>'退回'];
        }else{
            $status_arr = [2=>'失败'];
        }
        if($info->getData('status')==2) {
            return ZBuilder::make('form')
                ->setPageTitle('提现审核')// 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['hidden', 'id'],
                    ['static', 'order_no', '订单号'],
                    ['static', 'money', '提现金额',],
                    ['static', 'bank', '提现银行信息'],
                    ['static', 'status', '状态', '', $status_arr],
                    ['textarea', 'remark', '备注', '']
                ])
                ->hideBtn('submit')
                ->setFormData($info)// 设置表单数据
                ->fetch();
        }else{
            return ZBuilder::make('form')
                ->setPageTitle('提现审核')// 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['hidden', 'id'],
                    ['static', 'order_no', '订单号'],
                    ['static', 'money', '提现金额',],
                    ['static', 'bank', '提现银行信息'],
                    ['radio', 'status', '状态', '', $status_arr],
                    ['textarea', 'remark', '备注', '']
                ])
                ->setFormData($info)// 设置表单数据
                ->fetch();

        }
    }

}