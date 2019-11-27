<?php
	namespace plugins\UcpaasSms\controller;

	use app\common\builder\ZBuilder;
	use app\common\controller\Common;

	class UcpaasSms extends Common
	{
		public function sendSms($mobile, $msg)
		{
			$Config = plugin_config('UcpaasSms');

			$postArr = array (
				'account'  =>  $Config['username'],
				'password' => $Config['password'],
				//'msg' => urlencode($Config['tip'].$msg),
                'msg' => urlencode($msg),
				'phone' => $mobile,
				'report' => true,
	        );
			$result = self::curlPost( 'http://smssh1.253.com/msg/send/json' , $postArr);
			$res = json_decode($result, true);
			return $res['code'] == 0 ? true: false;
		}
        public function sendSms_member($msg,$params)
        {
            $Config = plugin_config('UcpaasSms');
            $postArr = array (
                'account' =>$Config['username'],
                'password' =>$Config['password'],
                'msg' =>$msg,
                'params' => $params,
                'report' => true
            );
            $result = self::curlPost( 'http://smssh1.253.com/msg/variable/json' , $postArr);

            $res = json_decode($result, true);
            return $res['code'] == 0 ? true: false;
        }

		public static function curlPost($url,$postFields){
			$postFields = json_encode($postFields);
			$ch = curl_init ();
			curl_setopt( $ch, CURLOPT_URL, $url ); 
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8'
				)
			);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
	        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
	        curl_setopt( $ch, CURLOPT_TIMEOUT,1); 
	        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
			$ret = curl_exec ( $ch );
	        if (false == $ret) {
	            $result = curl_error(  $ch);
	        } else {
	            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
	            if (200 != $rsp) {
	                $result = "请求状态 ". $rsp . " " . curl_error($ch);
	            } else {
	                $result = $ret;
	            }
	        }
			curl_close ( $ch );
			return $result;
		}
	}

?>