<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlUserCoinDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_user_coin_datas', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('编号');
            $table->bigInteger('userId')->comment('用户编号');
            $table->integer('addCoin')->comment('增长金币数量');
            $table->bigInteger('coinNum')->comment('变化后的金币数量');
            $table->integer('coinChannel')->comment('金币(获取/消耗)途径(消费交易号/获取活动号)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftl_user_coin_datas');
    }
}
