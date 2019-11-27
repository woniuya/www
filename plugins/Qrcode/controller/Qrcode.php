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

namespace plugins\Qrcode\controller;

use app\common\controller\Common;
require_once(dirname(dirname(__FILE__))."/sdk/phpqrcode.php");

/**
 * 二维码生成控制器
 * @package plugins\Barcode\controller
 */
class Qrcode extends Common
{
    /**
     * 生成二维码
     * @param string $text 二维码内容
     * @param string $file_path 二维码图片路径，默认生成在网站根目录下，图片名为“qrcode.png”
     * @author 路人甲乙
     *
     * 示例：
     * plugin_action('Qrcode/Qrcode/generate', ['123456', APP_PATH.'test.png']);
     */
    public function generate($text = '', $file_path = 'qrcode.png')
    {
        // 插件配置参数
        $plugin_config = plugin_config('qrcode');

        $plugin_config['outfile']      = $plugin_config['outfile']      == 0 ? false : $file_path;
        $plugin_config['saveandprint'] = $plugin_config['saveandprint'] == 1 ? true  : false;
        if ($plugin_config['back_color'] == '') {
            $plugin_config['back_color'] = 0xFFFFFF;
        } else {
            $plugin_config['back_color'] = hexdec($this->RGBToHex($plugin_config['back_color']));
        }
        if ($plugin_config['fore_color'] == '') {
            $plugin_config['fore_color'] = 0x000000;
        } else {
            $plugin_config['fore_color'] = hexdec($this->RGBToHex($plugin_config['fore_color']));
        }

        // 判断是否加logo
        if ($plugin_config['logo'] > 0) {
            $plugin_config['outfile'] = $plugin_config['outfile'] === false ? 'qrcode.png' : $plugin_config['outfile'];

            \QRcode::png($text, $plugin_config['outfile'], $plugin_config['level'], $plugin_config['size'], $plugin_config['margin'], false, $plugin_config['back_color'], $plugin_config['fore_color']);

            // 添加logo
            if ($plugin_config['logo'] > 0) {
                $QR = imagecreatefromstring(file_get_contents($plugin_config['outfile']));
                $logo = imagecreatefromstring(file_get_contents('.'.get_file_path($plugin_config['logo'])));
                $QR_width = imagesx($QR);//二维码图片宽度
                $QR_height = imagesy($QR);//二维码图片高度
                $logo_width = imagesx($logo);//logo图片宽度
                $logo_height = imagesy($logo);//logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width/$logo_qr_width;
                $logo_qr_height = $logo_height/$scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;
                //重新组合图片并调整大小
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                    $logo_qr_height, $logo_width, $logo_height);
                // 生成带logo的二维码图片
                imagepng($QR, $plugin_config['outfile']);
            }

            // 显示二维码
            header("Content-type: image/png");
            imagepng($QR);
        } else {
            \QRcode::png($text, $plugin_config['outfile'], $plugin_config['level'], $plugin_config['size'], $plugin_config['margin'], $plugin_config['saveandprint'], $plugin_config['back_color'], $plugin_config['fore_color']);
        }
        exit;
    }

    /**
     * 十六进制 转 RGB
     * @param  string $hexColor 16进制颜色值
     * @from http://blog.csdn.net/shaerdong/article/details/46805807
     * @return array RGB数组
     */
    function hex2rgb($hexColor) {
        $color = str_replace('#', '', $hexColor);
        if (strlen($color) > 3) {
            $rgb = array(
                'r' => hexdec(substr($color, 0, 2)),
                'g' => hexdec(substr($color, 2, 2)),
                'b' => hexdec(substr($color, 4, 2))
            );
        } else {
            $color = $hexColor;
            $r = substr($color, 0, 1) . substr($color, 0, 1);
            $g = substr($color, 1, 1) . substr($color, 1, 1);
            $b = substr($color, 2, 1) . substr($color, 2, 1);
            $rgb = array(
                'r' => hexdec($r),
                'g' => hexdec($g),
                'b' => hexdec($b)
            );
        }
        return $rgb;
    }

    /**
     * RGB转 十六进制
     * @param string $rgb RGB颜色的字符串 如：rgb(255,255,255);
     * @from http://blog.csdn.net/shaerdong/article/details/46805807
     * @return string
     */
    function RGBToHex($rgb){
        $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
        preg_match($regexp, $rgb, $match);
        array_shift($match);
        $hexColor = "0x";
        $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
        for ($i = 0; $i < 3; $i++) {
            $r = null;
            $c = $match[$i];
            $hexAr = array();
            while ($c > 16) {
                $r = $c % 16;
                $c = ($c / 16) >> 0;
                array_push($hexAr, $hex[$r]);
            }
            array_push($hexAr, $hex[$c]);
            $ret = array_reverse($hexAr);
            $item = implode('', $ret);
            $item = str_pad($item, 2, '0', STR_PAD_LEFT);
            $hexColor .= $item;
        }
        return $hexColor;
    }
}