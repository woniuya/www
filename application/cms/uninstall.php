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

use think\Db;
use think\Exception;
// cms模块卸载文件

// 是否清除数据
$clear = $this->request->get('clear');

if ($clear == 1) {
    // 内容模型的表名列表
    $table_list = Db::name('cms_model')->column('table');

    if ($table_list) {
        foreach ($table_list as $table) {
            // 删除内容模型表
            $sql = 'DROP TABLE IF EXISTS `'.$table.'`;';
            try {
                Db::execute($sql);
            } catch (\Exception $e) {
                throw new Exception('删除表：'.$table.' 失败！', 1001);
            }
        }
    }
}
