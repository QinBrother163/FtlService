<?php

namespace App\Ftenlog;

class ErrorCode
{
    //输入参数缺失
    const PARAM_MISS = 10001;
    //找不到指定appId应用
    const APPID_ERROR = 10002;
    //签名错误
    const SIGN_ERROR = 10003;
    //找不到指定方法
    const NO_FIND_METHOD = 10004;
    //验证授权失败
    const ACCOUNT_NO_LOGIN = 10005;
    //操作过于频繁
    const OPER_EXCESSIVE = 10006;
    //失效二维码(无效广告商)
    const ACCOUNT_INVALID = 10007;
    //获取微信用户异常
    const WXUSER_ERROR = 10008;
}
