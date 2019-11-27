<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui <1690611599@qq.com>
// +----------------------------------------------------------------------
namespace app\market\home;
use think\Controller;
class Heart// extends Common{
{
    protected $socket;
    protected $is_connected = false;
    protected $socket_uri;
    protected $options;

    public function __construct() {
        if (!ini_get('date.timezone')) date_default_timezone_set('Asia/Shanghai');
        $this->options['timeout'] = 90;
        $this->options['fragment_size'] = 4096;
        $this->options['api_url'] = 'ws://132.232.16.116';
        $this->options['api_port'] = '8282';
        $this->socket_uri = $this->options['api_url'].":".$this->options['api_port'];
    }
    public function index(){
        $this->send('heart');
        $view = new \think\View();
        return $view->fetch('heart/index');
    }
    public function heart(){
        $this->send('heart');
        return ;
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
        stream_set_timeout($this->socket, 10);
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
    protected static $opcodes = array(
        'continuation' => 0,
        'text'         => 1,
        'binary'       => 2,
        'close'        => 8,
        'ping'         => 9,
        'pong'         => 10,
    );
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
    protected function write($data) {
        $written = fwrite($this->socket, $data);
        if ($written < strlen($data)) {
            throw new Exception(
                "Could only write $written out of " . strlen($data) . " bytes."
            );
        }
    }
}