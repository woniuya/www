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
use app\money\model\Record as RecordModel;
use think\Db;
use think\Hook; 
use think\Cache;

/**
 * 资金记录控制器
 * @package app\money\admin
 */
class Record extends Admin
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
        $data_list = RecordModel::getAll($map, $order);
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
            ->setSearch('mobile,name') // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['type', '类型'],
                ['affect', '变动资金'],
                ['account', '账户余额'],
                ['create_time', '时间', 'datetime'],
                ['info', '信息'],
            ])
            ->hideCheckbox()
            ->addTopButton('custem',$btn_excel)
            ->setTableName('money')
            ->addOrder('id,account,affect,create_time')
     		->setRowList($data_list)
            ->fetch(); // 渲染模板
    }
    public function index_export(){
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder();
        empty($order) && $order = 'id desc';
        // 数据列表
        $xlsData = RecordModel::getAll($map, $order);
          foreach ($xlsData as $k=>$v){
               $v['create_times']=date('Y-m-d h:i',$v['create_time']);
           }
        $title="清算明细";
        $arrHeader = array('ID','手机号','姓名','类型','变动资金','账户余额','时间','信息');
        $fields = array('id','mobile','name','type','affect','account','create_time','info');
        export($arrHeader,$fields,$xlsData,$title);
    }
    
}