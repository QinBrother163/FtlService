<?php

namespace App\Ftenlog\Ftl\Trade;

use App\Ftenlog\ErrorCode;
use App\Ftenlog\Ftl\FtlController;
use App\Ftenlog\Ftl\Service\FtlPageInfos;
use App\FtlVolumesInfos;
use DB;
use Illuminate\Http\Request;
use Psy\Util\Json;

class FtlTradeController extends FtlController
{
    protected function doMethod($request)
    {
        $method = $request['method'];
        if ($method == '/ftenlog/trade/getGoodsInfo') {
            return $this->getGoodsInfo($request);
        }
        if ($method == '/ftenlog/trade/getPagesInfo') {
            return $this->getPagesInfo($request);
        }
        if ($method == '/ftenlog/trade/getVolumesInfo'){
            return $this->getVolumesInfo($request);
        }
        if ($method == '/ftenlog/trade/getUserVolumes'){
            return $this->getUserVolumes($request);
        }
        return [
            'code' => ErrorCode::NO_FIND_METHOD,
            'msg' => '找不到指定method的方法',
        ];
    }

    protected function getGoodsInfo($request){
        $ret = $this->checkAccountLogin();
        if ($ret) {
            return $ret;
        }
        $goodsType = (new FtlGoodsTypes)->where(['verify'=> 1])->get();
        $goodsInfo = (new FtlShopInfos)->where(['verify'=> 1])->get();

       for($i = 0; $i < sizeof($goodsInfo); $i++){
           $responce[$i]['productId'] = $goodsInfo[$i]->productId; //商品编号
           $responce[$i]['goodsId'] = $goodsInfo[$i]->goodsId;   //商品内容名称编号
           $responce[$i]['goodsName'] = $goodsInfo[$i]->goodsName; //商品名称
           $responce[$i]['goodsDesc'] = $goodsInfo[$i]->goodsDesc; //商品描述
           $responce[$i]['goodsImg'] = $goodsInfo[$i]->goodsImg; //商品大图
           $responce[$i]['complex'] = $goodsInfo[$i]->complex; //是否套餐
           $responce[$i]['comment'] = $goodsInfo[$i]->comment; //套餐内容
           $responce[$i]['category'] = $goodsInfo[$i]->category; //商品类型
           $responce[$i]['price'] = $goodsInfo[$i]->price; //金额
           $responce[$i]['storeId'] = $goodsInfo[$i]->storeId; //商家号
           $responce[$i]['storeName'] = $goodsInfo[$i]->storeName; //商家名
           $discount = json_decode($goodsInfo[$i]->discount);
           $responce[$i]['discount'] = $discount->flag == 1 ? $discount->discounts : 0; //打折力度
           $expand = json_decode($goodsInfo[$i]->expand);
           $responce[$i]['saleNum'] = $expand->saleNum; //虚拟的销售量
           $responce[$i]['good'] = $expand->good; //虚拟的好评
           $responce[$i]['barImg'] = $goodsInfo[$i]->barImg;//轮播图列表
           $responce[$i]['briefImg'] = $goodsInfo[$i]->briefImg;//简介图列表
           $responce[$i]['talk'] = sizeof(json_decode($goodsInfo[$i]->talk));//评论数

           $numDiscount = json_decode($goodsInfo[$i]->numDiscount);
           for($k = 0; $k < sizeof($numDiscount); $k++){
               $numDiscountBiz[$k]['term'] = $numDiscount[$k]->term;
               $numDiscountBiz[$k]['discount'] = $numDiscount[$k]->discount;
           }
           $responce[$i]['numDiscount'] = isset($numDiscountBiz) ? $numDiscount : '[]'; //满减折扣
       }

        return $this->biz([
            "goodsType"=>$goodsType,
            "goodsInfo"=>$responce
        ]);
    }

    protected function getPagesInfo($request){
        $ret = $this->checkAccountLogin();
        if ($ret) {
            return $ret;
        }
        $pageInfo = (new FtlPageInfos)->where(['verify'=> 1])->get();
        return $this->biz([
            "pageInfo"=>$pageInfo,
            ]);
    }

    /*
     * 获取优惠卷列表
     */
    protected function getVolumesInfo($request){
        $ret = $this->checkAccountLogin();
        if ($ret) {
            return $ret;
        }
        $volumesInfo = (new FtlVolumesInfos)->where(['verify'=> 1])->get();
        return $this->biz([
            "volumesInfo"=>$volumesInfo,
        ]);
    }

    /*
     * 获取用户优惠卷
     *      type : 1:有效期
     *             2:已失效
     *             3:已使用
     *
     *      start: 查询起点
     */
    protected function getUserVolumes($request){
        $limit = 20;
        $ret = $this->checkAccountLogin();
        if ($ret) {
            return $ret;
        }
        $user = $this->user;
        $bizContent = $request['bizContent'];
        $bizIn = json_decode($bizContent);
        {
            //! 输入参数检查
            $subCode = 0;
            $subMsg = '';
            if($subCode == 0 && empty($bizIn->type)){
                $subCode = 1;
                $subMsg = 'not set UserIn param type';
            }
            if ($subCode != 0) {
                return $this->error(ErrorCode::PARAM_MISS,
                    '获取优惠卷输入参数缺失', $subCode, $subMsg);
            }
        }

        $start = isset($bizIn->start) ? $bizIn->start : 0;

        $log = DB::table('ftl_user_main_datas')
            ->where(['userId'=>$user->userId])
            ->first(['userCartVolume']);
        $arr = json_decode($log->userCartVolume);

        $type = $bizIn->type;
        $nowDate = date('y-m-d H:i:s',time());
        switch ($type){
            case 1:
                //有效期
                $info = DB::table('ftl_user_volumes')
                    ->whereIn('id',$arr)
                    ->whereDate('outTime',">",$nowDate)
                    ->where(['status'=>0])
                    ->orderBy('created_at', 'desc')
                    ->leftJoin('ftl_volumes_infos', 'ftl_user_volumes.volumeId', '=', 'ftl_volumes_infos.volumeId')
                    ->get(['ftl_volumes_infos.volumeId',
                        'ftl_volumes_infos.applyRange',
                        'ftl_volumes_infos.numDiscount',
                        'ftl_volumes_infos.verify',
                        'ftl_volumes_infos.note',
                        'ftl_user_volumes.outTime',
                        'ftl_user_volumes.status']);
                break;
            case 2:
                //已失效
                $info = DB::table('ftl_user_volumes')
                    ->offset($start)
                    ->limit($limit)
                    ->whereIn('id',$arr)
                    ->where('status','!=',3)
                    ->whereDate('outTime',"<",$nowDate)
                    ->orderBy('created_at', 'desc')
                    ->leftJoin('ftl_volumes_infos', 'ftl_user_volumes.volumeId', '=', 'ftl_volumes_infos.volumeId')
                    ->get(['ftl_volumes_infos.volumeId',
                        'ftl_volumes_infos.applyRange',
                        'ftl_volumes_infos.numDiscount',
                        'ftl_volumes_infos.verify',
                        'ftl_volumes_infos.note',
                        'ftl_user_volumes.outTime',
                        'ftl_user_volumes.status']);
                break;
            case 3:
                //已使用
                $info = DB::table('ftl_user_volumes')
                    ->offset($start)
                    ->limit($limit)
                    ->whereIn('id',$arr)
                    ->where(['status'=>3])
                    ->orderBy('created_at', 'desc')
                    ->leftJoin('ftl_volumes_infos', 'ftl_user_volumes.volumeId', '=', 'ftl_volumes_infos.volumeId')
                    ->get(['ftl_volumes_infos.volumeId',
                        'ftl_volumes_infos.applyRange',
                        'ftl_volumes_infos.numDiscount',
                        'ftl_volumes_infos.verify',
                        'ftl_volumes_infos.note',
                        'ftl_user_volumes.outTime',
                        'ftl_user_volumes.status']);
                break;
        }

        if(!$info){
            $info = [];
        }
        $last = sizeof($info) != $limit;
        return $this->biz([
            'type'=>$type,
            'info'=>$info,
            'last'=>$last
        ]);

    }
}
