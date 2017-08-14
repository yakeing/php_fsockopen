# JWSign
php Json Wed Sign class

-----

#### Travis CI

[![Travis-ci](https://api.travis-ci.org/yakeing/php_jwsign.svg)](https://travis-ci.org/yakeing/php_jwsign)

#### Packagist

[![Version](http://img.shields.io/packagist/v/yakeing/php_jwsign.svg)](https://packagist.org/packages/yakeing/php_jwsign)
[![Downloads](http://img.shields.io/packagist/dt/yakeing/php_jwsign.svg)](https://packagist.org/packages/yakeing/php_jwsign)

#### Github

[![Downloads](https://img.shields.io/github/downloads/yakeing/php_jwsign/total.svg)](https://github.com/yakeing/php_jwsign)
[![Size](https://img.shields.io/github/size/yakeing/php_jwsign/src/php_jwsign/jwsign.php.svg)](https://github.com/yakeing/php_jwsign)
[![tag](https://img.shields.io/github/tag/yakeing/php_jwsign.svg)](https://github.com/yakeing/php_jwsign)
[![Language](https://oauth.applinzi.com/SvgLabel/4D4D4D/Language/F66000/PHP/image.svg)](https://github.com/yakeing/php_jwsign)
[![License](https://oauth.applinzi.com/SvgLabel/4D4D4D/License/007EC6/MPL-2.0/image.svg)](https://github.com/yakeing/php_jwsign)

BY: [yakeing](http://weibo.com/yakeing)

### Installation

Use [Composer](https://getcomposer.org) to install the library.

```

    $ composer require yakeing/php_jwsign

```

### JWSign init

---

```php

    $jwsign = new jwsign();
    $jwsign->SetPrivate($accesskey);
    
```

### Get Pubkey

---

```php

    $Pubkey = $jwsign->GetPubkey();
    
    var_dump($Pubkey);
    array(3) {
        ["pub"]=>string(451) "-----BEGIN PUBLIC KEY-----\nMIIBIjA....NjQIDAQA\n-----END PUBLIC KEY----"
        ["bits"]=>int(2048)
        ["kid"]=>string(43) "cjbdM-CeRfP...5BNYQuksIIgmk"
    }
    
```

### Sign Message

---

```php

    $Message = base64_encode('
        {
            "method":"pay",
            "charset":"utf-8",
            "version":"1.0",
            "token":"NAM...YgV"
        }
    ');
    
    $JsonStr = $jwsign->SignMessage($Message);
    
    var_dump($JsonStr);
    string(557) "{
        "message":"eyJtZXRiO...Z1YifQ==",
        "nonce":"MmlhaDE1MD...MTgwLjEwNDc1OTAw",
        "kid":"cjOdM-CORfPGa...j-0I5BNYQuksIIgmk",
        "sign":"hXvBULK2wSroVFZ...-HYHG7l8Epixikg"
        }"
    
```

### Pubkey Verify

---

```php

    $value = '{
        "message":"eyJtZXRiO...Z1YifQ==",
        "nonce":"MmlhaDE1MD...MTgwLjEwNDc1OTAw",
        "kid":"cjOdM-CORfPGa...j-0I5BNYQuksIIgmk"
        }';
    $sign = 'hXvBULK2wvSroVFZ...-HKbHGDYHG7l8Epixikg';
    $pub = '-----BEGIN PUBLIC KEY-----\nMIIBIjA....NjQIDAQA\n-----END PUBLIC KEY----';
    
    $Str = $jwsign->PubkeyVerify($value, $sign, $pub);
    
    var_dump($Str);
    bool(true)
    
```

### Get Message

---
```php

    $value = '{
        "message":"eyJtZXRiO...Z1YifQ==",
        "nonce":"MmlhaDE1MD...MTgwLjEwNDc1OTAw",
        "kid":"cjOdM-CORfPGa...j-0I5BNYQuksIIgmk"
        }';
    $Str = json_decode($value, true);
    
    var_dump(base64_decode($Str['message']));
    string(100) "{
            "method":"pay",
            "charset":"utf-8",
            "version":"1.0",
            "token":"NAM...YgV"
        }"
    
```

Donate
---
Your donation makes CODE better.

 Bitcoin (比特币赞助)

 1Ff2hTfr4EioWv2ZDLKTedUiF9wBBVYSbU

 ![Bitcoin](https://oauth.applinzi.com/QR/230/bitcoin%3a1Ff2hTfr4EioWv2ZDLKTedUiF9wBBVYSbU/Bitcoin.png)

 WeChat (微信赞助)

 ![WeChat](https://oauth.applinzi.com/QR/230/wxp%3a%7C%7Cf2f0SOGAUjQ1ALzigoyN7nW8tK68D2oeU3YO/WeChat.png)

 Alipay (支付宝赞助)

 ![Alipay](https://oauth.applinzi.com/QR/230/HTTPS%3a%7C%7CQR.ALIPAY.COM%7CTSX082709YGHVXYUQCWKD6/Alipay.png)
 
