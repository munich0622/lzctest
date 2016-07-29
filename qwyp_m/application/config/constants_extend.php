<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*------------以下常量设置后传外网会一致-------------*/

//站点所属区域
define('SITE_ZONE',  			1);  //1国内2香港

//订单状态
define('OS_UNPAID',            	0);  //等待买家付款
define('OS_PAID',            	1);  //买家已付款
define('OS_PREPARING',          2);  //卖家配货中
define('OS_SHIPPING',           3);  //卖家已发货
define('OS_RECEIVED',           4);  //买家已收货
define('OS_INSTALLED',          5);  //已安装
define('OS_FINISH',           	6);  //订单完成
define('OS_RETURN_SUCCEED',     10);  //退款退货成功
define('OS_PAID_PART',     		11);  //买家已付订金
define('OS_CANCEL',           	21);  //订单取消

//退货退款状态
define('RS_REQUEST_RETURN',      1);  //买家申请退货退款
define('RS_REQUEST_REFUND',      2);  //买家申请退款
define('RS_REPEAL_REFUND',       3);  //买家撤销退款申请
define('RS_AGREE_RETURN',        4);  //商家同意退货，买家退货中
define('RS_RETURNED',            5);  //买家已退货，商家确认收货中
define('RS_RECEIVED',            6);  //商家收到退货
define('RS_REFUSE_REFUND',       7);  //商家拒绝退款
define('RS_AGREE_REFUND',        8);  //商家同意退款
define('RS_SUCCEED',             9);  //退款成功

//订单类型
define('OT_GENERAL',            0);  //普通订单
define('OT_GIFT',            	1);  //赠品订单
define('OT_GROUP_BUY',          2);  //团购订单
define('OT_GROUP_REV',          3);  //预售(反向团购)
define('OT_SECKILL',        	4);  //秒杀
define('OT_GROUP_DIRECT',       5);  //购买直购团代金券
define('OT_COUPON_BUY',       	6);  //购买优惠券
define('OT_WHOLE_ROOM',       	7);  //全屋购

//订单来源
define('OBF_PC',            	1);  //PC商城
define('OBF_M',            		2);  //M端
define('OBF_B',                 3);  //B端

//商品优惠类型
define('GDT_COUPON',      		1);  //优惠券
define('GDT_RED_PACKET',      	2);  //红包
define('GDT_DISCOUNT',      	3);  //打折
define('GDT_AMOUNT_MINUS',      4);  //满减
define('GDT_GIFT',        		5);  //赠品
define('GDT_SECKILL',       	6);  //秒杀
define('GDT_GROUP_BUY',         7);  //团购
define('GDT_GROUP_REV',         8);  //预售
define('GDT_PACKAGE',           9);  //组合优惠

//优惠券类型
define('CT_PLATFORM',           1);  //平台级
define('CT_JOIN',           	2);  //品牌商级
define('CT_BRAND',           	3);  //品牌级
define('CT_CATEGORY',           4);  //分类级
define('CT_GOODS',          	5);  //商品级

//导购员佣金状态
define('RT_UNCONFIRM',          0);  //未确认
define('RT_CONFIRMED',          1);  //已确认
define('RT_UNPAID',           	2);  //未结算
define('RT_PAID',           	3);  //已结算
define('RT_VOID',          		9);  //无效

//未支付订单自动取消时长--3天
define('UNPAY_ORDER_CANCEL_EXPIRE',  		3*86400);
//订单发货后自动确认收货时长--10天
define('AUTO_CONFIRM_RECEIVED_EXPIRE',  	10*86400);
//已完成订单支持退款退货时长--30天
define('FINISH_ORDER_ALLOW_RETURN_EXPIRE',  30*86400);





/* End of file constants.php */
/* Location: ./application/config/constants.php */