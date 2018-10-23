<?php

namespace App\Ftenlog\Ftl\Trade;

use Illuminate\Database\Eloquent\Model;

class FtlNotifyLogs extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id', //记录编号
        'method',//请求方式
        'bizIn',//输入文本
        'bizOut',//输出文本
    ];
}
