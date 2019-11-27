<?php
/*
 * Copyright (C) www.lvmaque.com
 * @author menghui@lvmaque.com
 * @time 2017-12-17
 */

class Base {
    protected $socket, $is_connected = false, $is_closing = false, $last_opcode = null,$options=array(),
        $close_status = null, $huge_payload = null;

    protected static $opcodes = array(
        'continuation' => 0,
        'text'         => 1,
        'binary'       => 2,
        'close'        => 8,
        'ping'         => 9,
        'pong'         => 10,
    );

    public function getLastOpcode()  { return $this->last_opcode;  }
    public function getCloseStatus() { return $this->close_status; }
    public function isConnected()    { return $this->is_connected; }
    protected function connect(){}

    public function setTimeout($timeout) {
        $this->options['timeout'] = $timeout;
        if ($this->socket && get_resource_type($this->socket) === 'stream') {
            stream_set_timeout($this->socket, $timeout);
        }
    }

    public function setFragmentSize($fragment_size) {
        $this->options['fragment_size'] = $fragment_size;
        return $this;
    }

    public function getFragmentSize() {
        return $this->options['fragment_size'];
    }
    public function send($payload, $opcode = 'text', $masked = true) {
        if (!$this->is_connected) $this->connect(); /// @todo This is a client function, fixme!

        if (!in_array($opcode, array_keys(self::$opcodes))) {
            throw new Exception("Bad opcode '$opcode'.  Try 'text' or 'binary'.");
        }
        // record the length of the payload
        $payload_length = strlen($payload);

        $fragment_cursor = 0;
        // while we have data to send
        while ($payload_length > $fragment_cursor) {
            // get a fragment of the payload
            $sub_payload = substr($payload, $fragment_cursor, $this->options['fragment_size']);

            // advance the cursor
            $fragment_cursor += $this->options['fragment_size'];

            // is this the final fragment to send?
            $final = $payload_length <= $fragment_cursor;

            // send the fragment
            $this->send_fragment($final, $sub_payload, $opcode, $masked);

            // all fragments after the first will be marked a continuation
            $opcode = 'continuation';
        }

    }

    protected function send_fragment($final, $payload, $opcode, $masked) {
        // Binary string for header.
        //echo $this->socket."<--75W:".substr($payload,0,90)."\n\n";
        $frame_head_binstr = '';

        // Write FIN, final fragment bit.
        $frame_head_binstr .= (bool) $final ? '1' : '0';

        // RSV 1, 2, & 3 false and unused.
        $frame_head_binstr .= '000';

        // Opcode rest of the byte.
        $frame_head_binstr .= sprintf('%04b', self::$opcodes[$opcode]);

        // Use masking?
        $frame_head_binstr .= $masked ? '1' : '0';

        // 7 bits of payload length...
        $payload_length = strlen($payload);
        if ($payload_length > 65535) {
            $frame_head_binstr .= decbin(127);
            $frame_head_binstr .= sprintf('%064b', $payload_length);
        }
        elseif ($payload_length > 125) {
            $frame_head_binstr .= decbin(126);
            $frame_head_binstr .= sprintf('%016b', $payload_length);
        }
        else {
            $frame_head_binstr .= sprintf('%07b', $payload_length);
        }

        $frame = '';

        // Write frame head to frame.
        foreach (str_split($frame_head_binstr, 8) as $binstr) $frame .= chr(bindec($binstr));

        // Handle masking
        if ($masked) {
            // generate a random mask:
            $mask = '';
            for ($i = 0; $i < 4; $i++) $mask .= chr(rand(0, 255));
            $frame .= $mask;
        }

        // Append payload to frame:
        for ($i = 0; $i < $payload_length; $i++) {
            $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        }

        $this->write($frame);
    }

    public function receive() {
        if (!$this->is_connected){
            $this->connect(); /// @todo This is a client function, fixme!
        }
        $this->huge_payload = '';
        $response = null;
        while (1){
            while (is_null($response)) $response = $this->receive_fragment();
            $tmd=json_decode($response,true);
            if(isset($tmd["event"])){
                //收到交易服务器信息通知
                if($tmd["event"]=="Trade_ServerEvent"){break;}
                //收到后台行情模块初始化结束通知
                if($tmd["event"]=="Market_InitEvent"){break;}
                //收到行情登录结果通知
                if($tmd["event"]=="Market_LoginEvent"){break;}
                //收到后台交易模块初始化结束通知
                if($tmd["event"]=="Trade_InitEvent"){break;}
                //收到交易模块登录结果通知
                if($tmd["event"]=="Trade_LoginEvent"){break;}
                //交易结果通知
                if($tmd["event"]=="Trade_OrderOKEvent"){break;}
                //读取超时事件
                //if($tmd["event"]=="Empty_error_event"){break;}
                //行情连接错误通知
                if(isset($tmd['data']['ErrInfo'])){
                    $answer = iconv("UTF-8", "GB2312//IGNORE", $tmd['data']['ErrInfo']);
                    var_dump($answer);
                }
                if($tmd["event"]=="MarketEx_ServerErrEvent"){break;}
                //扩展行情连接错误通知
                if($tmd["event"]=="MarketL2_ServerErrEvent"){break;}
                //交易服务器连接错误通知
                if($tmd["event"]=="Trade_ServerErrEvent"){break;}
                //行情连接错误通知
                if($tmd["event"]=="Market_ServerErrEvent"){break;}
                //当前时间段不允许委托通知
                if($tmd["event"]=="Trade_SendOrderEvent"){break;}
            }else{
                break;
            }
        }
        return $response;
    }

    protected function receive_fragment() {

        // Just read the main fragment information first.
        $data = $this->read(2);
        if (!isset($data[0])) {
            //return json_encode(["event"=>"Empty_error_event"]);
            $metadata = stream_get_meta_data($this->socket);
            throw new Exception(
                'Empty read; connection dead?  Stream state: ' . json_encode($metadata)
            );
        }
        // Is this the final fragment?  // Bit 0 in byte 0
        /// @todo Handle huge payloads with multiple fragments.
        $final = (boolean) (ord($data[0]) & 1 << 7);
        // Should be unused, and must be false…  // Bits 1, 2, & 3
//        $rsv1  = (boolean) (ord($data[0]) & 1 << 6);
//        $rsv2  = (boolean) (ord($data[0]) & 1 << 5);
//        $rsv3  = (boolean) (ord($data[0]) & 1 << 4);

        // Parse opcode
        $opcode_int = ord($data[0]) & 31; // Bits 4-7
        $opcode_ints = array_flip(self::$opcodes);
        if (!array_key_exists($opcode_int, $opcode_ints)) {
            throw new Exception("Bad opcode in websocket frame: $opcode_int");
        }
        $opcode = $opcode_ints[$opcode_int];
        // record the opcode if we are not receiving a continutation fragment
        if ($opcode !== 'continuation') {
            $this->last_opcode = $opcode;
        }
        // Masking?
        $mask = (boolean) (ord($data[1]) >> 7);  // Bit 0 in byte 1
        $payload = '';
        // Payload length
        $payload_length = (integer) ord($data[1]) & 127; // Bits 1-7 in byte 1
        if ($payload_length > 125) {
            if ($payload_length === 126) $data = $this->read(2); // 126: Payload is a 16-bit unsigned int
            else                         $data = $this->read(8); // 127: Payload is a 64-bit unsigned int
            $payload_length = bindec(self::sprintB($data));
        }
        // Get masking key.
        $masking_key=array();
        if ($mask) $masking_key = $this->read(4);
        // Get the actual payload, if any (might not be for e.g. close frames.
        if ($payload_length > 0) {
            $data = $this->read($payload_length);
            if ($mask) {
                // Unmask payload.
                for ($i = 0; $i < $payload_length; $i++) $payload .= ($data[$i] ^ $masking_key[$i % 4]);
            }
            else $payload = $data;
        }
        if ($opcode === 'close') {
            // Get the close status.
            if ($payload_length >= 2) {
                $status_bin = $payload[0] . $payload[1];
                $status = bindec(sprintf("%08b%08b", ord($payload[0]), ord($payload[1])));
                $this->close_status = $status;
                $payload = substr($payload, 2);

                if (!$this->is_closing) $this->send($status_bin . 'Close acknowledged: ' . $status, 'close', true); // Respond.
            }
            if ($this->is_closing) $this->is_closing = false; // A close response, all done.
            // And close the socket.
            fclose($this->socket);
            $this->is_connected = false;
        }
        // if this is not the last fragment, then we need to save the payload
        if (!$final) {
            $this->huge_payload .= $payload;
            return null;
        }
        // this is the last fragment, and we are processing a huge_payload
        else if ($this->huge_payload) {
            // sp we need to retreive the whole payload
            $payload = $this->huge_payload .= $payload;
            $this->huge_payload = null;
        }
        return $payload;
    }

    /**
     * Tell the socket to close.
     *
     * @param integer $status  http://tools.ietf.org/html/rfc6455#section-7.4
     * @param string  $message A closing message, max 125 bytes.
     */
    public function close($status = 1000, $message = 'ttfn') {
        $status_binstr = sprintf('%016b', $status);
        $status_str = '';
        foreach (str_split($status_binstr, 8) as $binstr) $status_str .= chr(bindec($binstr));
        $this->send($status_str . $message, 'close', true);
        $this->is_closing = true;
        $response = $this->receive(); // Receiving a close frame will close the socket now.
        return $response;
    }
    public function close_b($status = 1000, $message = 'ttfn') {
        $status_binstr = sprintf('%016b', $status);
        $status_str = '';
        foreach (str_split($status_binstr, 8) as $binstr) $status_str .= chr(bindec($binstr));
        $this->send($status_str . $message, 'close', true);
        $this->is_connected = false;
        unset($this->socket);
        return 'close';
    }

    protected function write($data) {
        $written = fwrite($this->socket, $data);
        if ($written < strlen($data)) {
            throw new Exception(
                "Could only write $written out of " . strlen($data) . " bytes."
            );
        }
    }

    protected function read($length) {
        $data = '';
        while (strlen($data) < $length) {
            if (feof($this->socket)) break;
            $buffer = fread($this->socket, $length - strlen($data));
            if ($buffer === false) {
                $metadata = stream_get_meta_data($this->socket);
                throw new Exception(
                    'Broken frame, read ' . strlen($data) . ' of stated '
                    . $length . ' bytes.  Stream state: '
                    . json_encode($metadata)
                );
            }
            if ($buffer === '') {
                $metadata = stream_get_meta_data($this->socket);
                throw new Exception(
                    'Empty read; connection dead?  Stream state: ' . json_encode($metadata)
                );
            }
            $data .= $buffer;
        }
        return $data;
    }
    /**
     * Helper to convert a binary to a string of '0' and '1'.
     */
    protected static function sprintB($string) {
        $return = '';
        for ($i = 0; $i < strlen($string); $i++) $return .= sprintf("%08b", ord($string[$i]));
        return $return;
    }
}

class Client extends Base
{
    protected $socket;
    protected $is_connected = false;
    protected $is_closing = false;
    protected $last_opcode = null;
    protected $close_status = null;
    protected $huge_payload = null;
    protected $socket_uri;
    public $login=false;

    public function __construct($options = array()) {
        $this->options = $options;
        if (!ini_get('date.timezone')) date_default_timezone_set('Asia/Shanghai');
        if (!array_key_exists('timeout', $this->options)) $this->options['timeout'] = 65;
        // the fragment size
        if (!array_key_exists('fragment_size', $this->options)) $this->options['fragment_size'] = 4096;
        if (!array_key_exists('api_url', $this->options)) $this->options['api_url'] = 'ws://132.232.16.116';
        if (!array_key_exists('api_port', $this->options)) $this->options['api_port'] = '8282';
        $rid = mt_rand(100,1000)+time();
        $uri = $this->options['api_url'].":".$this->options['api_port']."?rid=".$rid."&flag=1";
        $this->socket_uri = $uri;
    }
    public function __destruct() {
        if ($this->socket) {
            if (get_resource_type($this->socket) === 'stream') fclose($this->socket);
            $this->socket = null;
        }
    }

    /**
     * Perform WebSocket handshake
     */
    protected function connect() {
        $url_parts = parse_url($this->socket_uri);
        $scheme    = $url_parts['scheme'];
        $host      = $url_parts['host'];
        $user      = isset($url_parts['user']) ? $url_parts['user'] : '';
        $pass      = isset($url_parts['pass']) ? $url_parts['pass'] : '';
        $port      = isset($url_parts['port']) ? $url_parts['port'] : ($scheme === 'wss' ? 443 : 80);
        $path      = isset($url_parts['path']) ? $url_parts['path'] : '/';
        $query     = isset($url_parts['query'])    ? $url_parts['query'] : '';
        $fragment  = isset($url_parts['fragment']) ? $url_parts['fragment'] : '';
        $path_with_query = $path;
        if (!empty($query))    $path_with_query .= '?' . $query;
        if (!empty($fragment)) $path_with_query .= '#' . $fragment;

        if (!in_array($scheme, array('ws', 'wss'))) {
            throw new \Exception(
                "Url should have scheme ws or wss, not '$scheme' from URI '$this->socket_uri' ."
            );
        }
        $host_uri = ($scheme === 'wss' ? 'ssl' : 'tcp') . '://' . $host;
        // Set the stream context options if they're already set in the config
        if (isset($this->options['context'])) {
            // Suppress the error since we'll catch it below
            if (@get_resource_type($this->options['context']) === 'stream-context') {
                $context = $this->options['context'];
            }
            else {
                throw new \Exception(
                    "Stream context in \$options['context'] isn't a valid context"
                );
            }
        }
        else {
            $context = stream_context_create();
        }
        // Open the socket.  @ is there to supress warning that we will catch in check below instead.
        $this->socket = @stream_socket_client(
            $host_uri . ':' . $port,
            $errno,
            $errstr,
            $this->options['timeout'],
            STREAM_CLIENT_CONNECT,
            $context
        );

        if ($this->socket === false) {
            throw new \Exception(
                "Could not open socket to \"$host:$port\": $errstr ($errno)."
            );
        }
        // Set timeout on the stream as well.
//        stream_set_timeout($this->socket, $this->options['timeout']);
        stream_set_timeout($this->socket, 6);
        // Generate the WebSocket key.
        $key = self::generateKey();
        // Default headers (using lowercase for simpler array_merge below).
        $headers = array(
            'host'                  => $host . ":" . $port,
            'user-agent'            => 'websocket-client-php',
            'connection'            => 'Upgrade',
            'upgrade'                => 'websocket',
            'sec-websocket-key'     => $key,
            'sec-websocket-version' => '13',
        );
        // Handle basic authentication.
        if ($user || $pass) {
            $headers['authorization'] = 'Basic ' . base64_encode($user . ':' . $pass) . "\r\n";
        }
        // Deprecated way of adding origin (use headers instead).
        if (isset($this->options['origin'])) $headers['origin'] = $this->options['origin'];
        // Add and override with headers from options.
        if (isset($this->options['headers'])) {
            $headers = array_merge($headers, array_change_key_case($this->options['headers']));
        }
        $header =
            "GET " . $path_with_query . " HTTP/1.1\r\n"
            . implode(
                "\r\n", array_map(
                    function($key, $value) { return "$key: $value"; }, array_keys($headers), $headers
                )
            )
            . "\r\n\r\n";
        // Send headers.
        $this->write($header);
        // Get server response header (terminated with double CR+LF).
        $response = stream_get_line($this->socket, 1024, "\r\n\r\n");
        /// @todo Handle version switching
        // Validate response.
        if (!preg_match('#Sec-WebSocket-Accept:\s(.*)$#mUi', $response, $matches)) {
            $address = $scheme . '://' . $host . $path_with_query;
            throw new \Exception(
                "Connection to '{$address}' failed: Server sent invalid upgrade response:\n"
                . $response
            );
        }
        $keyAccept = trim($matches[1]);
        $expectedResonse = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        if ($keyAccept !== $expectedResonse) {
            throw new \Exception('Server sent bad upgrade response.');
        }
        $this->is_connected = true;
    }
    /**
     * Generate a random string for WebSocket key.
     * @return string Random string
     */
    protected static function generateKey() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"$&/()=[]{}0123456789';
        $key = '';
        $chars_length = strlen($chars);
        for ($i = 0; $i < 16; $i++) $key .= $chars[mt_rand(0, $chars_length-1)];
        return base64_encode($key);
    }

}
class Server extends Base {
    protected $addr;
    protected $port;
    protected $listening;
    protected $request;
    protected $request_path;
    /**
     * @param array   $options
     *   Associative array containing:
     *   - timeout:  Set the socket timeout in seconds.  Default: 5
     *   - port:     Chose port for listening.
     */
    public function __construct(array $options = array()) {
        // the fragment size
        if (!array_key_exists('fragment_size', $options)) $options['fragment_size'] = 4096;
        $this->port = isset($options['tran_port']) ? $options['tran_port'] : 8000;
        $this->addr = isset($options['tran_url']) ? $options['tran_url'] : 'tcp://127.0.0.1';
        $this->options = $options;
        do {
            $this->listening = @stream_socket_server("$this->addr:$this->port", $errno, $errstr);
        } while ($this->listening === false && $this->port++ < 10000);
        if (!$this->listening) {
            throw new Exception("Could not open listening socket.");
        }
    }

    public function getPort()    { return $this->port;         }
    public function getPath()    { return $this->request_path; }
    public function getRequest() { return $this->request;      }
    public function getListening() { return $this->listening;      }

    public function getHeader($header) {
        foreach ($this->request as $row) {
            if (stripos($row, $header) !== false) {
                list($headername, $headervalue) = explode(":", $row);
                return trim($headervalue);
            }
        }
        return null;
    }

    public function accept() {
        //echo "::$this->port\n";
        if (empty($this->options['timeout'])) {
            $this->socket = stream_socket_accept($this->listening,-1);
            stream_set_timeout($this->socket,2);
        } else {
            //stream_set_blocking($this->listening,0);
            $this->socket = stream_socket_accept($this->listening,$this->options['timeout']);
            stream_set_timeout($this->socket,2);
        }
        $this->performHandshake();
        return $this->socket;
    }

    protected function performHandshake() {
        $request = '';
        do {
            $buffer = stream_get_line($this->socket, 1024, "\r\n");
            $request .= $buffer . "\n";
            $metadata = stream_get_meta_data($this->socket);
        } while (!feof($this->socket) && $metadata['unread_bytes'] > 0);
        if (!preg_match('/GET (.*) HTTP\//mUi', $request, $matches)) {
            throw new Exception("No GET in request:\n" . $request);
        }
        $get_uri = trim($matches[1]);
        $uri_parts = parse_url($get_uri);
        $this->request = explode("\n", $request);
        $this->request_path = $uri_parts['path'];
        /// @todo Get query and fragment as well.
        if (!preg_match('#Sec-WebSocket-Key:\s(.*)$#mUi', $request, $matches)) {
            throw new Exception("Client had no Key in upgrade request:\n" . $request);
        }
        $key = trim($matches[1]);
        /// @todo Validate key length and base 64...
        $response_key = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $header = "HTTP/1.1 101 Switching Protocols\r\n"
            . "Upgrade: websocket\r\n"
            . "Connection: Upgrade\r\n"
            . "Sec-WebSocket-Accept: $response_key\r\n"
            . "\r\n";
        $this->write($header);
        $this->is_connected = true;
    }
}

class Trade
{
    private $socket;
    public  $login=false;
    private $login_tid;
    private $broker=18;
    private $clienver="7.09";
    public  $hqlogin=false; //行情login
    private $hqlogin_tid;
    public  $jytlogin=false; //login
    private $jytlogin_tid=array();

    public function __construct($option){
        $this->socket=new Client($option);//初始化链接
    }
    public function build_rid_no(){
        $numbers = mt_rand(100,1000)+time();
        return $numbers;
    }
    /*
     * 交易通系统登陆（安全设置下）
     */
    public function jytLogin($user,$pwd){
        $para = array("Encode" => 0,"LoginID" => $user,"LoginPW" => $pwd);
        $payload1 = json_encode(array(
            'req' => 'Server_Login',
            'rid' => $this->build_rid_no(),
            'para'=>$para
        ));
        $this->socket->send($payload1);
        $Login = json_decode($this->socket->receive(),true);
        if(isset($Login["event"])&&$Login["event"]=="Trade_ServerEvent"){
            $Login = json_decode($this->socket->receive(),true);
        }
        if($Login['ret']==0){
            $data = $Login['data'];
            $list=array();
            foreach ($data as $k=>$v){
                $list[$k]['ID'] = $v['ID'];
                $list[$k]['Broker'] = $v['Broker'];
                $list[$k]['Name'] = $v['Name'];
                $list[$k]['LID'] = $v['LID'];
            }
            $this->jytlogin = true;
            $this->jytlogin_tid = $list;
        }else{
            $this->jytlogin = false;
            $this->jytlogin_tid = array();
        }
    }
    //交易行情初始化登录
    public  function login($LID,$user,$password,$broker,$clienver){
        $this->jytLogin($user,$password);//登陆券商服务器
        if(!empty($broker)){$this->broker=$broker;}
        if(!empty($clienver)){$this->clienver=$clienver;}
        $para = array("Broker" => $this->broker,"Net" => 0,"Server" => 2,"ClientVer" => $this->clienver);
        $rid=$this->build_rid_no();
        $list=$this->_init($para,$rid);
        if(($rid == $list['res']['rid']) && ($rid == $list['message']['rid']) && $list['message']['data']['Result']==0){
            if($list['res']['ret']==0){//ret代表请求成功还是失败，0表示成功，非0表示失败
                $data = $list['res']['data'][0];
                $jytID = 0;
                if($this->jytlogin) {
                    foreach ($this->jytlogin_tid as $k=>$v){
                        if($LID==$v['LID']){
                            $jytID = $v['ID'];
                            break;
                        }else{
                            $jytID = 0;
                            unset($v['ID']);
                        }
                    }
                    if($jytID !==0){//判断该$jytID是否已被添加到安全组设置，如果存在，则执行下面参数
                        //安全模式登陆
                        // ReportSuccess 指定委托实际成交回报轮询定时周期，毫秒为单位
                        // TryConn 连接登录成功后因为网络或其它问题导致连接失效时自动尝试连接登录次数 3表示3秒
                        $para = array("ID" => $jytID, "ReportSuccess" => 3000, "TryConn" => 3, "IP" => $data['IP'], "Port" => $data['Port']);
                        $payload1 = json_encode(array(
                            'req' => 'Trade_SafeLogin',
                            'rid' => $this->build_rid_no(),
                            'para' => $para
                        ));
                    }else{
                        $this->login_tid=0;
                        $this->login=false;
                        return;
                    }
                }else{//普通模式登陆
                    $para = array("AccountMode"=> 8,"Broker" => $data['Broker'],"CreditAccount" => 0,"ReportSuccess" => 1000,"TryConn" => 3,"Server" => 2,"DeptID"=>1,"Encode"=>0,"IP"=>$data['IP'],"LoginID"=>$user,"LoginPW"=>$password,"Port"=>$data['Port'],"TradeID"=>"","CommPW"=>"");
                    $payload1 = json_encode(array(
                        'req' => 'Trade_Login',
                        'rid' => $this->build_rid_no(),
                        'para'=>$para
                    ));
                }
                $this->socket->send($payload1);
                $tmd = json_decode($this->socket->receive(),true);
                if(isset($tmd["event"])&&$tmd["event"]=="Trade_LoginEvent"){//判断是否推送事件 推送内容{"event":"Market_LoginEvent","rid":1525398637,"data":{"Result":0,"Speed":438,"ID":2,"Port":7709,"IP":"218.9.148.196"}}
                    $LoginMessage=$tmd;
                }else{//回传信息 {"ret":0,"rid":1525398637,"data":{"ID":0,"Wait":1}}
                    $LoginMessage=json_decode($this->socket->receive(),true);//推送事件
                }
                if($LoginMessage['data']['Result']==0){
                    $this->login_tid=$LoginMessage['data']['ID'];
                    $this->login=true;
                }
            }
        }else{
            $this->login_tid=0;
            $this->login=false;
        }
    }

    //level2行情登录
    public  function login_2($user,$password){
        $para = array("Net" => 0,"Server" => 8,"JsonType" => 0);
        $list=$this->_init($para);//初始化，8代表level2行情
        if($list['message']['data']['result']==0) {
            if($list['res']['ret']==0){//ret代表请求成功还是失败，0表示成功，非0表示失败
                $data = $list['res']['data'][0];
                $para = array("Server" => 8,"IP" => $data['IP'],"LoginID" => $user,"LoginPW" =>$password, "Port"=>$data['Port']);
                $LoginMessage=$this->trade_login($para);
                if($LoginMessage['data']['result']==0){
                    $this->login_tid=$LoginMessage['data']['ID'];
                    $this->login=true;
                }
            }
        }
    }
    //普通行情初始化并登录
    public function star(){
        $para = array("Net" => 0,"Server" => 1,"JsonType" => 0);
        $list=$this->_init($para);//初始化，"1"代表普通行情
        if($list['message']['data']['Result']==0) {
            if($list['res']['ret']==0){//ret代表请求成功还是失败，0表示成功，非0表示失败
                $data = $list['res']['data'][0];
                $para = array("Server" => 1, "TryConn" => 3,"IP" => $data['IP'], "Port"=>$data['Port']);
                $LoginMessage=$this->trade_login($para);
                if($LoginMessage['data']['Result']==0){
                    $this->hqlogin_tid=$LoginMessage['data']['ID'];
                    $this->hqlogin=true;
                }else{
                    $this->hqlogin_tid=0;
                    $this->hqlogin=false;
                }
            }
        }
    }
    //登录
    private function trade_login($para){
        $payload1 = json_encode(array(
            'req' => 'Trade_Login',
            'rid' => $this->build_rid_no(),
            'para'=>$para
        ));
        $this->socket->send($payload1);
        $this->socket->receive();
        $LoginMessage = json_decode($this->socket->receive(),true);
        return $LoginMessage;
    }
    //初始化
    private function _init($para,$rid=null){
        if(empty($rid)){$rid=$this->build_rid_no();}
        $payload = json_encode(array(
            'req' => 'Trade_Init',
            'rid' => $rid,
            'para'=> $para
        ));
        $this->socket->send($payload);
        $list['message']=null;
        $tmd_1 = json_decode($this->socket->receive(),true);
        $tmd_2 = json_decode($this->socket->receive(),true);
        if(isset($tmd_1["event"])){
            if($tmd_1["event"]=="Trade_ServerEvent"){
                $list['message'] =json_decode($this->socket->receive(),true);
            }else{
                $list['message'] =$tmd_1;
            }
            $list['res']=$tmd_2;
        }else{
            $list['res']=$tmd_1;
            $list['message']=$tmd_2;
        }
        return $list;
    }
    public function send($query){
        $this->socket->send($query);
    }
    public function receive(){
        return $this->socket->receive();
    }
}
class Transfer {
    protected $status=0;//0、单返回交易状态 2、已初始化状态 4、双返回交易状态 5、待退出状态 6、待登录状态 7、校验状态
    protected $trade_star;
    protected $trade_login;
    protected $sever;
    protected $trade_id=NAME;
    protected $option;
    protected $error=array('error'=>'unknown error');
    protected $login_arr;
    protected $hq_login;
    protected $trade_id_login=array();
    protected $trade_arr=array();

    public function __construct($option,$login_arr) {
        $this->option=$option;
        $this->login_arr=$login_arr;
        $this->server = new Server($this->option);
        $this->trade_star=new \Trade($this->option);
    }
    protected function init(){
        $this->trade_star->star();
        if($this->trade_star->hqlogin){
            $this->hq_login="Success";
        }else{
            $this->hq_login="Fail   ";
        }
        foreach ($this->login_arr as $k=>$v){
            $this->trade_login[$k]=new \Trade($this->option);
            $this->trade_login[$k]->login($v['LID'],$v['user'],$v['password'],$v['broker'],$v['clienver']);
            if($this->trade_login[$k]->login){
                array_push($this->trade_arr,$k);
                $this->trade_id_login[$k]="Success";
            }else{
                $this->trade_id_login[$k]="Fail   ";
            }
        }
    }
    public function run(){
        $this->init();
        $this->ui();
        $this->main();
    }
    protected function main(){
        $num=0;
        while ($cnn=$this->server->accept()) {
            $this->trade_id=NAME;
            $this->status = 0;
            try {
                while(1) {
                    //var_dump($this->server);
                    $message = $this->server->receive();
                    if($message === 'heart'&&$this->status === 0){
                        $date=$this->riqi();
                        $mt='{"req":"Trade_CheckStatus","rid":"12","para":{"Server" : 1}}';
                        $this->trade_star->send($mt);
                        $check=json_decode($this->trade_star->receive());
                        //if(isset($check->event)&&$check->event="Empty_error_event"){}
                        if(isset($check->data->Status)&&$check->data->Status===0){echo ':';}
                        else{
                            //var_dump($check);
                            if(isset($check->data->ErrInfo)){
                                echo iconv("UTF-8","GB2312//IGNORE",$check->data->ErrInfo);
                            }
                            $num++;
                            if($date&&$num!=20){echo $date;break;}
                            $num=0;
                            echo ',';
                            $this->trade_star=new \Trade($this->option);
                            $this->trade_star->star();
                        }
                        $ml='{"req":"Trade_CheckStatus","rid":"12","para":{"Server" : 2}}';
                        foreach ($this->login_arr as $k=>$v){
                            $this->trade_login[$k]->send($ml);
                            $check=json_decode($this->trade_login[$k]->receive());
                            if(isset($check->data->Status)&&$check->data->Status===0){echo ':';}else{
                                //var_dump($check);
                                if(isset($check->data->ErrInfo)){
                                    echo iconv("UTF-8","GB2312//IGNORE",$check->data->ErrInfo);
                                }
                                $num++;
                                if($date&&$num!=20){echo $date;break;}
                                $num=0;
                                echo ".";
                                $this->trade_login[$k]=new \Trade($this->option);
                                $this->trade_login[$k]->login($v['LID'],$v['user'],$v['password'],$v['broker'],$v['clienver']);
                            }
                        }
                        break;
                    }
                    echo date("m-d H:i:s",time())," Client: $message\n\n";
                    $tmd=json_decode($message);
                    if(isset($tmd->com)){
                        if($tmd->com=='trade_id'){
                            $tmd2=abs(intval($tmd->id));
                            if($tmd2===9999){$this->trade_id=$this->trade_arr[0];continue;}
                            if(in_array($tmd2,$this->trade_arr)){
                                $this->trade_id=$tmd2;
                            }else{
                                $this->error['error']="Non-existent trade_id";
                                continue;
                            }
                        }
                        continue;
                    }
                    if ($message === 'exit') {
                        echo microtime(true), " Client told me to quit.  Bye bye.\n";
                        echo microtime(true), " Close response: ", $this->server->close(), "\n";
                        echo microtime(true), " Close status: ", $this->server->getCloseStatus(),"\n";
                        exit;
                    }
                    if($message === 'ui'){
                        $this->ui();
                        break;
                    }
                    if($message === 'TradeArr'){
                        $answer=json_encode($this->trade_arr);
                        $this->server->send($answer);
                        echo date("m-d H:i:s",time())," server: $answer \n\n";
                        break;
                    }
                    if ($message === 'Dump headers') {
                        $this->server->send(implode("\r\n", $this->server->getRequest()));
                        break;
                    }
                    elseif ($auth = $this->server->getHeader('Authorization')) {
                        $this->server->send("$auth - $message", 'text', false);
                        break;
                    }
                    //status=4 普通行情已登录状态
                    if($this->status===4){
                        $this->trade_star->send($message);
                        $answer=$this->trade_star->receive();
                        if(json_decode($answer)->rid===json_decode($message)->rid){
                            $this->server->send($answer);
                        }else{
                            $answer=$this->trade_star->receive();
                            $this->server->send($answer);
                        }
                        $answer=iconv("UTF-8","GB2312//IGNORE",$answer);
                        echo date("m-d H:i:s",time())," server: $answer \n\n";
                        break;
                    }
                    if($message === 'star'){
                        $this->status=4;
                        continue;
                    }
                    //status=7 验证登录状态
                    if($this->status===7){
                        $tm7=json_decode($message);
                        if($tm7->operation=="CheckBroker"){
                            $login_tmd=new \Trade($this->option);
                            $login_tmd->login($tm7->LID,$tm7->user,$tm7->password,$tm7->broker,$tm7->clienver);
                            if($login_tmd->login){
                                $answer=true;
                                $this->server->send(json_encode($answer));
                            }else{
                                $answer=false;
                                $this->server->send(json_encode($answer));
                            }
                            echo date("m-d H:i:s", time()), " server: $answer \n\n";
                        }else{
                            $this->error="Data error";
                            $this->server->send(json_encode($this->error));
                            echo date("m-d H:i:s", time()), " server: $this->error \n\n";
                        }
                        unset($login_tmd);
                        break;
                    }
                    if($message === 'CheckBroker'){
                        $this->status = 7;
                        continue;
                    }
                    //status=6 待登录状态
                    if($this->status===6){
                        $tm6=json_decode($message);
                        if($tm6->operation==="BrokerLogin"){
                            $k=$tm6->id;
                            $this->trade_login[$k]=new \Trade($this->option);
                            $this->trade_login[$k]->login($tm6->LID,$tm6->user,$tm6->password,$tm6->broker,$tm6->clienver);
                            if($this->trade_login[$k]->login){
                                array_push($this->trade_arr,$k);
                                $this->trade_id_login[$k]="Success";
                                $num=intval($tm6->id);
                                $this->login_arr[$num]['LID']=$tm6->LID;
                                $this->login_arr[$num]['user']=$tm6->user;
                                $this->login_arr[$num]['password']=$tm6->password;
                                $this->login_arr[$num]['broker']=$tm6->broker;
                                $this->login_arr[$num]['clienver']=$tm6->clienver;
                                $answer=true;
                                $this->ui();
                            }else{
                                $this->trade_id_login[$k]="Fail   ";
                                $answer=false;
                            }
                            $this->server->send(json_encode($answer));
                            echo date("m-d H:i:s", time()), " server: $answer \n\n";
                        }else{
                            $this->error="Data error";
                            $this->server->send(json_encode($this->error));
                            echo date("m-d H:i:s", time()), " server: $this->error \n\n";
                        }
                        unset($tm6);
                        break;
                    }
                    if($message === 'BrokerLogin'){
                        $this->status = 6;
                        continue;
                    }
                    //status=5 待退出状态
                    if($this->status === 5){
                        $tm5=json_decode($message);
                        if(!empty($tm5->id)&&$tm5->operation=="BrokerOut"){
                            $id=intval($tm5->id);
                            unset($this->trade_login[$id]);
                            unset($this->trade_id_login[$id]);
                            unset($this->login_arr[$id]);
                            foreach ($this->trade_arr as $kk=>$vv){
                                if($vv===$id){
                                    unset($this->trade_arr[$kk]);
                                }
                            }
                            $answer=true;
                        }else{
                            $answer=false;
                        }
                        $this->server->send(json_encode($answer));
                        unset($tm5);
                        $this->ui();
                        break;
                    }
                    if($message === 'BrokerOut'){
                        $this->status = 5;
                        continue;
                    }
                    //status=2 待双返回交易状态
                    if ($this->status === 2 ) {
                        if($this->trade_id===NAME){
                            $this->server->send(json_encode($this->error));
                            break;
                        }
                        $this->trade_login[$this->trade_id]->send($message);
                        $answer=json_decode($this->trade_login[$this->trade_id]->receive());
                        if(!isset($answer->event)&&$answer->rid===$tmd->rid){
                            $this->server->send($answer);
                            $answer = iconv("UTF-8", "GB2312//IGNORE", $answer);
                            echo date("m-d H:i:s", time()), " server: $answer \n\n";
                            break;
                        }
                        $answer = json_decode($this->trade_login[$this->trade_id]->receive());
                        if(!isset($answer->event)&&$answer->rid===$tmd->rid){
                            $this->server->send($answer);
                            $answer = iconv("UTF-8", "GB2312//IGNORE", $answer);
                            echo date("m-d H:i:s", time()), " server: $answer \n\n";
                            break;
                        }
                        $answer = json_decode($this->trade_login[$this->trade_id]->receive());
                        if(!isset($answer->event)&&$answer->rid===$tmd->rid){
                            $this->server->send($answer);
                        }
                        $answer = iconv("UTF-8", "GB2312//IGNORE", $answer);
                        echo date("m-d H:i:s", time()), " server: $answer \n\n";
                        break;

                    }
                    if ($message === 'double') {
                        $this->status = 2;
                        continue;
                    }
                    //status=0 单返回交易状态
                    if ($this->status === 0 ) {
                        if($this->trade_id===NAME){
                            $this->server->send(json_encode($this->error));
                            break;
                        }
                        $this->trade_login[$this->trade_id]->send($message);
                        $answer = $this->trade_login[$this->trade_id]->receive();
                        if(json_decode($answer)->rid===json_decode($message)->rid){
                            $this->server->send($answer);
                        }else{
                            $answer = $this->trade_login[$this->trade_id]->receive();
                            $this->server->send($answer);
                        }
                        $answer = iconv("UTF-8", "GB2312//IGNORE", $answer);
                        echo date("m-d H:i:s", time()), " server: $answer \n\n";
                        break;
                    }
                    break;
                }
                $this->server->close_b();

            }
            catch (Exception $e) {
                echo "\n", microtime(true), " Client died:\n $e\n";
            }
        }
        exit;
    }
    protected  function festival(){
        $t0=date("Y",time());
        if(file_exists($t0.".txt")){
            $data=file_get_contents($t0.".txt");
            return  json_decode($data)->$t0;
        }else{
            $url="http://tool.bitefu.net/jiari/?d=".$t0;
            $d0=file_get_contents($url);
            file_put_contents($t0.".txt",$d0);
            return json_decode($d0)->$t0;
        }
    }
    protected function riqi(){
        $t=time()-strtotime(date("Y-m-d",time()));
        $t2=3600*9;//早盘开盘时间
        $t3=3600*11.5;//早盘停盘时间
        $t4=3600*12.9;//下午开盘时间
        $t5=3600*15;//默认下午停盘时间14:55
        if(!(($t>$t2 && $t<$t3)|| ($t>$t4 && $t<$t5))){
            return 5;//
        }
        $date=date('N', time());
        if($date>5){ //周六周日
            return $date;//
        }
        $t0=$this->festival();//返回节假日
        $array=array();
        if (is_object($t0)) {
            $n=0;
            foreach ($t0 as $key => $value) {
                $array[$n] = $key;
                $n++;
            }
        }else{
            $array=$t0;
        }
        if(in_array(date("md",time()),$array)){//如果是节假日
            return 8;
        }
        return false;
    }
    protected function ui(){
        echo "\n";
        echo "---------------------------".NAME."-----------------------------\n\n";
        echo "  PHP version:".PHP_VERSION."        ".NAME."  version:".VERSION ."\n\n";
        echo "  Server  port: ".$this->option['api_port']."        Server url:".$this->option['api_url']."\n\n";
        echo "  Client  port: ".$this->option['tran_port']."        Client url:".$this->option['tran_url']." \n\n";
        echo "  market__login: ".$this->hq_login."  ";
        foreach ($this->login_arr as $k=>$v){
            echo "  trade_".$k."_login: ".$this->trade_id_login[$k]."  ";
            if($k%2==1){echo "\n\n";}
        }
        echo "--------------------------------------------------------------------\n";
        echo iconv("UTF-8", "GB2312//IGNORE", "::正常 :,行情异常 :.交易异常 :5休市时间 :6周六 :7周日 :8节日 \n");
        echo "Press Ctrl-C to quit. Start success. Date: ".date('y-m-d H:i:s',time())."\n\n";
    }
}
