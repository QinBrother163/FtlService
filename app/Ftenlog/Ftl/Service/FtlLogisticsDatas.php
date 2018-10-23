<?php

namespace App\Ftenlog\Ftl\Service;

use Illuminate\Database\Eloquent\Model;

class FtlLogisticsDatas extends Model
{
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'tradeNo',// 交易号
        'retailId',//渠道号
        'shipperCode', //快递公司编号(查看快递100的快递公司编号文档)
        'LogisticCode', //物流运单号
        //0：物流单暂无结果，1：查询成功，2：接口出现异常
        'status', // 成功码
        // (0：在途，即货物处于运输过程中；
        // 1：揽件，货物已由快递公司揽收并且产生了第一条跟踪信息；
        // 2：疑难，货物寄送过程出了问题；
        // 3：签收，收件人已签收；
        // 4：退签，即货物由于用户拒签、超区等原因退回，而且发件人已经签收；
        // 5：派件，即快递正在进行同城派件；
        //6：退回，货物正处于退回发件人的途中；)
        'state', // 物流状态码
        'body', // 物流内容
        'errorMsg' //物流发生错误内容
    ];
    protected $primaryKey = 'tradeNo';
}
