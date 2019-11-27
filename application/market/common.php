<?php
use app\apicom\model\JWT;

/***
 ** ajax json 2018 05 29
 ***/
if (!function_exists('ajaxmsg')) {
    function ajaxmsg($msg = "", $type = 1,$data = '',$is_end = true)
    {
        $json['status'] = $type.'';
        if (is_array($msg)) {
            foreach ($msg as $key => $v) {
                $json[$key] = $v;
            }
        } elseif (!empty($msg)) {
            $json['message'] = $msg;
        }
        $json['data'] = $data;
        if ($is_end) {
            echo json_encode($json);
            exit;
        } else {
            echo json_encode($json);
            exit;
        }
    }
}
/***
 ** ajax json 2018 05 29
 ***/
if (!function_exists('isLogin')) {
    function isLogin($token)
    {
        if(!empty($token)){
            $decoded = JWT::decode($token, JWT_TOKEN_KEY, array('HS256'));
              $doHost = $_SERVER['HTTP_HOST'];
				if($doHost == $decoded->doHost){
					return $decoded->uid; 
				}else{
					return 0;
				} 
        }else{
            return 0;
        }
		
    }
}

?>