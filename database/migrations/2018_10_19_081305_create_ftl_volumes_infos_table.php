<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlVolumesInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_volumes_infos', function (Blueprint $table) {
            $table->increments('volumeId')->comment('优惠卷编号');
            $table->string('applyRange')->comment('适用范围 商品编号[id,id,id]');
            $table->string('numDiscount')->comment('减免规则 {"term":"达到多少钱","discount":"减免多少钱"}');
            $table->integer('validTime')->comment('有效期多少小时');
            $table->integer('stockNum')->comment('库存');
            $table->bigInteger('receiveNum')->comment('领取次数');
            $table->bigInteger('useNum')->comment('使用次数');
            $table->boolean('verify')->comment('已审核');
            $table->string('note')->comment('备注')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftl_volumes_infos');
    }
}
