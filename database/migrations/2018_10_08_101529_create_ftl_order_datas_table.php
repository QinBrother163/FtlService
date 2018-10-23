<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlOrderDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_order_datas', function (Blueprint $table) {
            $table->string('tradeNo')->comment('交易号');
            $table->string('orderNo')->comment('输入订单号');
            $table->unsignedBigInteger('userId')->comment('买家的用户编号');
            $table->integer('storeId')->comment('门店编号');
            $table->string('storeName')->comment('门店名称')->nullable();
            $table->integer('totalAmount')->comment('订单金额/分');
            $table->string('subject')->comment('订单标题')->nullable();
            $table->string('body')->comment('订单描述')->nullable();
            $table->text('goodsDetail')->comment('订单包含的商品列表信息');
            $table->text('extendParams')->comment('业务扩展参数')->nullable();
            $table->text('extUserInfo')->comment('买家额外信息')->nullable();
            $table->dateTime('payTimeout')->comment('最晚付款时间');
            $table->integer('payAmount')->comment('买家实付金额/分')->nullable();
            $table->integer('receiptAmount')->comment('实收金额/分')->nullable();
            $table->string('serialNo')->comment('支付流水号')->nullable();
            $table->integer('tradeStatus')->comment('交易状态');
            $table->dateTime('payTime')->comment('交易支付时间')->nullable();
            $table->bigInteger('address')->comment('收货地址编号');
            $table->integer('offsetMoney')->comment('抵消金额');
            $table->timestamps();

            $table->primary('tradeNo');
            $table->index('userId');
            //$table->index('serialNo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftl_order_datas');
    }
}
