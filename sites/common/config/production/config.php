<?php

// 用户cookie配置
$config['cookie_name'] = 'dolocker';
$config['cookie_domain'] = '.doplatform.com';
$config['cookie_account'] = 'account'; // 保存登录账号的cookie键

// cookie密钥
define('KEY_COOKIE_CRYPT', '111111');
define('KEY_COOKIE_CRYPT_IV', '0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF');

//邀请会员获得奖励
define('INVIT_AWARD', 4);
//注册会员获得奖励
define('INVITED_AWARD', 2);
//解锁获得奖励
define('UNLOCK_AWARD', 0.06);
//次数类型的任务暂定为7次
define('TASK_OPEN_NUM', 7);
//被邀请人奖励
define('BEI_INVIT_AWARD', 3);
//微信公众平台TOKEN
define("TOKEN", "weixin");
//关注公众平台得到的钱
define("ATTENT_WEIXIN", 1);
//一级分成比例
define("ONE_SCALE", 0.1);
//二级分成比例
define("TWO_SCALE", 0.05);

//广告类型 邀请好友链接
define("AD_INVIT_FRIENT", '{"Class":"com.doplatform.dolocker.activity.InviteActivity"}');

//广告类型 今日可做链接
define("AD_TODAY_DO", '{"Class":"com.doplatform.dolocker.activity.AppListActivity"}');
//接口域名
$config['domain_www']   = 'http://dolocker.doplatform.com:10005/'; // 首页站点
$config['domain_static']   = 'http://dolocker.doplatform.com:10006/'; // 静态站点
$config['domain_admin']   = 'http://dolocker.doplatform.com:8080/'; // 后台站点
$config['domain_reg']   = 'http://dolocker.doplatform.com:10001/'; // 注册站点
$config['domain_login'] = 'http://dolocker.doplatform.com:10002/'; // 登陆站点
$config['domain_interface'] = 'http://dolocker.doplatform.com:10003/'; // 接口站点
$config['domain_app_img'] = 'http://dolocker.doplatform.com:10004/app/'; // app图片
$config['domain_task_img'] = 'http://dolocker.doplatform.com:10004/task/'; // 任务图片
$config['domain_goods_img'] = 'http://dolocker.doplatform.com:10004/goods/'; // app图片
$config['domain_install_packages'] = 'http://dolocker.doplatform.com:10004/packages/'; // 安装包

$config['domain_image'] = 'http://dolocker.doplatform.com:10004/'; // 图片

$config['domain_subpackage'] = 'http://api.cpsmob.net:11000/subpackage/get_package_data'; // 分包站点

//管理员uid
$config['admin_uids'] = array(1,2);
$config['apk_download'] = 'http://www.winfield-tech.com/apps/ulocker.apk';
$config['sess_save_path'] = '/tmp/';
