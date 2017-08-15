<?php
namespace php_jwsignTest;

use php_jwsign;
use php_jwsign\jwsign;
use PHPUnit\Framework\TestCase;

class php_jwsignTest extends TestCase{

  public function testSign(){
    $jws = new jwsign();
    $Message = 'Helloworld';
    $privateFile = dirname(__FILE__).'/private.key';
    //$accesskey = file_get_contents($privateFile);
    $this->assertTrue(is_file($privateFile));
    // $jws = new jwsign();
    // $jws->SetPrivate($accesskey);
    // $Pubkey = $jws->GetPubkey();
    // $this->assertTrue(is_array($Pubkey));
    // $this->assertEquals(2048, $Pubkey['bits']);
    // $this->assertEquals('Helloworld', $Pubkey['kid']);
    // $JsonStr = $jws->SignMessage($Message);
    // $this->assertTrue(!is_bool($JsonStr));
    // return array($Pubkey['pub'], $JsonStr);
    $this->assertEquals(200, 100);
  }

  public function testVerify(){}
}
