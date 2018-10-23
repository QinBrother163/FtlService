<?php

namespace App\Ftenlog\Ftl\User;

use Illuminate\Database\Eloquent\Model;

class FtlUserCoinDatas extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id', //编号
        'userId',//用户编号
        'addCoin',//增长金币数量
        'coinNum',//变化后的金币数量
        'coinChannel',//金币(获取/消耗)途径(消费交易号/获取活动号)
    ];
}
