<?php

namespace App\Ftenlog\Ftl\Trade;

use Illuminate\Database\Eloquent\Model;

class FtlShopInfos extends Model
{
    protected $primaryKey = 'productId';
    public $incrementing = true;
    protected $fillable = [
        'productId', //商品编号
        'goodsId',//产品编号
        'goodsName',//商品名称
        'goodsDesc',//商品描述
        'goodsImg',//商品图片
        'complex',//是复合产品
        'comment',//复合内容  [{"goodsId":"TD020","quantity":6},{"goodsId":"TD004","quantity":1},{"goodsId":"TD012","quantity":1}]//普通商品
                    //         {"articles":[],"strategys":[],goods":[{"productId":"TD020","quantity":6},{"goodsId":"TD004","quantity":1}]}//文章 攻略 商品 套餐商品'
        'category',//产品类型
        'price',//定价/分
        'storeId',//商家编号
        'storeName',//商家名称
        'verify',//已审核
        'note',//备注
        'discount',//是否特惠 {"flag":true,"discounts":1000} //flag 是否开启 discounts 折扣力度
        'expand',//业务拓展
        'saleNum',//真实销量
        'barImg',//广告轮播图
        'briefImg',//介绍图
        'numDiscount',//减免规则
        'talk',//评论编号
        'collection',//收藏数
    ];
}
