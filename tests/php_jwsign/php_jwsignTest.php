<?php
namespace php_jwsignTest;

use php_jwsign;
use php_jwsign\jwsign;
use PHPUnit\Framework\TestCase;

class php_jwsignTest extends TestCase{

  public function testGet(){
    $jwsign = new jwsign();
    $this->assertTrue($jwsign);
    //$this->assertEquals('Hello world', trim($curl->Body));
  }

  public function testPost(){}
}
