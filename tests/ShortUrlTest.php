<?php
/**
 * Created by PhpStorm.
 * User: huacaizhi
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/11/11
 * Time: 19:58
 */

namespace Test;

use HuaCaiZhi\ShortUrlPackage\Driver\BaiDuDriver;
use HuaCaiZhi\ShortUrlPackage\Service\ShortUrlService;
use PHPUnit\Framework\TestCase;

class ShortUrlTest extends TestCase
{
    public function testHelloWorld()
    {
        //PHP_VERSION >=5.4
        $shortUrl = new ShortUrlService();
        $result = $shortUrl->service((new BaiDuDriver()))
            ->boot(array(
                'token' => 'xxxx',
                'long_url' => 'http://www.baidu.com',
            ));
        var_dump($result);


        //PHP_VERSION >=5.5
        $shortUrl = new ShortUrlService();
        $result1 = $shortUrl->service(BaiDuDriver::class)
            ->boot(array(
                'token' => 'xxxx',
                'long_url' => 'http://www.baidu.com',
            ));
        var_dump($result1);


        //1-year|long-term
        $shortUrl = new ShortUrlService();
        $result2 = $shortUrl->service(BaiDuDriver::class)
            ->boot(array(
                'token' => 'xxxx',
                'long_url' => 'http://www.baidu.com',
                'expire' => '1-year',
            ));
        var_dump($result2);


        $shortUrl = new ShortUrlService();
        $result3 = $shortUrl->service(BaiDuDriver::class)
            ->boot(array(
                'token' => 'xxxx',
                'short_url' => 'https://dwz.cn/VQDLmcaR'
            ),BaiDuDriver::TO_QUERY);
        var_dump($result3);
    }
}