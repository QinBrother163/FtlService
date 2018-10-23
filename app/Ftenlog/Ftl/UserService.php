<?php

namespace App\Ftenlog\Ftl;

use App\Ftenlog\ErrorCode;
use App\Ftenlog\Fast;
use App\Ftenlog\Ftl\User\FtlUpperUserDatas;
use App\Ftenlog\Ftl\User\FtlUserInfos;
use App\Ftenlog\Ftl\User\FtlUserMainDatas;
use App\User;
use DB;
use Exception;
use JWTAuth;
use Log;
use function Sodium\add;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserService
{

    /*
     * 用户登录(扫码进来的)
     */
    private function userQrCodeLogin($bizIn){
        $sign = $bizIn['sign'];
        $upperId = $bizIn['upperId'];
        $ts = $bizIn['ts'];
        $appId = env("TDSRV_APP_ID",1000);
        $appSecret = env("TDSRV_APP_SECRET");
        $wxAPPKey = env("WX_APP_ID");
        //APPKEY + WXAPPKEY + params1 + ts + APPSECRET
        $msg = "$appId$wxAPPKey$upperId$ts$appSecret";     //解密==============================
        $md5 = md5($msg);
        if (strcasecmp($md5, $sign) != 0) {
            return [
                'code' => ErrorCode::SIGN_ERROR,
                'msg' => '验证失败',
                'subCode' => 4,
                'subMsg' => '验证失败',
            ];
        }

        $openId = $bizIn['openId'];
        $unionId = $bizIn['unionId'];
        $userName = $bizIn['userName'];
        $userSex = $bizIn['userSex'];
        $userCity = $bizIn['userCity'];
        $userProvince = $bizIn['userProvince'];
        $userCountry = $bizIn['userCountry'];
        $userImg = $bizIn['userImg'];
//        $upperIdUser = DB::table('users')->where(['userId'=>$upperId,'verify'=>1])->first();
        $upperIdUser = (new User)->where(['userId'=>$upperId,'verify'=>1])->first();
        if(!$upperIdUser){
            return [
                'code' => ErrorCode::ACCOUNT_INVALID,
                'msg' => '无效二维码',
                'subCode' => 4,
                'subMsg' => '无效二维码',
            ];
        }

        $loginUser = DB::table('users')->where(['openId'=>$openId,'appId' => $wxAPPKey])->first();
        if($loginUser){
            //登录用户
            $token = JWTAuth::fromUser($loginUser);
            $bizIn = [
                'userId'=>$loginUser->userId,
                'userName'=>$userName,
                'userSex'=>$userSex,
                'userCity'=>$userCity,
                'userProvince'=>$userProvince,
                'userCountry'=>$userCountry,
                'userImg'=>$userImg
            ];
        }else{
            if($upperIdUser->appId == "1" && $upperIdUser->openId == "1"){
                //还没绑定注册广告商的账号
                $fillIn = [
                    'appId'=>$wxAPPKey,
                    'openId'=>$openId,
                    'unionId'=>$unionId,
                ];
                $upperIdUser->update($fillIn);
                $token = JWTAuth::fromUser($upperIdUser);
                $bizIn = [
                    'userId'=>$upperIdUser->userId,
                    'userName'=>$userName,
                    'userSex'=>$userSex,
                    'userCity'=>$userCity,
                    'userProvince'=>$userProvince,
                    'userCountry'=>$userCountry,
                    'userImg'=>$userImg,
                    'userType'=>2,
                    'userUpper'=>-1,
                    'userCoin'=>0,
                ];
            }else{
                //注册用户
                $userId = $this->getUserId();
                $fillUser = new User();
                $fillUser->fill([
                    'userId'=>$userId,
                    'appId'=>$wxAPPKey,
                    'openId'=>$openId,
                    'unionId'=>$unionId,
                    'verify'=>1
                ]);
                $fillUser->save();
                $token = JWTAuth::fromUser($fillUser);
                $this->updateUpperChild($upperId,$userId);
                $bizIn = [
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'userSex'=>$userSex,
                    'userCity'=>$userCity,
                    'userProvince'=>$userProvince,
                    'userCountry'=>$userCountry,
                    'userImg'=>$userImg,
                    'userType'=>1,
                    'userUpper'=>$upperId,
                    'userCoin'=>0,
                ];
            }
        }
        return [
            'code' => 0,
            'msg' => '登录成功',
            'subCode' => 0,
            'subMsg' => '登录成功',
            'token'=>$token,
            'biz' => $this->updateUserInfo($bizIn)
        ];
    }

    /*
     * 用户登录(搜索进来的)
     */
    private function userLogin($bizIn){

        $wxAPPKey = env("WX_APP_ID");
        $openId = $bizIn['openId'];
        $unionId = $bizIn['unionId'];
        $userName = $bizIn['userName'];
        $userSex = $bizIn['userSex'] ;
        $userCity = $bizIn['userCity'];
        $userProvince = $bizIn['userProvince'];
        $userCountry = $bizIn['userCountry'];
        $userImg = $bizIn['userImg'];

        $loginUser = DB::table('users')->where(['openId'=>$openId,'appId' => $wxAPPKey])->first();
        if(!$loginUser){
            //注册用户
            $userId = $this->getUserId();
            $fillUser = new User();
            $fillUser->fill([
                'userId'=>$userId,
                'appId'=>$wxAPPKey,
                'openId'=>$openId,
                'unionId'=>$unionId,
                'verify'=>1
            ]);
            $fillUser->save();
            $token = JWTAuth::fromUser($fillUser);
            $bizIn = [
                'userId'=>$userId,
                'userName'=>$userName,
                'userSex'=>$userSex,
                'userCity'=>$userCity,
                'userProvince'=>$userProvince,
                'userCountry'=>$userCountry,
                'userImg'=>$userImg,
                'userType'=>1,
                'userUpper'=>-1,
                'userCoin'=>0,
            ];
        }else{
            //登录用户
            $token = JWTAuth::fromUser($loginUser);
            $bizIn = [
                'userId'=>$loginUser->userId,
                'userName'=>$userName,
                'userSex'=>$userSex,
                'userCity'=>$userCity,
                'userProvince'=>$userProvince,
                'userCountry'=>$userCountry,
                'userImg'=>$userImg
            ];
        }

        return [
            'code' => 0,
            'msg' => '登录成功',
            'subCode' => 0,
            'subMsg' => '登录成功',
            'token'=>$token,
            'biz' => $this->updateUserInfo($bizIn)
        ];
    }

    /*
     * 微信登录成功返回的code 请求微信接口获取 openid session_key
     * 根据 openid 登录用户
     */
    public function GetWXOpenId($request){
        $WXAPPID = env('WX_APP_ID', "");
        $WXAPPSECRET = env('WX_APP_SECRET', "");
        if($WXAPPID == "" || $WXAPPSECRET == ""){
            return [
                'code' => ErrorCode::PARAM_MISS,
                'msg' => '登录失败',
                'subCode' => 4,
                'subMsg' => '登录失败',
            ];
        }
        $bizContent = $request['bizContent'];
        $bizIn = json_decode($bizContent);
        $wxCode = $bizIn->wxCode;
        $apiUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$WXAPPID.'&secret='.$WXAPPSECRET.'&js_code='.$wxCode.'&grant_type=authorization_code';
        list($status, $result) = Fast::curlPost1($apiUrl, null,true);
        $resArr = json_decode($result);
        if ($status != 200) {
            return [
                'code' => ErrorCode::WXUSER_ERROR,
                'msg' => '登录失败',
                'subCode' => 4,
                'subMsg' => '登录失败',
            ];
        }
        //{"session_key":"VmB6ZfmSM6zUeDdGF2jeoQ==","openid":"oDMJ65ZmjmljZWDf6jXQNbCJiW8M"}
        if(isset($resArr->errcode)){
            return [
                'code' => ErrorCode::WXUSER_ERROR,
                'msg' => '登录失败',
                'subCode' => 4,
                'subMsg' => '登录失败',
            ];
        }
        if(isset($bizIn->ts) && isset($bizIn->sign) && isset($bizIn->upperId)){
            //扫码进来的
            // ts sign upperId
            // openId unionId
            //用户信息
            $biz = [
                'ts'=>$bizIn->ts,
                'sign' => $bizIn->sign,
                'upperId'=>$bizIn->upperId,
                'openId'=>$resArr->openid,
                'unionId'=>isset($resArr->unionId) ? $resArr->unionId : "" ,
                'userName'=>$bizIn->userName,
                'userSex'=>$bizIn->userSex,
                'userCity'=>$bizIn->userCity ,
                'userProvince'=>$bizIn->userProvince,
                'userCountry'=>$bizIn->userCountry ,
                'userImg'=>$bizIn->userImg,
            ];
            return $this->userQrCodeLogin($biz);
        }
        //普通登录的
        // openId unionId
        //用户信息
        $biz = [
            'openId'=>$resArr->openid,
            'unionId'=>isset($resArr->unionId) ? $resArr->unionId : "" ,
            'userName'=>$bizIn->userName,
            'userSex'=>$bizIn->userSex,
            'userCity'=>$bizIn->userCity ,
            'userProvince'=>$bizIn->userProvince,
            'userCountry'=>$bizIn->userCountry ,
            'userImg'=>$bizIn->userImg,
        ];
        return $this->userLogin($biz);
    }

    private function updateUserInfo($bizIn){
        $userInfo = (new FtlUserInfos)->where(['userId'=>$bizIn['userId']])->first();
//        $userInfo = DB::table('ftl_user_infos')->where(['userId'=>$bizIn['userId']])->first();
        if(!$userInfo){
            $fillInfo = new FtlUserInfos();
            $fillInfo->fill([
                'userId'=>$bizIn['userId'],
                'userType'=>1,
                'userUpper'=>-1,
                'userName'=>'',
                'userSex'=>1,
                'userCity'=>"",
                'userProvince'=>"",
                'userCountry'=>"",
                'userImg'=>"",
                'userCoin'=>0,
            ]);
            $fillInfo->save();
            $fillInfo->update($bizIn);
            $biz = [
                'userInfo'=>$fillInfo,
                'userDatas'=>$this->getUserMain($fillInfo)
            ];
            return $biz;
        }
        unset($bizIn['userId']);
        $userInfo->update($bizIn);
        $biz = [
            'userInfo'=>$userInfo,
            'userDatas'=>$this->getUserMain($userInfo)
        ];
        return $biz;
    }

    private function getUserMain($log){
        if($log->userType == 1){
            //普通用户
            $userInfo = (new FtlUserMainDatas)->where(['userId'=>$log->userId])->first();
            if(!$userInfo){
                $fillInfo = new FtlUserMainDatas();
                $fillInfo->fill([
                    'userId'=>$log->userId,
                    'orderFail'=>'[]',
                    'orderSuccess'=>'[]',
                    'addressData'=>'[]',
                    'userCollection'=>'[]',
                    'userShoppingCart'=>'[]',
                    'userCartVolume'=>'[]'
                ]);
                $fillInfo->save();
                return $fillInfo;
            }
            return $userInfo;
        }
        //广告商
        $userInfo = (new FtlUpperUserDatas)->where(['userId'=>$log->userId])->first();
        if(!$userInfo){
            $fillInfo = new FtlUpperUserDatas();
            $fillInfo->fill([
                'userId'=>$log->userId,
                'userChild'=>'[]',
                'backData'=>'[]',
                'wxBack'=>'{}',
                'zfbBack'=>'{}',
                'bankCard'=>'[]'
            ]);
            $fillInfo->save();
            return $fillInfo;
        }
        return $userInfo;
    }

    /*
     * 填写广告商下线用户
     */
    private function updateUpperChild($upperId,$childId){
        //$upperLog = DB::table("ftl_upper_user_datas")->where(['userId'=>$upperId])->first();
        $upperLog = (new FtlUpperUserDatas)->where(['userId'=>$upperId])->first();
        if(!$upperLog){
            $fillData = new FtlUpperUserDatas();
            $fillData->fill([
                'userId'=>$upperId,
                'userChild'=>'['.$childId.']',
                'backData'=>'[]',
                'wxBack'=>"",
                'zfbBack'=>"",
                'bankCard'=>'[]',
            ]);
            $fillData->save();
        }else{
            $record = json_decode($upperLog->userChild);
            array_push($record,$childId);
            $upperLog->update(['userChild'=>json_encode($record)]);
        }
    }

    public function getUser($token)
    {
        if (empty($token)) {
            return [
                'subCode' => 1,
                'subMsg' => '授权码appAuthToken或token不能为空',
            ];
        }

        $user = null;
        $subCode = null;
        $subMsg = null;

        try {
            $user = JWTAuth::toUser($token);
            $subCode = 0;
            $subMsg = '成功';

        } catch (TokenExpiredException $e) {
            try {
                $token = JWTAuth::refresh($token);
                $user = JWTAuth::toUser($token);
                $subCode = 0;
                $subMsg = '成功';
            } catch (Exception $e) {
                $user = null;
                $token = null;
                $subCode = 1;
                $subMsg = '刷新失败 code:' . $e->getCode() . ' msg:' . $e->getMessage();
            }

        } catch (TokenInvalidException $e) {
            $token = null;
            $user = null;
            $subCode = 2;
            $subMsg = '授权码失效 code:' . $e->getCode() . ' msg:' . $e->getMessage();

        } catch (JWTException $e) {
            $token = null;
            $user = null;
            $subCode = 3;
            $subMsg = '其他错误 code:' . $e->getCode() . ' msg:' . $e->getMessage();
        }
        $ret = [
            'user' => $user,
            'token' => $token,
            'subCode' => $subCode,
            'subMsg' => $subMsg,
        ];
        if ($subCode != 0) {
            Log::info('-e getUser ', $ret);
        }
        return $ret;
    }

    private function getUserId(){
        $times = 1;
        while ($times < 100) {
            $userId = mt_rand(0, 9999999);
            if ($userId < 1000000) {
                $userId = $userId + 1000000;
            }

            $user = DB::table('users')->where(['userId'=>$userId])->first();

            if(!$user) {
                return $userId;
            }

            $times = $times + 1;
        }

        \Log::debug('getUserId fail');
        return -1;
    }
}
