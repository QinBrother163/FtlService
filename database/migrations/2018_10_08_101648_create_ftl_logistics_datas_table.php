<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtlLogisticsDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftl_logistics_datas', function (Blueprint $table) {
            $table->string('tradeNo')->comment('交易号')->primary();
            $table->string('shipperCode')->comment('快递公司编号(规格书查看 120.77.82.54/logistics/shipperCode.doc)');
            $table->string('LogisticCode')->comment('物流运单号');
            $table->unsignedTinyInteger('status')->nullable()->comment('成功码  0：物流单暂无结果，1：查询成功，2：接口出现异常');
            $table->unsignedTinyInteger('state')->nullable()->comment('物流状态码 0：在途 1：揽件 2：疑难 3：签收 4：退签 5：派件 6：退回');
            // (0：在途，即货物处于运输过程中；
            // 1：揽件，货物已由快递公司揽收并且产生了第一条跟踪信息；
            // 2：疑难，货物寄送过程出了问题；
            // 3：签收，收件人已签收；
            // 4：退签，即货物由于用户拒签、超区等原因退回，而且发件人已经签收；
            // 5：派件，即快递正在进行同城派件；
            //6：退回，货物正处于退回发件人的途中；)
            $table->string('errorMsg')->nullable()->comment('物流发生错误内容');
            $table->text('body')->nullable()->comment('物流内容');
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
        Schema::dropIfExists('ftl_logistics_datas');
    }
}
