<?php

namespace App\Ftenlog;


use App\Ftenlog\BaseBody;

class ResponseBody extends BaseBody
{
    public $code;      // 网关返回码
    public $msg;       // 网关返回码描述
    public $subCode;   // 业务返回码,详见文档:xxxx
    public $subMsg;    // 业务返回码描述,详见文档:交易已被支付
    public $timestamp; // 应答的时间
    public $sign;      // 签名,详见文档 32位小写
    public $bizContent;// 业务参数集合，最大长度不限，除公共参数外所有返回参数都必须放在这个参数中传递，具体参照各产品快速接入文档
    public $token;     // 更新后的授权码,为空则不更新

    public static function signCode($body, $secret)
    {
        $str = ''
            . self::getParam($body, 'bizContent')
            . self::getParam($body, 'code')
            . self::getParam($body, 'msg')
            . self::getParam($body, 'subCode')
            . self::getParam($body, 'subMsg')
            . self::getParam($body, 'timestamp')
            . self::getParam($body, 'token')
            . $secret;
        return md5($str);
    }
}