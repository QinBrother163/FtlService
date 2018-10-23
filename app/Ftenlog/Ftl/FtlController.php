<?php

namespace App\Ftenlog\Ftl;

use App\Ftenlog\ErrorCode;
use App\Ftenlog\Fast;
use App\Ftenlog\RequestBody;
use App\Ftenlog\ResponseBody;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FtlController extends Controller
{
    protected $appId; //appID
    protected $appSecret; //appSecret
    /**
     * @var User
     */
    protected $user;
    /**
     * @var string
     */
    protected $token;
    protected $oldToken;
    /**
     * @var UserService
     */
    protected $userSrv;

    public function __construct()
    {
        $this->appId = env('TDSRV_APP_ID', 1000);
        $this->appSecret = env('TDSRV_APP_SECRET','RcOFhtAYzwCGo91PGHdV');

        $this->userSrv = app(UserService::class);
    }

    protected function serialNo($numLen = 20)
    {
        return Fast::serialNo($numLen);
    }

    protected function biz($bizOut)
    {
        $response = [
            'code' => 0,
            'msg' => '调用成功',
            'subCode' => '',
            'subMsg' => '',
            'timestamp' => date('Y-m-d H:i:s'),
            'sign' => '',
            'bizContent' => json_encode($bizOut),
        ];
        if ($this->token != $this->oldToken) {
            $response['token'] = $this->token;
            \Log::info('-e new token ', $response);
        }
        $response['sign'] = ResponseBody::signCode($response, $this->appSecret);
        return $response;
    }

    protected function error($code, $msg, $subCode, $subMsg)
    {
        $response = [
            'code' => $code,
            'msg' => $msg,
            'subCode' => $subCode,
            'subMsg' => $subMsg,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        if ($this->token != $this->oldToken) {
            $response['token'] = $this->token;
            \Log::info('-e new token ', $response);
        }
        $response['sign'] = ResponseBody::signCode($response, $this->appSecret);
        return $response;
    }

    protected function signVerify($sign,$parm,$ts){
        $appId = env("TDSRV_APP_ID",1000);
        $appSecret = env("TDSRV_APP_SECRET");
        $wxAPPKey = env("WX_APP_ID");
        //APPKEY + WXAPPKEY + params1 + ts + APPSECRET
        $msg = "$appId$wxAPPKey$parm$ts$appSecret";     //解密==============================
        $md5 = md5($msg);
        if (strcasecmp($md5, $sign) != 0) {
            return [
                'code' => ErrorCode::SIGN_ERROR,
                'msg' => '验证失败',
                'subCode' => 4,
                'subMsg' => '验证失败',
            ];
        }
        return [
            'code' => 0,
            'msg' => '验证成功',
            'subCode' => 4,
            'subMsg' => '验证成功',
        ];
    }

    public function ftenlog(Request $request)
    {
        {   //! 输入参数检查
            $subCode = 0;
            $subMsg = '';

            if ($subCode == 0 && !isset($request['appId'])) {
                $subCode = 1;
                $subMsg = 'not set input param appId';
            }
            if ($subCode == 0 && !isset($request['method'])) {
                $subCode = 2;
                $subMsg = 'not set input param method';
            }
            if ($subCode == 0 && !isset($request['timestamp'])) {
                $subCode = 3;
                $subMsg = 'not set input param timestamp';
            }
            if ($subCode == 0 && !isset($request['signCode'])) {
                $subCode = 4;
                $subMsg = 'not set input param signCode';
            }
            if ($subCode == 0 && !isset($request['bizContent'])) {
                $subCode = 5;
                $subMsg = 'not set input param bizContent';
            }

            if ($subCode != 0) {
                return $this->error(ErrorCode::PARAM_MISS,
                    '输入参数缺失', $subCode, $subMsg);
            }
        }

        $appId = $request['appId'];
        if ($appId != $this->appId) {
            return $this->error(ErrorCode::APPID_ERROR,
                '找不到指定appId应用', '', '');
        }
        $md5 = RequestBody::signCode($request, $this->appSecret);
        $sign = $request['signCode'];
        if (strcasecmp($sign, $md5) != 0) {
            \Log::error('-e signCode', [
                'signC' => $sign,
                'signS' => $md5,
                'appSecret'=>$this->appSecret
            ]);
            return $this->error(ErrorCode::SIGN_ERROR,
                '输入参数签名错误', '', '');
        }
        //获取用户信息
        if(!empty($request['token'])){
            //根据token获取用户信息
            $res=$this->userSrv->getUser($request['token']);
            if ($res['subCode'] == 0) {
                $this->oldToken = $request['token'];
                $this->token = $res['token'];
                $this->user = $res['user'];
                //\Log::info($this->user);
            } else {
                \Log::info('-e subCode:' . $res['subCode']);
            }
        }else{
            $this->token = '';
            $this->user = null;
        }
        return $this->doMethod($request);
    }

    protected function doMethod($request)
    {
        $method = $request['method'];
        if ($method == '/ftenlog/wxUser') {
            return $this->wxUserLogin($request);
        }
        return $this->error(ErrorCode::NO_FIND_METHOD,
            '找不到指定method的方法', '', '');
    }

    protected function checkAccountLogin()
    {
        if (empty($this->user)) {
            return [
                'code' => ErrorCode::ACCOUNT_NO_LOGIN,
                'msg' => '验证授权失败',
                'subCode' => 4,
                'subMsg' => '获取用户失败',
            ];
        }
    }

    protected  function  wxUserLogin($request){
        $bizContent = $request['bizContent'];
        $bizIn = json_decode($bizContent);
        {
            //! 输入参数检查
            $subCode = 0;
            $subMsg = '';

            if($subCode == 0 && empty($bizIn->wxCode)){
                $subCode = 1;
                $subMsg = 'not set UserIn param wxCode';
            }
            if($subCode == 0 && empty($bizIn->userName)){
                $subCode = 1;
                $subMsg = 'not set UserIn param userName';
            }
            if($subCode == 0 && empty($bizIn->userSex)){
                $subCode = 1;
                $subMsg = 'not set UserIn param userName';
            }
            if($subCode == 0 && empty($bizIn->userCity)){
                $subCode = 1;
                $subMsg = 'not set UserIn param userName';
            }
            if($subCode == 0 && empty($bizIn->userProvince)){
                $subCode = 1;
                $subMsg = 'not set UserIn param userProvince';
            }
            if($subCode == 0 && empty($bizIn->userCountry)){
                $subCode = 1;
                $subMsg = 'not set UserIn param userCountry';
            }
            if($subCode == 0 && empty($bizIn->userImg)){
                $subCode = 1;
                $subMsg = 'not set UserIn param userImg';
            }

            if ($subCode != 0) {
                return $this->error(ErrorCode::PARAM_MISS,
                    '注册账号输入参数缺失', $subCode, $subMsg);
            }
        }
        $biz = $this->userSrv->GetWXOpenId($request);
        if($biz['code'] != 0){
            return $biz;
        }

        return $this->biz([
            'token' => $biz['token'],
            'userInfo' => $biz['biz']['userInfo'],
            'userDatas' => $biz['biz']['userDatas'],
            ]);
    }
}
