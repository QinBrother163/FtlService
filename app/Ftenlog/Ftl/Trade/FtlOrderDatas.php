<?php

namespace App\Ftenlog\Ftl\Trade;

use Illuminate\Database\Eloquent\Model;

class FtlOrderDatas extends Model
{
    protected $primaryKey = 'tradeNo';
    public $incrementing = true;
    protected $fillable = [
        'tradeNo',
        'orderNo',
        'userId',
        'storeId',
        'storeName',
        'totalAmount',
        'subject',
        'body',
        'goodsDetail',
        'extendParams',
        'extUserInfo',
        'payTimeout',
        'payAmount',
        'receiptAmount',
        'serialNo',
        'tradeStatus',
        'payTime',
        'address',
        'offsetMoney',
    ];
}
