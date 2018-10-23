<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlGoodsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_goods_types', function (Blueprint $table) {
            $table->increments('goodsTypeId')->comment('产品类型编号');
            $table->string('goodsTypeName')->comment('产品类型名字');
            $table->string('goodsTypeDesc')->comment('产品类型描述');
            $table->string('goodsTypeImg')->comment('产品类型广告图');
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
        Schema::dropIfExists('ftl_goods_types');
    }
}
