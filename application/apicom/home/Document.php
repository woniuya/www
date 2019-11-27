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
use app\apicom\model\Document as DocumentModel;
use util\Tree;
use think\Db;
use think\Request;

/**
 * 文档控制器
 * @package app\apicom\home
 */
class Document extends Common
{
    /**
     * 文档详情页
     * @param null $id 文档id
     * @param string $model 独立模型id
     * @author 路人甲乙
     * @return mixed
     */
    public function detail($id = null, $model = '')
    {
		$req=request();
        $id = $this->request->param("id");
         $model = $this->request->param("model");
         if($model =='') $model = 2;

        if ($id === null) ajaxmsg("缺少参数",0);

        if ($model != '') {
            $table = get_model_table($model);
            $map = [
                //'n.status' => 1,
                //'n.trash'  => 0
            ];
        } else {

            $map = [
                'd.status' => 1,
                'd.trash'  => 0
            ];
        }

        $info = DocumentModel::getOne($id, $model, $map);
        $info['create_time'] = getTimeFormt($info['create_time'],1);
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/uploads/images/';
        $info['content'] = str_replace('/uploads/images/',$host,$info['content']);
        if (isset($info['tags'])) {
            $info['tags'] = explode(',', $info['tags']);
        }
			ajaxmsg('获取成功',1,$info);

    }

  
}