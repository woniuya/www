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

namespace app\stock\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;

/**
 * 证券账户默认控制器
 * @package app\stock\admin
 */
class Contract extends Admin
{
    public function index()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            @chmod(config('data_backup_path')."contract.txt",0777);
            $res=file_put_contents(config('data_backup_path')."contract.txt",$data['content']);
            if ($res) {
                $this->success('修改成功', 'index');
            } else {
                $this->error('修改失败');
            }
        }
        $info['content']=file_get_contents(config('data_backup_path')."contract.txt");
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['ckeditor', 'content', '协议内容'],
            ])
            ->setFormdata($info)
            ->setPageTitle("实盘交易平台操盘协议")
            ->fetch();
    }

}
