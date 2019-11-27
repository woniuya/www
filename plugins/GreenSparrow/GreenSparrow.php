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

namespace plugins\GreenSparrow;
use app\common\controller\Plugin;
use think\Db;
/**
 * 实盘api客户端插件
 * @package plugin\SparrowClient
 * @author 路人甲乙
 */
class GreenSparrow extends Plugin
{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
        'name'        => 'GreenSparrow',
        // 插件标题[必填]
        'title'       => 'GreenSparrow',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'greensparrow.meng.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-globe',
        // 插件描述[选填]
        'description' => '这是路人甲乙实盘交易插件，为您提供实盘交易和行情查询方法。',
        // 插件作者[必填]
        'author'      => '路人甲乙',
        // 作者主页[选填]
        'author_url'  => 'http://www.lurenjiayi.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.1',
        // 是否有后台管理功能
        'admin'       => '0',
    ];

    /**
     * @var string 原数据库表前缀
     * 用于在导入插件sql时，将原有的表前缀转换成系统的表前缀
     * 一般插件自带sql文件时才需要配置
     */
    public $database_prefix = 'lmq_';

    /**
     * @var array 插件钩子
     */
    public $hooks = [
        // 钩子名称 => 钩子说明
        // 如果是系统钩子，则钩子说明不用填写
        'page_tips',
        'my_hook' => '我的钩子',
    ];

    /**
     * page_tips钩子方法
     * @param $params
     * @author 路人甲乙
     */
    public function pageTips(&$params)
    {
        /*echo '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p>Hello World</p>
        </div>';*/
    }

    /**
     * 安装方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @author 路人甲乙
     * @return bool
     */
    public function install(){
        $data['value']=3;
        $data['options']="1:腾讯 \n 2:新浪 \n 3:实盘API";
        $res=db::name('admin_config')->where('name','market_data_in')->update($data);
        return $res;
    }

    /**
     * 卸载方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @author 路人甲乙
     * @return bool
     */
    public function uninstall(){
        $data['value']=1;
        $data['options']="1:腾讯 \n2:新浪";
        $res=db::name('admin_config')->where('name','market_data_in')->update($data);
        return $res;
    }
}
