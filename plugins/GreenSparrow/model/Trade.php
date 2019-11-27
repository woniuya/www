<?php
namespace plugins\GreenSparrow\model;
use think\Model;
class Trade extends Model
{
    private $socket;
    public  $login=false;

    public function __construct(){
        header("Content-type: text/html; charset=utf-8");
        $rid = build_rid_no();
        $port=config('web_trade_port');
        //$options['url']='ws://yg.lurenjiayi.net:'.$port.'?rid='.$rid.'&flag=1';
        $options['url']='ws://132.232.16.116:8282?rid='.$rid.'&flag=1';
        //$options['url']='ws://172.17.85.11:8282?rid='.$rid.'&flag=1';
        $this->socket=new WebSocket($options);
    }
    public function send($query){
        return $this->socket->send($query);
    }
    public function receive(){
        return $this->socket->receive();
    }
}