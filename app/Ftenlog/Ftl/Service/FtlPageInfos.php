<?php
namespace App\Ftenlog\Ftl\Service;

use Illuminate\Database\Eloquent\Model;

class FtlPageInfos extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id', //编号
        'showId',//显示控件编号
        'pageId',//表现在页面
        'content',//内容
        'verify',//正式开放
        'note',//备注
    ];
}
