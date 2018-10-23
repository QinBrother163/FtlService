<?php

namespace App\Ftenlog\Ftl\User;

use Illuminate\Database\Eloquent\Model;

class FtlUserInfos extends Model
{
    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $fillable = [
        'userId', //用户编号
        'userType',//用户类型 //1:普通用户 2:广告商
        'userUpper',//-1为无广告商用户 用户编号则为有广告商用户
        'userName',//用户名字
        'userSex',//用户性别 1->男 2->女
        'userCity',//用户城市
        'userProvince',//用户省份
        'userCountry',//用户国家
        'userImg',//用户头像
        'userCoin',//用户抵消金额币
    ];
}
