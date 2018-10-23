<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlUpperUserDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_upper_user_datas', function (Blueprint $table) {
            $table->bigInteger('userId')->comment('用户编号');
            $table->longText('userChild')->comment('用户所包含的下级用户 [x,x,x,x,x,x,x,x,x,x]');
            $table->longText('backData')->comment('反款记录编号[x,x,x,x,x,x,x,x,x,x]');
            $table->text('wxBack')->comment('微信反款联系账号');
            $table->text('zfbBack')->comment('支付宝反款联系账号');
            $table->text('bankCard')->comment('银行卡信息(JSON格式:[{"cardType":银行类型,"cardNo":银行卡号码,"cardName":持卡人名称}])');
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
        Schema::dropIfExists('ftl_upper_user_datas');
    }
}
