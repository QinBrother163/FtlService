<?php

namespace App\Ftenlog\Ftl\User;

use Illuminate\Database\Eloquent\Model;

class FtlUserVolumes extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id', //编号
        'userId',//编号
        'volumeId',//优惠卷编号
        'outTime',//失效时间
        'status',//状态 0:未使用 1:已使用 2:已失效
        'tradeNo'//被使用的交易号
    ];
}
