<?php  
$crond_list = array(
    '*:46'=> [
        'app\stock\home\Crond::day',
    ],  //每天
    '*' => [
        //'app\stock\home\Crond::heart',
        'app\stock\home\Crond::temp',
        //'app\stock\home\Crond::day',
        'app\stock\home\Crond::minute',
        'app\stock\home\Crond::precautious_line',
    ],  //每分钟
);
return $crond_list;