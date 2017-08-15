<?php
namespace php_jwsignTest;
use php_jwsign;
use php_jwsign\jwsign;
use PHPUnit\Framework\TestCase;
class php_jwsignTest extends TestCase{

  public function testEncryptedData(){
    $Message = 'Hello';
    $KeyUrl = 'Private.key';
    $jws = new jwsign();
    $accesskey = file_get_contents($KeyUrl)
    $jwsign->SetPrivate($accesskey);
    $Pubkey = $jwsign->GetPubkey();
    $this->assertTrue(is_array($Pubkey));
    $this->assertEquals(2048, $Pubkey['bits']);
    $this->assertEquals('Helloworld', $Pubkey['kid']);
    $JsonStr = $jwsign->SignMessage($Message);
    $this->assertTrue(!is_bool($JsonStr));
    return array($Pubkey['pub'], $JsonStr);
  }

}
