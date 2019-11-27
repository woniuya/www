<?php
namespace app\wap\home;
use think\Controller;
class Index extends Controller{
    public function _empty(){
      
      	//echo '';exit;
        return $this->fetch('index');
    }
}