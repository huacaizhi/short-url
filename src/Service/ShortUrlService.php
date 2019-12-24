<?php
/**
 * Created by PhpStorm.
 * User: huacaizhi
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/11/11
 * Time: 19:45
 */

namespace HuaCaiZhi\ShortUrlPackage\Service;

use HuaCaiZhi\ShortUrlPackage\Contract\ShortUrlContract;

class ShortUrlService extends CommService
{
    public $shortUrlService;

    /**
     * app
     *
     * @param $class
     * @return mixed
     */
    public static function app($class)
    {
        if (empty($class)) {
            return new \Exception('class not found');
        }

        if (is_object($class)) {
            $class = get_class($class);
        }
        if (!class_exists($class)) return new \Exception('class not found');
        return new $class;
    }

    /**
     * service
     *
     * @param $class
     * @return $this
     */
    public function service($class)
    {
        if ($shortUrlService = self::app($class)) {
            $this->shortUrlService = $shortUrlService;
        }
        return $this;
    }

    ///**
    // * init
    // *
    // * @param array $init
    // * @return mixed
    // */
    //public function init($init = array())
    //{
    //    if (!is_array($init)) new \Exception('params needs array');
    //    $this->shortUrlService->init($init);

    //    return $this;
    //}

    /**
     * boot
     *
     * @param null $data
     * @param string $type
     * @return mixed
     */
    public function boot($data = null, $type = ShortUrlContract::TO_SHORT)
    {
        if (!in_array($type, array(ShortUrlContract::TO_SHORT))) {
            return new \Exception('服务类名出错!');
        }

        return $this->shortUrlService->setData($data)->{$type}();
    }
}