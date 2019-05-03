# php_fsockopen

[fsockopen](http://www.php.net/manual/zh/function.fsockopen.php) is socket package function, in addition to the basic commonly used TCP:// communication function, it also has other powerful communication function, UDP:// unix:// / udg:// and so on various protocols.

You can use  [stream_get_transports](http://php.net/manual/zh/function.stream-get-transports.php) to get the current server registered socket transfer protocol list to determine whether to support the need to use the agreement.

### Travis CI

[![Travis-ci](https://api.travis-ci.org/yakeing/php_fsockopen.svg)](https://travis-ci.org/yakeing/php_fsockopen)

### Packagist

[![Version](http://img.shields.io/packagist/v/yakeing/php_fsockopen.svg)](https://github.com/yakeing/php_fsockopen/releases)
[![Downloads](http://img.shields.io/packagist/dt/yakeing/php_fsockopen.svg)](https://packagist.org/packages/yakeing/php_fsockopen)

### Github

[![Downloads](https://img.shields.io/github/downloads/yakeing/php_fsockopen/total.svg)](https://github.com/yakeing/php_fsockopen)
[![Size](https://img.shields.io/github/size/yakeing/php_fsockopen/src/php_fsockopen/fsockopen.php.svg)](https://github.com/yakeing/php_fsockopen/blob/master/src/php_fsockopen/fsockopen.php)
[![tag](https://img.shields.io/github/tag/yakeing/php_fsockopen.svg)](https://github.com/yakeing/php_fsockopen/releases)
[![Language](https://img.shields.io/github/license/yakeing/php_fsockopen.svg)](https://github.com/yakeing/php_fsockopen/blob/master/LICENSE)
[![Php](https://img.shields.io/github/languages/top/yakeing/php_fsockopen.svg)](https://github.com/yakeing/php_fsockopen)

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

 [Bitcoin](https://btc.com/1FYbZECgs3V3zRx6P7yAu2nCDXP2DHpwt8) (比特币赞助)

 1FYbZECgs3V3zRx6P7yAu2nCDXP2DHpwt8

 ![Bitcoin](https://raw.githubusercontent.com/yakeing/Content/master/Donate/Bitcoin.png)

 WeChat (微信赞助)

 ![WeChat](https://raw.githubusercontent.com/yakeing/Content/master/Donate/WeChat.png)

 Alipay (支付宝赞助)

 ![Alipay](https://raw.githubusercontent.com/yakeing/Content/master/Donate/Alipay.png)

Author
---

weibo: [yakeing](https://weibo.com/yakeing)

twitter: [yakeing](https://twitter.com/yakeing)
