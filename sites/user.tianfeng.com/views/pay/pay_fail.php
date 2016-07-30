<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>支付页面</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta HTTP-EQUIV="Pragma" name="no-cache">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/shop_common.css');?>">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/pay.css');?>">
    <script type="text/javascript" src="<?php echo base_url('public/js/resize.js') ;?>" ></script> 
</head>
<body>
<link rel="stylesheet" href="/css/pay/index.css?<?=$static_version;?>">

<div class="page_success">
    <div class="failure">
        <div class="failure_t">
            <p class="failure_img"><img src="/img/common/yes02.png" width="45" height="45"></p>
            <p>支付成功，我们将会尽快为您发货！ </p>
            <p>订单号：<em></em>您的订单正在处理中...</>
            <div class="clearfix btns_box succ_box">
                <a href="/user/order_list" class="back_order_list btns">查看订单</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
