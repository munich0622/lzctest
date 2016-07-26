<!DOCTYPE html>
<html lang="zh-CN"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title></title>
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/font-awesome.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/ectouch.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/user.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/photoswipe.css');?>">
</head>

<body>
<div class="con">
<div class="ect-bg">
  <header class="ect-header ect-margin-tb ect-margin-lr text-center ect-bg icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history"></a> <span>登录</span></header>
</div>
 <form name="formLogin" action="<?php echo site_url('login/signin');?>" method="post" class="validforms">
 <div class="flow-consignee ect-bg-colorf">
     <section>
      <ul>
      	<li>
        <div class="input-text"><b>用户名：</b><span><input placeholder="用户名/手机" name="admin_user" type="text" class="inputBg" id="username" datatype="*" nullmsg="请填写信息！"></span></div>
        </li>
        <li>
        <div class="input-text"><b>密码：</b><span><input placeholder="密码" name="admin_psd" type="password" class="inputBg" datatype="*6-16"></span></div>
        </li>
       </ul>
    </section>
  </div>
  <p class="ect-checkbox ect-padding-tb ect-margin-tb ect-margin-bottom0 ect-padding-lr">
     <input type="checkbox" value="1" name="remember" id="remember" class="l-checkbox">
     <label for="remember">记住本次登录。<i></i></label>
  </p>
  <div class="ect-padding-lr ect-padding-tb"> 
  <input type="submit" class="btn btn-info ect-btn-info ect-bg" value="立即登陆">
  </div>
  </form>
  <p class="ect-padding-lr ect-margin-tb text-right ect-margin-bottom0" style="clear:both"><a href="javascript:void(0)">忘记密码</a> </p>
  </div>
</body>
</html>