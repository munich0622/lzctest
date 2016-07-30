<!DOCTYPE html>
<html data-dpr="1" style="font-size: 40px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>支付失败</title>
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
            <td class="icon"><a href="/user/index"><span class="return_btn"></span></a></td>
            <td class="c">支付失败</td>
            <td class="icon"><a href="javascript:;"><span class="category_btn"></span></a></td>
        </tr>
        </tbody>
    </table>
</header>


<div class="page_fail">
    <div class="failure">
        <div class="failure_t">
            <p class="failure_img"><img src="/public/img/fail.png" width="45" height="45"></p>
            <p>付款失败</p>
            <div class="clearfix btns_box">
                <a href="/user/index" class="pay_again btns">返回首页</a>
          	    <?php if($pay_info['type'] == 1):?>
                <a href="/user/pay_register" class="pay_again btns" style="float:right;">重新付款</a>
                <?php else :?>
                <a href="/user/display_user_level" class="pay_again btns" style="float:right;">重新付款</a>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
</div>

</body></html>