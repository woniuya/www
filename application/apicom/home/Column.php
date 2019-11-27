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

use app\apicom\model\Column as ColumnModel;
use think\Db;
use util\Tree;
use think\Request;

/**
 * 前台栏目文档列表控制器
 * @package app\apicom\admin
 */
class Column extends Common
{
    /**
     * 栏目文章列表
     * @param null $id 栏目id
     * @author 路人甲乙
     * @return mixed
     */
    public function index($id = null)
    {
	
        if ($id === null) ajaxmsg("缺少参数",0);
		$prefix = config("database.prefix");
        $map = [
            'status' => 1,
            'id'     => $id
        ];
        $page  = $this->request->param("page");
        $page = $page ? intval($page) : 1;
        $offset = ($page-1) * 20;

        $column = Db::name('cms_column')->where($map)->find();
        if (!$column) ajaxmsg("该栏目不存在",0);

        $model = Db::name('cms_model')->where('id', $column['model'])->find();
		$table = $model['table'];


        if ($model['type'] == 2) {
            $cid_all   = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;
				 
            $map = [
                'n.trash'  => 0,
               'n.status' => 1,
                'n.cid'    => ['in', $cid_all]
            ];


			$result = Db::table($table)
				 ->alias('d')
				 //->join('admin_user a','d.uid = a.id','left')
				 ->where($map)
				  ->order('d.id desc')
                ->page($offset,10)
				 ->select();
				 
		
				ajaxmsg('获取成功',1,$result);
			
        } else {
            $cid_all   = ColumnModel::getChildsId($id);
            $cid_all[] = (int)$id;

            $map = [
                'd.trash'  => 0,
                'd.status' => 1,
                'd.cid'    => ['in', $cid_all]
            ];

			$result = Db::table($prefix.'cms_document')
					 ->alias('d')
			        // ->join('admin_user a','d.uid = a.id','left')
					 ->where($map)
                     ->order('d.id desc')
                    ->page($offset,20)
					 ->select();

			    ajaxmsg('获取成功',1,$result);
        }
		

    }

}