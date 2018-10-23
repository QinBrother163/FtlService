<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlUserAddressDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_user_address_datas', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('编号');
            $table->bigInteger('userId')->comment('用户编号');
            $table->string('name')->comment('联系人名称');
            $table->string('phone')->comment('联系电话');
            $table->text('address')->comment('收货地址');
            $table->tinyInteger('default')->comment('是否默认收货地址 1:不默认 2:默认');
            $table->tinyInteger('verify')->comment('是否已删除 1:正在使用 2:已删除');
            $table->string('note')->comment('标签');
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
        Schema::dropIfExists('ftl_user_address_datas');
    }
}
