<?php
namespace plugins\GreenSparrow\model;
use think\Model;
/**
 * Created by PhpStorm.
 * author: gs101
 * Date: 2017/12/29
 * Time: 10:40
 */
class Plugin extends Model{
    public static function config(){
        $res=Plugin::query('select	config	from	lmq_admin_plugin	where	name=?',["GreenSparrow"]);
       return json_decode($res[0]['config'])->status;
    }
}