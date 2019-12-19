<?php
/**
 * Created by PhpStorm.
 * User: huacaizhi
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/11/8
 * Time: 16:11
 */

namespace HuaCaiZhi\ShortUrlPackage\Contract;


abstract class ShortUrlContract
{
    const TO_SHORT = 'shortLink';
    const TO_QUERY = 'query';
    public $data;

    public static $type = array(
        self::TO_SHORT,
        self::TO_QUERY
    );

    /**
     * set data
     *
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    //abstract public function init($init = array());

    abstract public function shortLink();

    abstract public function query();
}