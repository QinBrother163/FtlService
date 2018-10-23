<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlShopInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_shop_infos', function (Blueprint $table) {
            $table->increments('productId')->comment('商品编号');
            $table->string('goodsId')->comment('产品编号');
            $table->string('goodsName')->comment('商品名称');
            $table->string('goodsDesc')->comment('商品描述')->nullable();
            $table->string('goodsImg')->comment('产品广告图');
            $table->boolean('complex')->comment('是套餐产品');
            $table->string('comment')->comment('复合内容 
                                                [{"goodsId":"TD020","quantity":6},{"goodsId":"TD004","quantity":1},{"goodsId":"TD012","quantity":1}]  //普通商品
                                                {"articles":[],"strategys":[],goods":[{"productId":"TD020","quantity":6},{"goodsId":"TD004","quantity":1}]}//文章 攻略 商品 套餐商品');
            $table->unsignedTinyInteger('category')->comment('产品类型');
            $table->integer('price')->comment('定价/分');
            $table->integer('storeId')->comment('商家编号');
            $table->string('storeName')->comment('商家名称');
            $table->boolean('verify')->comment('已审核');
            $table->string('note')->comment('备注')->nullable();
            $table->string('discount')->comment('是否特惠'); //{"flag":true,"discounts":1000} //flag 是否开启 discounts 折扣力度
            $table->longText('expand')->comment('业务拓展')->nullable();
            $table->bigInteger('saleNum')->comment('真实销售量');
            $table->longText('barImg')->comment('广告轮播图');
            $table->longText('briefImg')->comment('介绍图');
            $table->longText('numDiscount')->comment('减免规则');
            $table->longText('talk')->comment('评论编号');
            $table->bigInteger('collection')->comment('收藏数');
            $table->unique(['storeId', 'goodsId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftl_shop_infos');
    }
}
