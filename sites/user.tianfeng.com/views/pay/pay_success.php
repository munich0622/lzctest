<!DOCTYPE html>
<html data-dpr="1" style="font-size: 40px;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>支付成功</title>
<meta name="Keywords" content="盐城天丰">
<meta name="Description" content="盐城天丰！">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta http-equiv="Pragma" name="no-cache">

<link rel="stylesheet" href="<?php   echo base_url('public/css/common_pay.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/css/index_pay.css');?>">
</head>
<body style="font-size: 12px;">
<header id="header">
    <table>
        <tbody>
        <tr>
            <td class="icon"><a href="javascript:void(0)"><span class="return_btn"></span></a></td>
            <td class="c">支付成功</td>
            <td class="icon"><a href="javascript:;"><span class="category_btn"></span></a></td>
        </tr>
        </tbody>
    </table>
</header>
<div class="page_success">
    <div class="failure">
        <?php if($pay_info['type'] == 1):?>
        <div class="failure_t">
            <p class="failure_img"><img src="/public/img/yes02.png" width="45" height="45"></p>
            <p>支付成功，恭喜你注册成功！ </p>
            <p>订单号：<em><?php echo $pay_info['myself_trade_no'];?></em>
            </p><div class="clearfix btns_box succ_box">
                <a href="/user/index" class="back_order_list btns">回到首页</a>
            </div>
        </div>
        <?php else:?>
        <div class="failure_t">
            <p class="failure_img"><img src="/public/img/yes02.png" width="45" height="45"></p>
            <p>支付成功，恭喜你成功升<?php echo $user_info['level'];?>级！ </p>
            <p>订单号：<em><?php echo $pay_info['myself_trade_no'];?></em>
            </p><div class="clearfix btns_box succ_box">
                <a href="/user/index" class="back_order_list btns">回到首页</a>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>
</div>
</body>
</html>