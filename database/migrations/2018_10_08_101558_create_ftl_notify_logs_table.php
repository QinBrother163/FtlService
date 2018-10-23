<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlNotifyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_notify_logs', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('记录编号');
            $table->bigInteger('method')->comment('请求方式');
            $table->longText('bizIn')->comment('输入文本');
            $table->longText('bizOut')->comment('输出文本');
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
        Schema::dropIfExists('ftl_notify_logs');
    }
}
