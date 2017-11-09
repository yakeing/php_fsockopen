# php_fsockopen

[fsockopen](http://www.php.net/manual/zh/function.fsockopen.php) is socket package function, in addition to the basic commonly used TCP:// communication function, it also has other powerful communication function, UDP:// unix:// / udg:// and so on various protocols.

You can use  [stream_get_transports](http://php.net/manual/zh/function.stream-get-transports.php) to get the current server registered socket transfer protocol list to determine whether to support the need to use the agreement.

### Travis CI

[![Travis-ci](https://api.travis-ci.org/yakeing/php_fsockopen.svg)](https://travis-ci.org/yakeing/php_fsockopen)

### Packagist

[![Version](http://img.shields.io/packagist/v/yakeing/php_fsockopen.svg)](https://packagist.org/packages/yakeing/php_fsockopen)
[![Downloads](http://img.shields.io/packagist/dt/yakeing/php_fsockopen.svg)](https://packagist.org/packages/yakeing/php_fsockopen)

### Github

[![Downloads](https://img.shields.io/github/downloads/yakeing/php_fsockopen/total.svg)](https://github.com/yakeing/php_fsockopen)
[![Size](https://img.shields.io/github/size/yakeing/php_fsockopen/src/php_fsockopen/fsockopen.php.svg)](https://github.com/yakeing/php_fsockopen)
[![tag](https://img.shields.io/github/tag/yakeing/php_fsockopen.svg)](https://github.com/yakeing/php_fsockopen)
[![Language](https://oauth.applinzi.com/Badge/4D4D4D/Language/F66000/PHP/image.svg)](https://github.com/yakeing/php_fsockopen)
[![License](https://oauth.applinzi.com/Badge/4D4D4D/License/007EC6/MPL-2.0/image.svg)](https://github.com/yakeing/php_fsockopen)

BY: [yakeing](http://weibo.com/yakeing)

### Installation

Use [Composer](https://getcomposer.org) to install the library.

```

    $ composer require yakeing/php_fsockopen

```

### Initialization parameter

- [x] Sample：
```php
    $fs = new fsockopen();
    $ret = $fs->init(
        10, //Running time / sec (optional)
        tcp, //transport protocol (optional)
        true //Blocking mode switch (optional)
        );
```

### Get network resources

- [x] Sample：
```php
    $ret = $fs->GET(
        $Url , //Destination URL
        $Referer , //Forge Referer (optional)
        $Cookie //This Cookie (optional)
    );
```


### POST Submit Form

- [x] Sample：
```php
    $ret = $fs->POST(
        $Url , //Destination URL
        $Content , //Submit content: key/vvalue&...
        $Referer , //Forge Referer (optional)
        $Cookie, //This Cookie (optional)
        $ContentType //Submission method (optional)
    );
```

### POST File

- [x] Sample：
```php
    $ret = $fs->POST_FILE(
        $Url , //Destination URL
        $File, //File OR Picture address: ['01.jpg','02.jpg',...]
        $Referer , //Forge Referer (optional)
        $Cookie, //This Cookie (optional)
    );
```

Donate
---
Your donation makes CODE better.

 Bitcoin (比特币赞助)

 1Ff2hTfr4EioWv2ZDLKTedUiF9wBBVYSbU

 ![Bitcoin](https://oauth.applinzi.com/QR/230/bitcoin%3a1Ff2hTfr4EioWv2ZDLKTedUiF9wBBVYSbU/Bitcoin.png)

 WeChat (微信赞助)

 ![WeChat](https://oauth.applinzi.com/QR/230/https%3a%7C%7Cpayapp.weixin.qq.com%7Cqr%7CAQFjACEumLq80lLV2aIgLwjh*t%3dCwAK%25wechat_pay/WeChat.png)

 Alipay (支付宝赞助)

 ![Alipay](https://oauth.applinzi.com/QR/230/HTTPS%3a%7C%7CQR.ALIPAY.COM%7CTSX082709YGHVXYUQCWKD6/Alipay.png)
