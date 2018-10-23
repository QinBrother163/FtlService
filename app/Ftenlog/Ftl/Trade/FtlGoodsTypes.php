<?php

namespace App\Ftenlog\Ftl\Trade;

use Illuminate\Database\Eloquent\Model;

class FtlGoodsTypes extends Model
{
    protected $primaryKey = 'goodsTypeId';
    public $incrementing = true;
    protected $fillable = [
        'goodsTypeId', //产品类型编号
        'goodsTypeName',//产品类型名字
        'goodsTypeDesc',//产品类型描述
        'goodsTypeImg',//产品类型图片
        'verify',//已审核
        'note',//备注
    ];
}
