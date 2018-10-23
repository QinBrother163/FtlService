<?php

namespace App\Ftenlog\Ftl\User;

use App\Ftenlog\ErrorCode;
use App\Ftenlog\Ftl\FtlController;
use DB;
use Illuminate\Http\Request;

class FtlUserController extends FtlController
{
    protected function doMethod($request)
    {
        $method = $request['method'];
        if ($method == '/ftenlog/user/getAddress') {
            return $this->getAddress($request);
        }
        if ($method == '/ftenlog/user/updateAddress') {
            return $this->updateAddress($request);
        }
        return [
            'code' => ErrorCode::NO_FIND_METHOD,
            'msg' => '找不到指定method的方法',
        ];
    }

    /*
     * 获取用户收货地址
     */
    protected function getAddress($request){
        $ret = $this->checkAccountLogin();
        if ($ret) {
            return $ret;
        }
        $user = $this->user;
        $log = DB::table('ftl_user_main_datas')
            ->where(['userId'=>$user->userId])
            ->first(['addressData']);
        $arr = $log->addressData;

        $info = DB::table('ftl_user_address_datas')
            ->whereIn('id',$arr)
            ->where(['verify'=>1])
            ->get();

        if(!$info){
            $info = [];
        }
        return $this->biz($info);
    }

    /*
     * 更新用户收货地址
     */
    protected function updateAddress($request){
        $ret = $this->checkAccountLogin();
        if ($ret) {
            return $ret;
        }
        $user = $this->user;
        $bizContent = $request['bizContent'];
        $bizIn = json_decode($bizContent);
        {
            //! 输入参数检查
            $subCode = 0;
            $subMsg = '';
            if($subCode == 0 && empty($bizIn->name)){
                $subCode = 1;
                $subMsg = 'not set UserIn param name';
            }
            if($subCode == 0 && empty($bizIn->phone)){
                $subCode = 1;
                $subMsg = 'not set UserIn param phone';
            }
            if($subCode == 0 && empty($bizIn->address)){
                $subCode = 1;
                $subMsg = 'not set UserIn param address';
            }
            if($subCode == 0 && empty($bizIn->default)){
                $subCode = 1;
                $subMsg = 'not set UserIn param default';
            }
            if($subCode == 0 && empty($bizIn->verify)){
                $subCode = 1;
                $subMsg = 'not set UserIn param verify';
            }
            if($subCode == 0 && empty($bizIn->note)){
                $subCode = 1;
                $subMsg = 'not set UserIn param note';
            }

            if ($subCode != 0) {
                return $this->error(ErrorCode::PARAM_MISS,
                    '添加地址输入参数缺失', $subCode, $subMsg);
            }
        }
        if(isset($bizIn->id)){
            //更新
            $info = (new FtlUserAddressDatas)->where(['id'=>$bizIn->id])->first();
            if($info){
                unset($bizIn['id']);
                $info->update($bizIn);
                return $this->biz("");
            }
        }
        //新建
        $fill = new FtlUserAddressDatas();
        $fill->fill([
            'userId'=>$user->userId,
            'name'=>$bizIn->name,
            'phone'=>$bizIn->phone,
            'address'=>$bizIn->address,
            'default'=>$bizIn->default,
            'verify'=>$bizIn->verify,
            'note'=>$bizIn->note
        ]);
        $fill->save();

        $log = DB::table('ftl_user_main_datas')
            ->where(['userId'=>$user->userId])
            ->first(['addressData']);
        $arr = json_decode($log->addressData,true);
        array_push($arr,$fill->id);
        $log->update(['addressData'=>json_encode($arr)]);
        return $this->biz("");
    }


    /*
     * 获取购物车
     */
    protected function getShoppingCart($request){

    }

    /*
     * 更新购物车
     */
    protected function updageShoppingCart($request){

    }
}
