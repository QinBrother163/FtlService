<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlAmountBacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_amount_backs', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('反款编号');
            $table->string('tradeNo')->comment('交易号');
            $table->unsignedBigInteger('upperId')->comment('反款用户（广告商）');
            $table->integer('totalAmount')->comment('订单金额/分');
            $table->integer('payAmount')->comment('买家实付金额');
            $table->integer('offsetAmount')->comment('抵消金额');
            $table->integer('backAmount')->comment('反款金额');
            $table->tinyInteger('backStatus')->comment('反款状态 1:待反款 2:已反款');
            $table->tinyInteger('backType')->comment('反款途径 1:微信 2:支付宝 3:银行卡');
            $table->string('backAccount')->comment('反款账号信息');
            $table->string('backSerialNo')->comment('反款交易号');
            $table->boolean('backPropose')->comment('申请提出反款');
            $table->boolean('backSuccess')->comment('处理反款成功');
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
        Schema::dropIfExists('ftl_amount_backs');
    }
}
