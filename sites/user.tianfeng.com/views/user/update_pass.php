<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>修改密码</title>
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/font-awesome.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/ectouch.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/user.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/photoswipe.css');?>">
</head>

<body>
<div class="con">
<?php echo $this->load->view("head", array(), true);?>
<form name="subform" class="subform" method="post" action="/user/update_pass_sub" >
<section class="flow-consignee ect-bg-colorf">
	<ul>
   	  <li>
   	    <div class="input-text"><b class="pull-left">原密码：</b><span>
        <input placeholder="原密码" name="password" type="password"></span></div></li>
      <li>
        <div class="input-text"><b>新密码：</b><span>
        <input placeholder="新密码" name="pwd" type="password"></span></div></li>
      <li><div class="input-text"><b>确认密码：</b><span><input placeholder="确认密码" name="pwd_repeat" type="password"></span></div></li>
    </ul>
</section>
<div class="two-btn ect-padding-tb ect-padding-lr ect-margin-tb text-center">
    <input name="submit" type="submit" class="btn btn-info ect-btn-info ect-bg" value="确认修改" />
</div>
</form>
</div>
</div>
</body>
</html>