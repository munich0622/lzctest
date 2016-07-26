<?php

// 用户cookie配置
$config['cookie_name'] = 'dolocker';
$config['cookie_domain'] = '.dolocker.com';
$config['cookie_account'] = 'account'; // 保存登录账号的cookie键

// cookie密钥
define('KEY_COOKIE_CRYPT', '111111');
define('KEY_COOKIE_CRYPT_IV', '0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF');

//邀请会员获得奖励
define('INVIT_AWARD', 3);
//被邀请会员获得奖励
define('INVITED_AWARD', 2);
//解锁获得奖励
define('UNLOCK_AWARD', 0.02);

//次数类型的任务暂定为7次
define('TASK_OPEN_NUM', 7);



//接口域名
$config['domain_admin']   = 'http://www.dolocker.com/'; // 注册站点
$config['domain_reg']   = 'http://www.dolocker.com:10001/'; // 注册站点
$config['domain_login'] = 'http://www.dolocker.com:10002/'; // 登陆站点
$config['domain_interface'] = 'http://www.dolocker.com:10003/'; // 接口站点
$config['domain_app_img'] = 'http://www.dolocker.com:10004/app/'; // app图片
$config['domain_task_img'] = 'http://www.dolocker.com:10004/task/'; // 任务图片
$config['domain_goods_img'] = 'http://www.dolocker.com:10004/goods/'; // app图片
$config['domain_install_packages'] = 'http://www.dolocker.com:10004/packages/'; // 安装包

$config['domain_image'] = 'http://img.dolocker.com/'; // 图片

//管理员uid
$config['admin_uids'] = array(1,2);
$config['sess_save_path'] = '/tmp/';
date_default_timezone_set('Africa/Lagos');
?>
