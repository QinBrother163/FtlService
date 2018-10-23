<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlPageInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_page_infos', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->integer('showId')->comment('显示控件编号');
            $table->integer('pageId')->comment('表现在页面');
            $table->string('content')->comment('内容');
            $table->boolean('verify')->comment('正式开放');
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
        Schema::dropIfExists('ftl_page_infos');
    }
}
