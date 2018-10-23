<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlUserVolumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_user_volumes', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('编号');
            $table->bigInteger('userId')->comment('用户编号');
            $table->integer('volumeId')->comment('优惠卷编号');
            $table->dateTime('outTime')->comment('失效时间');
            $table->unsignedTinyInteger('status')->comment('状态 0:未使用 1:已使用 2:已失效');
            $table->string('tradeNo')->comment('交易号')->nullable();
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
        Schema::dropIfExists('ftl_user_volumes');
    }
}
