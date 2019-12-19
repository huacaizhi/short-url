<?php
/**
 * Created by PhpStorm.
 * User: huacaizhi
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/12/18
 * Time: 15:40
 */

namespace HuaCaiZhi\ShortUrlPackage\Validator;

use HuaCaiZhi\ShortUrlPackage\Driver\BaiDuDriver;
use HuaCaiZhi\ShortUrlPackage\Driver\TheDriver;

class BaiDuVerify
{
    /**
     * 短网址有效期，目前支持：
     * "long-term"：长期，默认值
     * "1-year"：1年
     * @var array
     */
    public static $expireArray = [
        'long-term',
        '1-year'
    ];

    /**
     * check params
     *
     * @param array $params
     * @param string $func
     * @return array
     */
    public static function check($params = array(), $func = __FUNCTION__)
    {
        if (!isset($params['token'])
            || empty($params['token'])) {
            return TheDriver::apiReturn([
                'message' => TheDriver::DEFAULT_ERROR
            ], TheDriver::RETURN_ARRAY);
        }
        if (empty($func)
            || !in_array($func, BaiDuDriver::$type)) {
            $func = BaiDuDriver::TO_SHORT;
        }

        if ($func == BaiDuDriver::TO_SHORT) {
            if (!isset($params['long_url'])
                || empty($params['long_url'])) {
                return TheDriver::apiReturn([
                    'message' => TheDriver::DEFAULT_ERROR
                ], TheDriver::RETURN_ARRAY);
            }
            if (isset($params['expire'])
                && in_array($params['expire'], self::$expireArray)) {
                $expire = $params['expire'];
            } else {
                $expire = 'long-term';
            }

            // TODO：设置待注册长网址
            $bodys = array('Url' => $params['long_url'], 'TermOfValidity' => $expire);
            return $bodys;
        }

        if ($func == BaiDuDriver::TO_QUERY) {
            if (!isset($params['short_url'])
                || empty($params['short_url'])) {
                return TheDriver::apiReturn([
                    'message' => TheDriver::DEFAULT_ERROR
                ], TheDriver::RETURN_ARRAY);
            }

            // TODO：设置待注册长网址
            $bodys = array('shortUrl' => $params['short_url']);
            return $bodys;
        }
    }
}