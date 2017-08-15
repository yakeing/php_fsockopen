<?php
namespace php_jwsignTest;

use php_jwsign;
use php_jwsign\jwsign;
use PHPUnit\Framework\TestCase;

class php_jwsignTest extends TestCase{

  public function testSign(){
    $jws = new jwsign();
    $Message = base64_encode('
        {
            "method":"pay",
            "charset":"utf-8",
            "version":"1.0",
            "token":"074zyzi52z4JYBXSBspgyDo"
        }
    ');
    $privateFile = dirname(__FILE__).'/private.key';
    //$this->assertTrue(is_file($privateFile));
    $accesskey = file_get_contents($privateFile);
    $jws = new jwsign();
    $jws->SetPrivate($accesskey);
    $Pubkey = $jws->GetPubkey();
    $JsonStr = $jws->SignMessage($Message);
    $this->assertEquals(2048, $Pubkey['bits']);
    $this->assertTrue(is_string($JsonStr));
    return array($jws, $Pubkey['pub'], $JsonStr);
  }

  public function testVerify($args){
    list($jws, $pub, $JsonStr) = $args;
    $arrayStr = json_decode($JsonStr, true);
    $this->assertTrue(isset($arrayStr['sign']));
    $sign = $arrayStr['sign'];
    unset($arrayStr['sign']);
    $value = json_encode($arrayStr);
    $Verify = $jws->PubkeyVerify($value, $sign, $pub);
    $this->assertTrue($Verify);
  }
}
