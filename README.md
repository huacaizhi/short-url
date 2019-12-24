ShortUrl
===

**ShortUrl** 是一个尽可能聚合市面上提供短链接服务应用的 `PHP` 包。

安装
-----
1. 环境要求:
   - php >= 5.4
   - cURL extension

2. 如果您通过 `composer` 管理您的项目依赖，可以在您的项目根目录运行：

        $ composer require huacaizhi/short-url

   或者在您的 `composer.json` 中声明对 `huacaizhi/short-url` for PHP的依赖：

        "require": {
            "huacaizhi/short-url": "0.2.0"
        }

   然后通过 `composer install` 安装依赖。`composer` 安装完成后，在您的 `PHP` 代码中引入依赖即可：

        require_once __DIR__ . '/vendor/autoload.php';


3. 下载SDK源码，在您的代码中引入SDK目录下的 `autoload.php` 文件：

        require_once '/path/to/shorturl/autoload.php';

用法
-----
1.创建短链接
```php
use HuaCaiZhi\ShortUrlPackage\Driver\BaiDuDriver;
use HuaCaiZhi\ShortUrlPackage\Service\ShortUrlService;

//PHP_VERSION >=5.5
$shortUrl = new ShortUrlService();
$result = $shortUrl->service(BaiDuDriver::class)
    ->boot(array(
        'token' => 'xxxx',
        'long_url' => 'http://www.baidu.com',
    ));
var_dump($result);


//PHP_VERSION >=5.4
$shortUrl = new ShortUrlService();
$result = $shortUrl->service((new BaiDuDriver()))
    ->boot(array(
        'token' => 'xxxx',
        'long_url' => 'http://www.baidu.com',
    ));
var_dump($result);
```

2.创建短链接并设置有效期
```php
use HuaCaiZhi\ShortUrlPackage\Driver\BaiDuDriver;
use HuaCaiZhi\ShortUrlPackage\Service\ShortUrlService;

//百度短链接目前支持两种:1年有效(1-year)和长期有效:默认(long-term)
$shortUrl = new ShortUrlService();
$result = $shortUrl->service(BaiDuDriver::class)
    ->boot(array(
        'token' => 'xxxx',
        'long_url' => 'http://www.baidu.com',
        'expire' => '1-year',
    ));
var_dump($result);
```

3.还原短链接
```php
use HuaCaiZhi\ShortUrlPackage\Driver\BaiDuDriver;
use HuaCaiZhi\ShortUrlPackage\Service\ShortUrlService;

$shortUrl = new ShortUrlService();
$result = $shortUrl->service(BaiDuDriver::class)
    ->boot(array(
        'token' => 'xxxx',
        'short_url' => 'https://dwz.cn/VQDLmcaR'
    ),BaiDuDriver::TO_QUERY);
var_dump($result);
```


常用的短网址服务商
-----
> https://dwz.cn/ (百度短网址) - 已支持

>> 百度短链接: https://dwz.cn

>> `token` 获取地址: https://dwz.cn/console/userinfo

> http://yourls.org/

> https://bitly.com/

> https://goo.gl/