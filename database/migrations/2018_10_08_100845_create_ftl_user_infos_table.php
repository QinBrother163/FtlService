<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_user_infos', function (Blueprint $table) {
            $table->bigInteger('userId')->comment('用户编号');
            $table->unsignedTinyInteger('userType')->comment('用户类型 //1:普通用户 2:广告商');
            $table->bigInteger('userUpper')->comment('-1为无广告商用户 用户编号则为有广告商用户');
            $table->string('userName')->comment('用户名字');
            $table->unsignedTinyInteger('userSex')->comment('用户性别 1->男 2->女');
            $table->string('userCity')->comment('用户城市');
            $table->string('userProvince')->comment('用户省份');
            $table->string('userCountry')->comment('用户国家');
            $table->string('userImg')->comment('用户头像');
            $table->bigInteger('userCoin')->comment('用户抵消金额币');
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
        Schema::dropIfExists('ftl_user_infos');
    }
}
