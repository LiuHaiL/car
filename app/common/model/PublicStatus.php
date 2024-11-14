<?php

declare(strict_types=1);

namespace app\common\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class PublicStatus extends Model
{

    //公共状态变量
    const PUBLIC_STATUS_ZERO = 0; 
    const PUBLIC_STATUS_ONE =  1; 
    const PUBLIC_STATUS_TWO =  2; 
    const PUBLIC_STATUS_THREE =  3; 

    const PUBLIC_TWO =  2;

    //价钱倍数
    const PUBLIC_ONE_HUNDRED = 100;

    const ARRAY_COUNT = 0; //数组的数量

    //支付状态
    const PAY_STATUS_ZERO = 0; //待支付
    const PAY_STATUS_ONE = 1; //已支付
    const PAY_STATUS_TWO = 2; //已取消
    //状态
    public static $status = array(
        self::PAY_STATUS_ZERO => '待支付',
        self::PAY_STATUS_ONE => '已支付',
        self::PAY_STATUS_TWO => '已取消'
    );

    //支付方式状态
    const PAY_TYPE_ONE = 1; //微信支付
    const PAY_TYPE_TWO = 2; //支付宝
    const PAY_TYPE_THREE = 3; //余额支付

    //用户是否正常的状态
    const USER_STATUS_ZERO = 0; //关闭
    const USER_STATUS_ONE = 1; //开启


    //支出 收入
    const USER_MONEY_STATUS_EXPEND = 'expend'; //支出
    const USER_MONEY_STATUS_TAKEIN = 'takein'; //收入

    //认证
    const USER_RZ_STATUS_ZERO = 0; //未认证
    const USER_RZ_STATUS_ONE = 1; //认证通过
    const USER_RZ_STATUS_TWO = 2; //认证审核中
    const USER_RZ_STATUS_THREE = 3; //认证拒绝

     //认证状态
     public static $user_rz_status = array(
        self::USER_RZ_STATUS_ZERO => '未认证',
        self::USER_RZ_STATUS_ONE => '认证通过',
        self::USER_RZ_STATUS_TWO => '审核中',
        self::USER_RZ_STATUS_THREE => '认证拒绝'
    );

    //是否下载
    const USER_DOWNLOAD_STATUS_ONE = 1; //下载超过3天
    const USER_DOWNLOAD_STATUS_TWO = 2; //下载未超过3天
    const USER_DOWNLOAD_STATUS_THREE = 3; //从未下载过

    //用户是否为会员
    const USER_IS_NUMBER_ZERO = 0; //否
    const USER_IS_NUMBER_ONE = 1; //是
    
    //会员类型
    const USER_TYPE_ZERO = 0;
    const USER_TYPE_ONE = 1;
    const USER_TYPE_TWO = 2;
       //状态
       public static $user_type = array(
        self::USER_TYPE_ZERO => '普通用户',
        self::USER_TYPE_ONE => 'VIP用户',
        self::USER_TYPE_TWO => '全站VIP用户'
    );

    //发票模块
    //发票类型
    const INVOICE_TYPE_ONE = 1; //普通发票
    const INVOICE_TYPE_TWO = 2; //专用发票
    public static $invoiceType = array(
        self::INVOICE_TYPE_ONE => '普通发票',
        self::INVOICE_TYPE_TWO => '专用发票'
    );
    //抬头类型
    const INVOICE_HEADER_TYPE_ONE = 1; //企业
    const INVOICE_HEADER_TYPE_TWO = 2; //个人
    public static $invoiceHeaderType = array(
        self::INVOICE_HEADER_TYPE_ONE => '企业',
        self::INVOICE_HEADER_TYPE_TWO => '个人'
    );
    //发票内容
    const INVOICE_SERVICE_ONE = 1; //信息技术服务*云素材服务费
    const INVOICE_SERVICE_TWO = 2; //信息技术服务*技术服务费
    public static $invoiceService = array(
        self::INVOICE_SERVICE_ONE => '信息技术服务*云素材服务费',
        self::INVOICE_SERVICE_TWO => '信息技术服务*技术服务费'
    );

    //微信支付头部配置
    public static function wxCondfig()
    {
        $condfig =  [
            'appid'        =>  get_sys_config('app_id'), //appid
            'mchid'      => rtrim(get_sys_config('mchid')), //服务商户号
            'description' => '',
            'out_trade_no' => '',
            'notify_url' => '',
            'key'           => get_sys_config('skv3'), //密钥
            'serial_no'     => get_sys_config('xlid'), //证书序列号
            'api_cert'      => get_sys_config('certPath') . get_sys_config('api_cert'), //证书
            'api_key'       => get_sys_config('certPath') . get_sys_config('api_key'),   //私钥
        ];
        return $condfig;
    }



    //支付类型
    public static function payType()
    {
        $res[] = [
            'id' => 1,
            'title' => '微信',
            'name' => 'wxpay'
        ];
        $res[] = [
            'id' => 2,
            'title' => '支付宝',
            'name' => 'alipay'
        ];
        $res[] = [
            'id' => 3,
            'title' => '余额',
            'name' => 'blance'
        ];
        return $res;
    }
}
