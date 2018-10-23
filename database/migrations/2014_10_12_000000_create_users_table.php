<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('userId');//内部用户编号
            $table->string('appId');//所属的appId
            $table->string('openId');//微信返回的该对应小程序对应用户openId
            $table->string('unionId');//微信返回的唯一公共用户ID
            $table->unsignedTinyInteger('verify');//用户审核状态 1:正常 2:封禁
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
