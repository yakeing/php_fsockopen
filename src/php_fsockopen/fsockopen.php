<?php
/*
 * fsockopen class
 * 是 socket 套接字链接的封装函数
 * @author http://weibo.com/yakeing
 * @version 2.0
 */
namespace php_fsockopen;
class fsockopen{
	private $stream = true; //阻塞模式
	private $timeout = 5; //连接/运行时间
	private $xport = 'tcp'; //连接协议 tcp
	//浏览器
	public $UserAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36';
	public $AcceptEncoding = ''; //压缩编码 gzip, deflate, sdch
	public $AcceptLanguage = 'zh-CN,zh;q=0.8'; //语言
	public $Connection = 'Close'; //持久连接 keep-alive 关闭 Close
	public $HttpVersion = 'HTTP/1.1'; //使用HTTP 1.0协议，服务器会主动放弃chunked编码
	public $OnContinue = false; // 关闭 HTTP/1.1 100 Continue 返回 可能造成 HTTP/1.1 417 Expectation Failed
	public $Content_Type = 'application/x-www-form-urlencoded;charset=utf-8'; //提交表单方式
	//构造函数
	public function __construct(){
		function_exists('fsockopen') or $this->ERR('php需要fsockopen函数支持或被关闭', false);
	} //END __construct
	//错误处理
	private function ERR($str, $KeepRunning=true){
		if($KeepRunning){
			echo "<b>发现异常错误:<b><br />\n".$str;
		}else{
			throw new Exception($str); //抛出异常
		}
	} //END ERR
	//初始化 $timeout=时长, $xport=协议, $stream =阻塞模式
	public function init($timeout='', $xport='', $stream = ''){
		if(!empty($xport)){
			$this->xport = $xport;
		}
		//获取已注册的套接字传输协议列表
		if(array_search($this->xport, stream_get_transports()) === false){
			$this->ERR('服务器不支持 ('.$this->xport.') 传输协议!', false);
		}
		if(is_bool($stream)){
			$this->stream = $stream;
		}
		if(is_int($timeout) && 0 !=$timeout){
			$this->timeout = $timeout;
		}
	} //END init
	//建立新通道连接
	private function NewChannel($url){
		$errno = 0;
		$errstr = '';
		$purl = parse_url($url);
		if($this->xport != 'tcp'){
			$xport = $this->xport.'://';
			if(isset($purl['host'])){
				$host = $purl['host'];
			}else{
				$host = $purl['path'];
			}
		}else{
			$xport = ($purl['scheme'] == 'https') ? 'ssl://' : ''; // tls://
			$host = $purl['host'];
		}
		if(!isset($purl['port']) || empty($purl['port'])){
			if($purl['scheme'] == 'https'){
				$port = 443;
			}else{
				$port = 80;
			}
		}else{
			$port = $purl['port'];
		}
		// $port 使用-1表示不使用端口,例如 unix:// 资源
		// $timeout  连接时间
		$fp = fsockopen($xport.$host, $port, $errno, $errstr, $this->timeout);
		if(false === $fp){
			$this->ERR('连接错误:('.$errno.') '.$errstr, false);
		}
		//关闭阻塞模式
		if($this->stream === false){
			if(!stream_set_blocking($fp,0)){
				$this->ERR('错误：未能关闭阻塞模式!');
			}
		}
		$query = isset($purl['query'])?'?'.$purl['query']:'';
		$path = isset($purl['path'])?$purl['path']:'/';
		return array($fp, $path.$query, $purl);
	} //END NewChannel
	//发送 POST 文件
	public function POST_FILE($Url, $File, $Referer='', $Cookie=''){
		$file_array = is_array($File) ? $File : array($File);
		srand((double)microtime()*1000000);
		$boundary = "--------------------------".substr(md5(rand(0,32000)),0,10);
		$data = "--".$boundary;
		for($i=0;$i<count($file_array);$i++){
			$FileType = pathinfo($file_array[$i], PATHINFO_EXTENSION | PATHINFO_FILENAME);
			switch($FileType){
				case 'gif':
					$content_type = "image/gif";
				break;
				case 'png':
					$content_type = "image/png";
				break;
				case 'jpg':
				case 'jpeg':
					$content_type = "image/jpg";
				break;
				default:
					$content_type = "application/octet-stream";
			}
			$content_file = join('', file($file_array[$i]));
			$data.= "\r\nContent-Disposition: form-data; name=\"file".($i+1)."\"; filename=\"".$file_array[$i]."\"";
			$data.= "\r\nContent-Type: ".$content_type;
			$data.= "\r\n\r\n".$content_file."\r\n--".$boundary;
		}
		$data.="--\r\n\r\n";
		$ContentType = 'multipart/form-data; boundary='.$boundary;
		return $this->POST($Url, $data, $Referer, $Cookie, $ContentType);
	} //END POST_ FILE
	//POST 方式
	public function POST($Url, $Content, $Referer='', $Cookie='', $ContentType=''){
		$ContentType = empty($ContentType) ? $this->Content_Type : $ContentType;
		list($fp, $path, $purl) = $this->NewChannel($Url);
		$header = "POST ".$path." ".$this->HttpVersion."\r\n";
		$header .= "Host: ".$purl['host']."\r\n";
		$header .= "Connection: ".$this->Connection."\r\n"; //持久连接
		$header .= "Content-Length: ".strlen($Content)."\r\n";
		$header .= "Origin: ".$purl['scheme']."://".$purl['host']."\r\n";
		// $header .= "X-Requested-With: XMLHttpRequest\r\n"; //AJax异步请求
		$header .= "User-Agent: ".$this->UserAgent."\r\n"; //浏览器
		$header .= "Content-Type: ".$ContentType."\r\n"; //提交方式
		$header .= "Accept: */ *\r\n";
		if($this->OnContinue === false){ //防止返回 HTTP/1.1 100 Continue
			$header .= "Expect:\r\n";
		}
		if(!empty($Referer)){
			$header .= "Referer: ".$Referer."\r\n"; //来源
		}
		if(!empty($this->AcceptEncoding)){
			$header .= "Accept-Encoding: ".$this->AcceptEncoding."\r\n"; //压缩编码
		}
		$header .= "Accept-Language: ".$this->AcceptLanguage."\r\n"; //语言
		if(!empty($Cookie)){
			$header .= "Cookie: ".$Cookie."\r\n";
		}
		$header .= "\r\n";
		return $this->fwrite_out($fp, $header.$Content);
	} //END POST
	//GET 方式
	public function GET($Url, $Referer='', $Cookie=''){
		list($fp, $path, $purl) = $this->NewChannel($Url);
		$header = "GET ".$path." ".$this->HttpVersion."\r\n";
		$header .= "Host: ".$purl['host']."\r\n";
		$header .= "Connection: ".$this->Connection."\r\n"; //持久连接
		$header .= "User-Agent: ".$this->UserAgent."\r\n"; //浏览器
		$header .= "Accept: */*\r\n";
		if($this->OnContinue === false){ //防止返回 HTTP/1.1 100 Continue
			$header .= "Expect:\r\n";
		}
		if(!empty($Referer)){
			$header .= "Referer: ".$Referer."\r\n"; //来源
		}
		if(!empty($this->AcceptEncoding)){
			$header .= "Accept-Encoding: ".$this->AcceptEncoding."\r\n"; //压缩编码
		}
		$header .= "Accept-Language: ".$this->AcceptLanguage."\r\n"; //语言
		if(!empty($Cookie)){
			$header .= "Cookie: ".$Cookie."\r\n";
		}
		$header .= "\r\n";
		return $this->fwrite_out($fp, $header);
	} //END GET
	//处理 Chunked 分块字段 Transfer-Encoding:chunked
	private function DecodeChunked($Body){
		$ret = '';
		$i=$chunk_size=1;
		while($chunk_size > 0 && $i < 100){ //最多100个分块防止死循环
			$footer_position = strpos($Body, "\r\n");//查找一个footer位置
			$chunk_size = (integer)hexdec(substr($Body, 0, $footer_position));//十六转十进制
			$NewBody = substr($Body, $footer_position+2); //全部(去除footer标识部分)
			$ret .= substr($NewBody,0, $chunk_size); //本次
			$Body = substr($NewBody, $chunk_size+2); //剩余
			++$i;
		}
		return $ret;
	} //END DecodeChunked
	//获取数据包
	private function fwrite_out($fp, $data){
		$ret='';
		fwrite($fp, $data);
		while (!feof($fp)){
			$ret .= fgets($fp, 4096);
		}
		fclose($fp);
		list($RetHeader, $RetBody) = explode("\r\n\r\n", $ret, 2);
		if($this->OnContinue && preg_match('/^HTTP\/1\.[1|0]\s100\sContinue/i', $RetHeader)){ //处理 HTTP/1.1 100 Continue 数据
			list($RetHeader, $RetBody) = explode("\r\n\r\n", $RetBody, 2);
		}
		if(preg_match('/Transfer-Encoding:\s?chunked/i', $RetHeader)){ //检测是否使用 分块字段
			$RetBody = $this->DecodeChunked($RetBody);
		}
		return array('header' => $RetHeader, 'body' => trim($RetBody));
	} //END fwrite_out
}// CLASS END
