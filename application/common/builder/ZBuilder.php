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

namespace app\common\builder;

use app\common\controller\Common;
use think\Exception;

/**
 * 构建器
 * @package app\common\builder
 * @author 路人甲乙
 */
class ZBuilder extends Common
{
    /**
     * @var array 构建器数组
     * @author 路人甲乙
     */
    protected static $builder = [];

    /**
     * @var array 模板参数变量
     */
    protected static $vars = [];

    /**
     * @var string 动作
     */
    protected static $action = '';

    /**
     * 初始化
     * @author 路人甲乙
     */
    public function _initialize()
    {}

    /**
     * 创建各种builder的入口
     * @param string $type 构建器名称，'Form', 'Table', 'View' 或其他自定义构建器
     * @param string $action 动作
     * @author 路人甲乙
     * @return table\Builder|form\Builder|aside\Builder
     * @throws Exception
     */
    public static function make($type = '', $action = '')
    {
        if ($type == '') {
            throw new Exception('未指定构建器名称', 8001);
        } else {
            $type = strtolower($type);
        }

        // 构造器类路径
        $class = '\\app\\common\\builder\\'. $type .'\\Builder';
        if (!class_exists($class)) {
            throw new Exception($type . '构建器不存在', 8002);
        }

        if ($action != '') {
            static::$action = $action;
        } else {
            static::$action = '';
        }

        return new $class;
    }

    /**
     * 加载模板输出
     * @param string $template 模板文件名
     * @param array  $vars     模板输出变量
     * @param array  $replace  模板替换
     * @param array  $config   模板参数
     * @author 路人甲乙
     * @return mixed
     */
    public function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $vars = array_merge($vars, self::$vars);
        return parent::fetch($template, $vars, $replace, $config);
    }
}
