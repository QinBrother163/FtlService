<?php

namespace App\Ftenlog\Ftl\Service;

use Illuminate\Database\Eloquent\Model;

class FtlAmountBacks extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id', //反款编号
        'tradeNo',//交易号
        'upperId',//反款用户（广告商）
        'totalAmount',//订单金额/分
        'payAmount',//买家实付金额
        'offsetAmount',//抵消金额
        'backAmount',//反款金额
        'backStatus',//反款状态 1:待反款 2:已反款
        'backType',//反款途径 1:微信 2:支付宝 3:银行卡
        'backAccount',//反款账号信息
        'backSerialNo',//反款交易号
        'backPropose',//申请提出反款
        'backSuccess',//处理反款成功
    ];
}
