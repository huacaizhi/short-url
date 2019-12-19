<?php
/**
 * Created by PhpStorm.
 * User: admin
 * @author  huacaizhi <huacaizhi@gmail.com>
 * Date: 2019/11/13
 * Time: 9:32
 */

namespace HuaCaiZhi\ShortUrlPackage\Driver;

class TheDriver
{
    const EXE_SUCCESS = 0; //执行成功
    const EXE_FAILURE = 1; //执行失败
    /**
     * default error
     * @var array
     */
    const DEFAULT_ERROR = '解析失败`or`参数错误';
    public static $message = 'KO';
    public static $okMessage = 'OK';
    public static $data = [];

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const RETURN_ARRAY = 'ARRAY';
    const RETURN_JSON = 'JSON';
    const RETURN_XML = 'XML';
    const POST_TYPE_FORM = 'form_params';
    const POST_TYPE_JSON = 'json';
    public static $methodArray = [
        self::METHOD_GET,
        self::METHOD_POST,
    ];
    public static $postTypeArray = [
        self::POST_TYPE_FORM,
        self::POST_TYPE_JSON,
    ];
    public static $returnTypeArray = [
        self::RETURN_ARRAY,
        self::RETURN_JSON,
    ];

    /**
     * request method
     * @var array
     */
    public static $requestMethodArray = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'OPTIONS',
        'HEAD',
        'DELETE',
    ];

    /**
     * is url
     * @param string $url
     * @return bool
     */
    public static function isUrl($url = '')
    {
        if (empty($url)) {
            return false;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }

    /**
     * curl request
     *
     * @param array $params
     * @return bool|string
     */
    public static function curlRequest($params = array())
    {
        if (!isset($params['url'])
            || empty($params['url'])
            || !self::isUrl($params['url'])
        ) {
            return false;
        }
        if (!isset($params['method'])) {
            $params['method'] = 'GET';
        } else {
            if (!in_array(strtoupper($params['method']),
                self::$requestMethodArray)) {
                return false;
            }
        }

        if (strtoupper($params['method']) == self::METHOD_POST) {
            if (!isset($params['body'])
                || !is_array($params['body'])
                || empty($params['body'])
            ) {
                return false;
            }
        }

        // 创建连接
        $curl = curl_init($params['url']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $params['method']);
        if (isset($params['header']) && !empty($params['header'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $params['header']);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        if (isset($params['method'])
            && strtoupper($params['method']) == self::METHOD_POST) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params['body']));
        }

        // 发送请求
        $response = curl_exec($curl);

        // 检查是否有错误发生
        if (curl_errno($curl)) {
            return curl_error($curl);
        }

        curl_close($curl);

        // 读取响应
        return $response;
    }

    /**
     * Api Return
     * @param array $data
     * @param string $type
     * @return array|false|string
     */
    public static function apiReturn($data = array(), $type = self::RETURN_JSON)
    {
        if (!isset($data['code'])) {
            $data['code'] = self::EXE_FAILURE;
        }

        if (!isset($data['message'])) {
            $data['message'] = self::$message;
        }

        if (!isset($data['data'])) {
            $data['data'] = self::$data;
        }

        switch ($type) {
            case self::RETURN_ARRAY:
                return $data;

            case self::RETURN_JSON:
            default:
                return json_encode($data);

            case self::RETURN_XML:
                return self::xml_encode($data);
        }
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id 数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public static function xml_encode($data, $root = 'root', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
    {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= self::data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id 数字索引key转换为的属性名
     * @return string
     */
    public static function data_to_xml($data, $item = 'item', $id = 'id')
    {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key = $item;
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= (is_array($val) || is_object($val)) ? self::data_to_xml($val, $item, $id) : $val;
            $xml .= "</{$key}>";
        }
        return $xml;
    }
}