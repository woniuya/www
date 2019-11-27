<?php
 
error_reporting(0); //抑制所有错误信息
@header("content-Type: text/html; charset=utf-8"); //语言强制
 
//获取用户IP地址
$ip = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
$ip = ($ip) ? $ip : $_SERVER["REMOTE_ADDR"];
logResult($ip);
 
//echo sprintf("欢迎访问,你的IP地址是:%s",$ip);
 
function logResult($word='') {
    $fp = fopen("log.txt","a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"记录时间：".strftime("%Y%m%d%H%M%S",time()).",IP:".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}