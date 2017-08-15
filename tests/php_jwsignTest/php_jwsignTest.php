<?php
namespace php_jwsignTest;

use php_jwsign;
use php_jwsign\jwsign;
use PHPUnit\Framework\TestCase;

class php_jwsignTest extends TestCase{

  public function testGet(){
    $curl = new jwsign();
    $this->assertEquals(200, 100);
  }

  public function testPost(){}
}
