<?php
/*
  * Curl Class
  *
  * @author http://weibo.com/yakeing
  * @version 2.1
  * Prompt: CertUrl The server may not contain root / chain SSL certificates, causing authentication errors
  * 注意: CertUrl 服务器可能不包含根/链SSL证书,导致身份验证错误
  * Prompt: When you open the {HeadOut} option, GET/POST returns only false
  * 注意:当您打开{HeadOut}选项时,GET / POST仅返回false
  *
  * Upload Files
  * $post = array('upload' =>curl_file_create(''img.jpg','image/jpeg','pic.jpg'));
  * $post = array('upload'=>new CURLFile(realpath('img.jpg'))); php 5.5 Edition
  */
namespace php_curl;
class curl{
	public $Headers = array();
	public $Body = '';
	public $HttpCode = 0;
	public $HttpError = '';
	public $HttpUrl = ''; //有效发送地址(最后一个)
	public $HeadersOut = ''; //发送的头部
	public $HttpCookie = array(); //返回 Cookie

	public $Cookie = array(); //设置Cookie
	public $Timeout = 5; //运行时间
	public $Referer = ''; //伪装来源
	public $CertUrl = ''; //HTTPS地址SSL证书
	public $HeadersOn = false; //输出头部信息
	public $HeaderFunction = false; //回调函数(头部)
	public $HeadOut = false; //回调函数(只输出头部)
	public $LocationQuantity = 0; //自动重定向次数(301/302)
	public $Encoding = 'deflate, gzip'; //压缩 1.identity、2.deflate, gzip
	public $UserAgent = 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';

	//Constructor
	public function __construct(){
		if(function_exists('curl')){
			throw new Exception('php需要Curl函数支持或被关闭');
		}
	} //END __construct

	//Get Network Resources
	public function Get($Url, $Header = array()){
		$Options = $this->Options($Url, $Header);
		//$Options[CURLOPT_HTTPGET] = true; //默认
		return $this->Http($Options);
	} //NDE Get

	//Send Data Packet
	public function Post($Url, $Vars, $Header = array()){
		$Options = $this->Options($Url, $Header);
		$Options[CURLOPT_POST] = true;
		$Options[CURLOPT_POSTFIELDS] = $Vars;
		return $this->Http($Options);
	} //END Post

	//Curl Options
	private function Options($Url, $Header){
		$Options = array(
			CURLOPT_URL => $Url, //URL 地址
			CURLOPT_USERAGENT => $this->UserAgent, //客户UA标示
			CURLOPT_TIMEOUT => $this->Timeout, //运行秒
			CURLOPT_CONNECTTIMEOUT => $this->Timeout, //连接秒
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, //http 1.1版本
			CURLOPT_RETURNTRANSFER => true, //返回文件流
			//CURLOPT_COOKIESESSION => true, //session cookie
			CURLINFO_HEADER_OUT => true //追踪句柄的请求
		);
		//不输出Body(无法接收cookie)
		//$Options[CURLOPT_NOBODY] = true;
		if(!empty($Header)){//设置头部信息
			$Options[CURLOPT_HTTPHEADER] = $Header;
		}
		if(strrpos($Url, 'https://') === 0){//https模式
			if(empty($this->CertUrl)){
				$Options[CURLOPT_SSL_VERIFYPEER] = false;//https安全
				$Options[CURLOPT_SSL_VERIFYHOST] = false;//证书检查
			}else{
				$Options[CURLOPT_SSL_VERIFYPEER] = true;//https安全
				$Options[CURLOPT_SSL_VERIFYHOST] = 2;//严格认证
				$Options[CURLOPT_CAINFO] = $this->CertUrl;//证书地址
			}
		}
		if(is_int($this->LocationQuantity) && $this->LocationQuantity > 0){//递归 自动重定向次数(301/302)
			$Options[CURLOPT_FOLLOWLOCATION] = true;//重定向
			$Options[CURLOPT_AUTOREFERER] = true;//重定向自动设置Referer:信息
			$Options[CURLOPT_MAXREDIRS] = $this->LocationQuantity; //重定向次数
		}
		if(!empty($this->Cookie)){//Cookie信息
			$Options[CURLOPT_COOKIE] = $this->Cookie;
		}
		if(!empty($this->Referer)){//伪装来源
			$Options[CURLOPT_REFERER] = $this->Referer;
		}
		if(!empty($this->Encoding)){//压缩
			$Options[CURLOPT_ENCODING] = $this->Encoding;
		}
		if($this->HeadersOn === true){//返回头部
			$Options[CURLOPT_HEADER] = true;
		}
		if($this->HeaderFunction === true){//回调函数(获取头部)
			$Options[CURLOPT_HEADERFUNCTION] = array($this, 'HeaderFunction');
		}
		return $Options;
	}

	// Header Function
	private function HeaderFunction($thising, $header){
		$i = strpos($header, ':');
		if(!empty($i)){
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			if($key == 'set_cookie'){
				list($k, $v) = explode('=', strstr($value, ';', true), 2);
				$this->HttpCookie[$k] =  $v;
			}else{
				$this->Headers[$key] = $value;
			}
		}else if(!empty(trim($header))){
			$this->Headers['HTTP'][] = $header;
		}else if($this->HeadOut){
			return 0;
		}
		return strlen($header);
	} //END HeaderFunction

	//Establish Communication Connection
	private function Http($options){
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$ResUlt = curl_exec($ch);
		//获取 header  部分的大小
		$Header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		//最后一个有效的URL地址
		$this->HttpUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		//发送的头部请求
		$this->HeadersOut = curl_getinfo($ch, CURLINFO_HEADER_OUT );
		//获取最后一个代码
		$this->HttpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//获取Content-Type:值
		//$ContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE );
		if($ResUlt === false || curl_errno($ch) !== 0){
			$this->error = curl_error($ch);
			curl_close($ch);
			return false;
		}else{
			$this->Body = $ResUlt;
			curl_close($ch);
			return true;
		}
	} //END Http

}
