<?php
/**
 * Created by PhpStorm.
 * User: huacaizhi
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/11/12
 * Time: 16:01
 */

namespace HuaCaiZhi\ShortUrlPackage\Driver;

use HuaCaiZhi\ShortUrlPackage\Contract\ShortUrlContract;
use HuaCaiZhi\ShortUrlPackage\Validator\BaiDuVerify;

class BaiDuDriver extends ShortUrlContract
{
    const CREATE_URL = 'https://dwz.cn/admin/v2/create';
    const QUERY_URL = 'https://dwz.cn/admin/v2/query';
    public $token;

    /**
     * 百度创建短链接时返回代码
     */
    const BD_EXE_SUCCESS = 0;
    const BD_EXE_FAILURE = -1;
    const BD_LONG_URL_ERROR = -2;
    const BD_LONG_URL_UNSAFE = -3;
    const BD_INSIDE_ERROR = -4;
    const BD_NOT_SUPPORT_DOMAIN = -5;
    const BD_EXPIRATION_DATE_ERROR = -6;
    const BD_TOKEN_ERROR = -100;
    public static $createReturnCodeArray = array(
        self::BD_EXE_SUCCESS => '正常返回短网址',
        self::BD_EXE_FAILURE => '短网址生成失败',
        self::BD_LONG_URL_ERROR => '长网址不合法',
        self::BD_LONG_URL_UNSAFE => '长网址存在安全隐患',
        self::BD_INSIDE_ERROR => '内部错误',
        self::BD_NOT_SUPPORT_DOMAIN => '短网址服务目前不支持该域名',
        self::BD_EXPIRATION_DATE_ERROR => '有效期设置错误',
        self::BD_TOKEN_ERROR => 'Token验证失败',
    );

    /**
     * 百度查询短链接时返回代码
     */
    const BD_QUERY_EXE_SUCCESS = 0;
    const BD_QUERY_LONG_URL_ERROR = -1;
    const BD_QUERY_SHORT_URL_NOT_EXIST = -2;
    const BD_QUERY_SHORT_URL_ERROR = -3;
    public static $queryReturnCodeArray = array(
        self::BD_QUERY_EXE_SUCCESS => '正常返回短网址',
        self::BD_QUERY_LONG_URL_ERROR => '短网址对应的长网址不合法',
        self::BD_QUERY_SHORT_URL_NOT_EXIST => '短网址不存在',
        self::BD_QUERY_SHORT_URL_ERROR => '查询的短网址不合法',
        self::BD_TOKEN_ERROR => 'Token验证失败',
    );

    /**
     * create short url
     */
    public function shortLink()
    {
        $data = $this->getData();
        $bodys = BaiDuVerify::check($data);
        if (isset($bodys['code'])) {
            return TheDriver::apiReturn([
                'message' => $bodys['message']
            ]);
        }
        $url = self::CREATE_URL;
        $method = 'POST';
        $content_type = 'application/json';

        // 配置headers
        $headers = array('Content-Type:' . $content_type, 'Token:' . $data['token']);
        $result = TheDriver::curlRequest([
            'url' => $url,
            'method' => $method,
            'header' => $headers,
            'body' => $bodys,
        ]);
        $resultArray = json_decode($result, true);

        if (!$resultArray) {
            return TheDriver::apiReturn([
                'message' => TheDriver::DEFAULT_ERROR
            ]);
        }

        if (isset($resultArray['Code'])
            && $resultArray['Code'] == self::BD_EXE_SUCCESS
        ) {
            return TheDriver::apiReturn([
                'code' => TheDriver::EXE_SUCCESS,
                'message' => TheDriver::$okMessage,
                'data' => [
                    'short_url' => $resultArray['ShortUrl'],
                    'long_url' => $resultArray['LongUrl'],
                ],
            ]);
        } else {
            return TheDriver::apiReturn([
                'code' => TheDriver::EXE_FAILURE,
                'message' => isset(self::$createReturnCodeArray[$resultArray['Code']])
                    ? self::$createReturnCodeArray[$resultArray['Code']]
                    : TheDriver::DEFAULT_ERROR,
            ]);
        }
    }

    /**
     * query url
     */
    public function query()
    {
        $data = $this->getData();
        $bodys = BaiDuVerify::check($data, __FUNCTION__);
        if (isset($bodys['code'])) {
            return TheDriver::apiReturn([
                'message' => $bodys['message']
            ]);
        }
        $url = self::QUERY_URL;
        $method = 'POST';
        $content_type = 'application/json';

        // 配置headers
        $headers = array('Content-Type:' . $content_type, 'Token:' . $data['token']);
        $result = TheDriver::curlRequest([
            'url' => $url,
            'method' => $method,
            'header' => $headers,
            'body' => $bodys,
        ]);
        $resultArray = json_decode($result, true);

        if (!$resultArray) {
            return TheDriver::apiReturn([
                'message' => TheDriver::DEFAULT_ERROR
            ]);
        }

        if (isset($resultArray['Code'])
            && $resultArray['Code'] == self::BD_EXE_SUCCESS
        ) {
            return TheDriver::apiReturn([
                'code' => TheDriver::EXE_SUCCESS,
                'message' => TheDriver::$okMessage,
                'data' => [
                    'short_url' => $resultArray['ShortUrl'],
                    'long_url' => $resultArray['LongUrl'],
                ],
            ]);
        } else {
            return TheDriver::apiReturn([
                'code' => TheDriver::EXE_FAILURE,
                'message' => isset(self::$queryReturnCodeArray[$resultArray['Code']])
                    ? self::$queryReturnCodeArray[$resultArray['Code']]
                    : TheDriver::DEFAULT_ERROR,
            ]);
        }
    }
}