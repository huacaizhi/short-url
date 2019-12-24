<?php
/**
 * Created by PhpStorm.
 * User: huacaizhi
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/12/24
 * Time: 14:10
 */

namespace HuaCaiZhi\ShortUrlPackage\Driver;

use HuaCaiZhi\ShortUrlPackage\Contract\ShortUrlContract;
use HuaCaiZhi\ShortUrlPackage\Validator\MaiyUrlVerify;

class MaiyUrlDriver extends ShortUrlContract
{
    const CREATE_WEIBO_URL = 'http://maiyurl.cn/weibourl';
    const CREATE_TENCENT_URL = 'http://maiyurl.cn/tengxurl';
    const WEIBO = 'weibo';
    const TENCENT = 'tencent';
    public static $urlProvider = array(
        self::WEIBO => array(
            'url' => self::CREATE_WEIBO_URL,
            'txt' => '新浪微博短链接'
        ),
        self::TENCENT => array(
            'url' => self::CREATE_TENCENT_URL,
            'txt' => '腾讯短链接'
        ),
    );

    /**
     * create short url
     */
    public function shortLink()
    {
        $data = $this->getData();
        $bodys = MaiyUrlVerify::check($data);
        if (isset($bodys['code'])) {
            return TheDriver::apiReturn([
                'message' => $bodys['message']
            ]);
        }
        $result = TheDriver::curlRequest([
            'url' => $bodys,
        ]);
        if (!$result || !TheDriver::isUrl($result)) {
            return TheDriver::apiReturn([
                'message' => is_string($result)
                    ? $result
                    : TheDriver::DEFAULT_ERROR
            ]);
        }

        return TheDriver::apiReturn([
            'code' => TheDriver::EXE_SUCCESS,
            'message' => TheDriver::$okMessage,
            'data' => [
                'short_url' => $result,
                'long_url' => $data['long_url'],
            ],
        ]);
    }

    /**
     * query url
     */
    public function query()
    {
        return TheDriver::apiReturn([
            'message' => '该短链接服务商暂不支持还原短链接',
        ]);
    }
}