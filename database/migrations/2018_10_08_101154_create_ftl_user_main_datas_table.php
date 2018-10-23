<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlUserMainDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_user_main_datas', function (Blueprint $table) {
            $table->bigInteger('userId')->comment('用户编号');
            $table->longText('orderFail')->comment('待支付订单编号 [x,x,x,x,x,x,x,x,x,x]');
            $table->longText('orderSuccess')->comment('交易成功订单编号[x,x,x,x,x,x,x,x,x,x]');
            $table->longText('addressData')->comment('收货地址编号 [x,x,x,x,x,x,x,x,x,x]');
            $table->longText('userCollection')->comment('用户收藏夹编号 [x,x,x,x,x,x,x,x,x,x]');
            $table->longText('userShoppingCart')->comment('用户购物车 [x,x,x,x,x,x,x,x,x,x]');
            $table->longText('userCartVolume')->comment('用户卡劵 [x,x,x,x,x,x,x,x,x,x]');
            $table->timestamps();
            $table->primary('userId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftl_user_main_datas');
    }
}
