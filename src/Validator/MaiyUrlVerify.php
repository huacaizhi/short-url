<?php
/**
 * Created by PhpStorm.
 * User: huacaizhi
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/12/24
 * Time: 14:19
 */

namespace HuaCaiZhi\ShortUrlPackage\Validator;

use HuaCaiZhi\ShortUrlPackage\Driver\MaiyUrlDriver;
use HuaCaiZhi\ShortUrlPackage\Driver\TheDriver;

class MaiyUrlVerify
{
    /**
     * check params
     *
     * @param array $params
     * @param string $func
     * @return array|string
     */
    public static function check($params = array(), $func = __FUNCTION__)
    {
        if (empty($func)
            || !in_array($func, MaiyUrlDriver::$type)) {
            $func = MaiyUrlDriver::TO_SHORT;
        }

        if ($func == MaiyUrlDriver::TO_SHORT) {
            if (!isset($params['long_url'])
                || empty($params['long_url'])) {
                return TheDriver::apiReturn([
                    'message' => TheDriver::DEFAULT_ERROR
                ], TheDriver::RETURN_ARRAY);
            }

            $type = MaiyUrlDriver::WEIBO;
            if (isset($params['type']) && !empty($params['type'])) {
                if (in_array(strtolower($params['type']), array_keys(MaiyUrlDriver::$urlProvider))) {
                    $type = strtolower($params['type']);
                }
            }

            // TODO：设置待注册长网址
            $bodys = MaiyUrlDriver::$urlProvider[$type]['url'] . '?' . http_build_query(array(
                    'url_long' => utf8_encode($params['long_url'])
                ));
            return $bodys;
        }

        if ($func == MaiyUrlDriver::TO_QUERY) {
            return TheDriver::apiReturn([
                'message' => TheDriver::DEFAULT_ERROR
            ], TheDriver::RETURN_ARRAY);
        }
    }
}