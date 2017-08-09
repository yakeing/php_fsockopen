<?php
/**
	* Json Wed Sign (RSA)
	*
	* @author http://weibo.com/yakeing
	* @version 1.0
	* Extension: Openssl
 **/
class JWSign{
	public $Develop = false;
	public $KeyId = '';

	//construct
	public function __construct(){
		extension_loaded('openssl') or die('Openssl extensions support is required');
	}//END __construct

	//Set Private
	public function SetPrivate($key, $Pass=null){
		return ($this->KeyId = openssl_pkey_get_private($key, $Pass))? true : false;
	}//END SetPrivate

	//Get Pubkey
	public function GetPubkey(){
		$details= openssl_pkey_get_details($this->KeyId);
		return array('pub' => $details['key'], 'bits' => $details['bits'], 'kid' => $this->PrivkeySign());
	}//END Get Pubkey

	// Sign Message
	public function SignMessage($str){
		$nonce = $this->ReplayNonce();
		$kid = $this->PrivkeySign();
		$ret = array('message' => $str, 'nonce' => $nonce, 'kid' => $kid);
		// OPENSSL_ALGO_SHA256
		if(openssl_sign(json_encode($ret), $sign, $this->KeyId, 'sha256')){
			$sign = $this->UrlBase64Encode($sign);
			$ret['sign'] = $sign;
			return json_encode($ret);
		}
		return false;
	}//END SignMessage

	//Pubkey Verify
	public function PubkeyVerify($str, $sign, $PubKey){
		if(!is_string($str) || !is_string($sign) || !is_string($PubKey)) return false;
		$sign = $this->UrlBase64Decode($sign);
		if($PubKeyId = openssl_pkey_get_public($PubKey)){//openssl_get_publickey
			return (openssl_verify($str, $sign, $PubKeyId, 'sha256') === 1);
		}
	} //NED PubkeyVerify


	// JSON Web Key
	private function JWK(){
		$key_info = openssl_pkey_get_details($this->KeyId);
		if(isset($key_info['rsa'])){
			$jwk = array(
				'e' => $this->UrlBase64Encode($key_info['rsa']['e']),
				'kty' => 'RSA',
				'n' => $this->UrlBase64Encode($key_info['rsa']['n'])
			);
		// }else if($key_info['ec']){ //ECC
		}else{
			exit('Not a valid RAS Private');
			return false;
		}
		return json_encode($jwk);
	}//END JWK

	// Sign Kid
	private function PrivkeySign(){
		$jwk = $this->JWK();
		return $this->UrlBase64Encode(openssl_digest($jwk, 'sha256', true));
	}//END PrivkeySign

	//Replay Nonce
	private function ReplayNonce(){
		list($t1, $t2) = explode(' ', microtime());
		$ranstr = $this->RandStr(4);
		return $this->UrlBase64Encode($ranstr.$t2.$t1);
	}//END ReplayNonce

	// Random code
	private function RandStr($length){
		$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$len = strlen($str)-1;
		$ret = '';
		for($i=0; $i < $length; ++$i){
			$ran = mt_rand(0, $len);
			$ret .= $str[$ran];
		}
		return $ret;
	}//END Random code

	// URL Base64 Encode
	private function UrlBase64Encode($str){
		$ret = base64_encode($str);
		return str_replace(array('+', '/', '='), array('-', '_', ''), $ret);
	}//END UrlBase64Encode

	// URL Base64 Decode
	private function UrlBase64Decode($str){
		$ret64 = str_replace(array('-', '_'), array('+', '/'), $str);
		return base64_decode($ret64);
	}//END UrlBase64Decode

	//destruct
	public function __destruct(){
		if(is_resource($this->KeyId)){
			openssl_free_key($this->KeyId);
		}
	} //END destruct
}
