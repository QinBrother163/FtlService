<?php

namespace App\Ftenlog;

class Fast
{
    static $timeout = 10;

    /**
     * @param $url string
     * @return array
     */
    public static function getUrlArgs($url)
    {
        $query = parse_url($url)['query'];
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    /**
     * list($code, $result) = curlHttpPost('xxx','xxx');
     * @param string $url
     * @param array|object $data
     * @return array resp
     * @return string resp.code
     * @return string resp.result
     */
    public static function curlPostJson($url, $data)
    {
        $json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($json))
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($code, $result);
    }

    public static function curlPost($url, $data)
    {
        $args = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
                'Content-Length: ' . strlen($args))
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \Log::debug("-e code:$code result:$result");
        curl_close($ch);
        return array($code, $result);
    }

    public static function curlPost1($url, $data, $disSsl = false)
    {
        if($data != null){
            $data = http_build_query($data);
        }
        return self::post($url, $data, 'application/x-www-form-urlencoded', $disSsl);
    }

    /**
     * @param string $url
     * @param array|object $data
     * @return array resp
     * @return int resp.code
     * @return string resp.result
     */
    public static function curlGet($url, $data)
    {
        $args = http_build_query($data);
        $url .= "?$args";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($code, $result);
    }

    public static function serialNo($numLen = 20)
    {
        return date('YmdHis') . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $numLen - 14);
    }

    //返回当前的毫秒时间戳
    public static function ms()
    {
        list($mms, $sec) = explode(' ', microtime());
        $ms = (float)sprintf('%.0f', (floatval($mms) + floatval($sec)) * 1000);
        return $ms;
    }

    public static function qrUrl($url)
    {
        $args = http_build_query([
            'url' => $url,
        ]);
        return url('/api/qrCode') . "?$args";
    }

    public static function post($url, $data, $type, $disSsl = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: $type; charset=utf-8",
            'Content-Length: ' . strlen($data)
        ));
        //curl_setopt($ch, CURLOPT_HEADER, true);
        //curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($disSsl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \Log::debug("-e code:$code result:$result");

        //$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //$header = substr($result, 0, $headerSize);
        //$result = substr($result, $headerSize);

        curl_close($ch);
        return array($code, $result);
    }
}
