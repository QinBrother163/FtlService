<?php

namespace App\Ftenlog\Ftl\User;

use Illuminate\Database\Eloquent\Model;

class FtlUserMainDatas extends Model
{
    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $fillable = [
        'userId', //用户编号
        'orderFail',//待支付订单编号 [x,x,x,x,x,x,x,x,x,x]
        'orderSuccess',//交易成功订单编号[x,x,x,x,x,x,x,x,x,x]
        'addressData',//收货地址编号[x,x,x,x,x,x,x,x,x,x]
        'userCollection',//用户收藏夹编号[x,x,x,x,x,x,x,x,x,x]
        'userShoppingCart',//用户购物车[x,x,x,x,x,x,x,x,x,x]
        'userCartVolume'//用户卡劵[x,x,x,x,x,x,x,x,x,x]
    ];
}
