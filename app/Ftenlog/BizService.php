<?php

namespace App\Ftenlog;
use Illuminate\Support\Facades\DB;

class BizService
{
    /*
     * 发送验证码
     */
    public function sendSmsCode($tel){

        $code = rand(1000,9999);
        $apiUrl = 'https://sms.yunpian.com/v2/sms/single_send.json';
        $aipKey = "150fa3190b72d0b9f5ce9d214438e875"; //修改为您的apikey(https://www.yunpian.com)登录官网后获取

        $text = "【双动运动】验证码 : ".$code.",有效期为10分钟。";
        $bizIn = [
            'text' => $text,
            'apikey' => $aipKey,
            'mobile' => $tel,
        ];
        list($status, $result) =  Fast::curlPost1($apiUrl, $bizIn,true);
        if ($status != 200) {
            return false;
        }
        return true;
    }


}
