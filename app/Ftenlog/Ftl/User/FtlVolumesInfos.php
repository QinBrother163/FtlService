<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FtlVolumesInfos extends Model
{
    protected $primaryKey = 'productId';
    public $incrementing = true;
    protected $fillable = [
        'volumeId',//优惠卷编号
        'applyRange',//适用范围 商品编号[id,id,id]
        'numDiscount',//减免规则 {"term":"达到多少钱","discount":"减免多少钱"}
        'validTime',//有效期 多少小时
        'stockNum',//库存
        'receiveNum',//领取次数
        'useNum',//使用次数
        'verify',//是否正式开放
        'note',//备注
    ];
}
