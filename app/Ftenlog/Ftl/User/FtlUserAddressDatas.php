<?php

namespace App\Ftenlog\Ftl\User;

use Illuminate\Database\Eloquent\Model;

class FtlUserAddressDatas extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id', //编号
        'userId',//用户编号
        'name',//联系人名称
        'phone',//联系电话
        'address',//收货地址
        'default',//是否默认收货地址 1:不默认 2:默认
        'verify',//是否已删除 1:正在使用 2:已删除
        'note',//标签
    ];
}
