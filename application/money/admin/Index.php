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
use app\money\model\Money as MoneyModel;
use think\Db;
use think\Hook; 
use think\Cache;

/**
 * 资金管理控制器
 * @package app\money\admin
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
        $data_list = MoneyModel::getAll($map, $order);
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
            ->setSearch(['mid'=>'用户ID','member.name' => '姓名', 'member.mobile' => '手机号']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['account', '可用资金'],
                ['freeze', '冻结金额'],
                ['operate_account', '操盘总额'],
                ['bond_account', '保证金总额'],
                //['status', '状态', 'switch'],
            ])
            ->hideCheckbox()
            ->setTableName('money')
            ->addTopButton('custem',$btn_excel)
            //->addTopButtons('enable,disable') // 批量添加顶部按钮
            ->addRightButtons(['edit']) // 批量添加右侧按钮
            ->addOrder('id,account,freeze,status,operate_account,bond_account')
     		->setRowList($data_list)
            ->fetch(); // 渲染模板
    }

    public function index_export(){
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = MoneyModel::getAll($map, $order);

        $title="资金列表";
        $arrHeader = array('ID','手机号','姓名','可用资金','冻结金额','操盘总额','保证金总额');
        $fields = array('id','mobile','name','account','freeze','operate_account','bond_account');
        export($arrHeader,$fields,$xlsData,$title);
    }

    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $table   = input('post.table', ''); 
        $type    = input('post.type', '');
        if(in_array($field, array('account', 'freeze', 'operate_account', 'bond_account')) ){
        	$value *= 100;
        }
        
        $mid  = MoneyModel::where('id', $id)->value('mid');
        $old_value  = MoneyModel::where('id', $id)->value($field);
        $mobile = Db('member')->where('id',$mid)->value('mobile');
        $details = $mobile.' 字段(' . $field . ')，原值：('.$old_value.')新值：(' . $value . ')';

		switch ($type) {
            // 日期时间需要转为时间戳
            case 'combodate':
                $value = strtotime($value);
                break;
            // 开关
            case 'switch':
                $value = $value == 'true' ? 1 : 0;
                break;
            // 开关
            case 'password':
                $value = Hash::make((string)$value);
                break;
        }

        $pk = Db('money')->getPk();
        $result = Db('money')->where($pk, $id)->setField($field, $value);

        if (false !== $result) {
            Cache::clear();
            // 记录行为日志
            if (!empty($member_mobile)) { 
                call_user_func_array('action_log', ['money_edit', 'money', $id, UID, $details]);
            }
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}