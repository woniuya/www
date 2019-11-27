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
use app\money\model\Recharge as RechargeModel;
use think\Db;
use think\Hook; 
use think\Cache;

/**
 * 充值管理控制器
 * @package app\money\admin
 */
class Recharge extends Admin
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
        $order = $this->getOrder(); 
        empty($order) && $order = 'id desc';
        // 数据列表
        $data_list = RechargeModel::getAll($map, $order);
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
            ->setSearch(['mid'=>'用户ID', 'member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['order_no', '订单号'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['money', '金额'],
                ['status', '状态'],
                ['type', '充值方式',['transfer'=>"线下转账","alipay"=>"支付宝"]],
                ['create_time', '充值时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->hideCheckbox()
            ->addTopButton('custem',$btn_excel)
            ->addRightButton('edit',[],['area' => ['800px', '90%'], 'title' => '充值审核']) // 批量添加右侧按钮
            ->replaceRightButton(['status' => '成功'], '<button class="btn btn-danger btn-xs" type="button" disabled>不可操作</button>')
            ->addOrder('id,create_time,status,money,order_no')
            ->setRowList($data_list)
            ->fetch(); // 渲染模板
    }
    public function index_export(){
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData =  RechargeModel::getAll($map, $order);

        $type_arr=['transfer'=>"线下转账","alipay"=>"支付宝"];
           foreach ($xlsData as $k=>$v){
               $v['type']=$type_arr[$v['type']];
              // $v['status']=$status_arr[$v['status']];
               $xlsData[$k]['create_times']=date('Y-m-d H:i:s',$v["create_time"]);
           }
        $title="充值管理";
        $arrHeader = array('ID','订单号','手机号','姓名','金额','状态','充值方式','充值时间');
        $fields = array('id','order_no','mobile','name','money','status','type','create_times');
        export($arrHeader,$fields,$xlsData,$title);
    }
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null , '_close_pop');
     
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $result = $this->validate($data, 'Recharge.update');
            // 验证失败 输出错误信息
            if(true !== $result) $this->error($result);
            $result_up = RechargeModel::saveAudit();
            if(true !== $result_up){
                $this->error($result_up);   
            }else { 
                $this->success('编辑成功', null, '_parent_reload');
            } 
            
        }
        $info = RechargeModel::where('id', $id)->find();
        if($info->getData('status')!==0){
            $this->error('此状态不允许编辑', null, '_close_pop');
        }
        $info->money = money_convert($info->money);
        $arr=['transfer'=>"线下充值","alipay"=>"支付宝"];
        if($info->type!=='transfer'){
            $info['line_bank']='线上支付';
        }else{
            $bank_info=Db::name("admin_bank")->where(['id'=>$info['charge_type_id']])->find();
            if(!empty($bank_info)){
                $info['line_bank']='存入银行:'.$bank_info['bank_name'].'; 收款卡号：'.$bank_info['card'];
            }
        }
        $info->type=$arr[$info->type];
        $addr=$_SERVER["DOCUMENT_ROOT"].'/uploads/images/'.$info['receipt_img'];
        if(!empty($info["receipt_img"])&&file_exists($addr)){
            $myurl = '<div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="'. PUBLIC_PATH .'uploads/images/'.$info['receipt_img'].'"data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="'.PUBLIC_PATH .'uploads/images/'.$info['receipt_img'].'"></a></div>';
        }else{
            $myurl ='<span></span>';
        }

        return ZBuilder::make('form')
            ->setPageTitle('审核') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['static', 'order_no', '订单号'],
                ['static', 'money', '充值金额',],
                ['static', 'type', '充值方式'],
                ['static', 'form_name', '转账用户名'],
                ['static', 'line_bank', '银行信息', ''],
                ['radio', 'status', '充值状态', '', [1=>'成功', 2=>'失败']],
                ['textarea', 'remark', '备注', '']
            ])
			->setExtraHtml($myurl, 'form_top')
            ->setFormData($info) // 设置表单数据
            ->fetch();   
    }

}