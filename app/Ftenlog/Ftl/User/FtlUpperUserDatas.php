<?php

namespace App\Ftenlog\Ftl\User;

use Illuminate\Database\Eloquent\Model;

class FtlUpperUserDatas extends Model
{
    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $fillable = [
        'userId', //用户编号
        'userChild',//用户所包含的下级用户 [x,x,x,x,x,x,x,x,x,x]
        'backData',//反款记录编号[x,x,x,x,x,x,x,x,x,x]
        'wxBack',
        'zfbBack',
        'bankCard',
    ];
}
