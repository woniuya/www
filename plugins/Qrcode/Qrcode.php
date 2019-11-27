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

namespace plugins\Qrcode;

use app\common\controller\Plugin;

/**
 * 二维码生成插件
 * @package plugins\Qrcode
 */
class Qrcode extends Plugin
{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
        'name'        => 'Qrcode',
        // 插件标题[必填]
        'title'       => '二维码生成插件',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'qrcode.ming.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-qrcode',
        // 插件描述[选填]
        'description' => '二维码生成插件',
        // 插件作者[必填]
        'author'      => '路人甲乙',
        // 作者主页[选填]
        'author_url'  => 'http://www.lurenjiayi.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.0'
    ];

    /**
     * 安装方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @author 路人甲乙
     * @return bool
     */
    public function install(){
        return true;
    }

    /**
     * 卸载方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @author 路人甲乙
     * @return bool
     */
    public function uninstall(){
        return true;
    }
}