<?php
namespace app\index\controller;

use think\Request;
class Error extends Home
{
   public function index(Request $request)
  {
      return $this->fetch('404');
    }


}
