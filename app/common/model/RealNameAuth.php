<?php

declare(strict_types=1);

namespace app\common\model;

use think\Model;
use think\Exception;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class RealNameAuth extends BaseModel
{


    //入库后
    public static function onAfterInsert($model)
    {
        $user = User::where('id', $model->user_id)->lock(true)->find();
        if (!$user) {
            throw new Exception("The user can't find it");
        }
        $user->is_rz = PublicStatus::USER_RZ_STATUS_TWO;
        $user->save();
        return true;
    }



    //入库前
    public static function onBeforeInsert($model){
        $repeat = $model->where([
            ['icard', '=', $model->icard]
        ])->find();
        if($repeat){
            throw new Exception("ID card exists");
        }
        $user = User::where('id', $model->user_id)->lock(true)->find();
        if (!$user) {
            throw new Exception("The user can't find it");
        }
        if ($user->is_rz !== 0) {
            throw new Exception("Real-name authentication under review");
        }
        return true;
    }
}
