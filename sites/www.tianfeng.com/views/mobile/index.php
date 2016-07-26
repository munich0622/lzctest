<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>优锁屏</title>
</head>
<link href="<?php echo $this->config->item('domain_static'); ?>common/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->config->item('domain_static'); ?>mobile/css/home.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $this->config->item('domain_static'); ?>common/js/weixin.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('domain_static'); ?>common/js/jquery-1.9.1.min.js"></script>
<body>
<div style="width:720px;margin:0 auto;">
	<div class="head">
		<div class="download">
			<a href="javascript:void(0);" onclick="downLoadApk();"><img src="<?php echo $this->config->item('domain_static'); ?>mobile/img/download.png"></a>
		</div>
	</div>
	<?php if($code){?>
	<div class="code">
		<h1>注册填写邀请码奖励<span>5</span>元:<font><?php echo $code;?></font></h1>
	</div>
	<?php }?>
	<div class="nav">
		<ul>
			<li class="current"><a href="<?php echo $this->config->item('domain_www'); ?>mobile/index">关于优锁屏</a></li>
			<li class="line"><a href="<?php echo $this->config->item('domain_www'); ?>mobile/contact">联系我们</a></li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="phone">
		<img src="<?php echo $this->config->item('domain_static'); ?>mobile/img/mobel-phone.png">
	</div>
	<div class="list margin_list">
		<div class="list_img"><img src="<?php echo $this->config->item('domain_static'); ?>mobile/img/mobel-icon1.png"></div>
		<div class="list_word">
			<h5>解锁赚钱</h5>
			<p>左右滑动解锁屏幕就能赚钱，更多赚钱任务和奖励等您拿</p>
		</div>
	</div>
	<div class="clear"></div>
	<div class="list margin_list">
		<div class="list_img"><img src="<?php echo $this->config->item('domain_static'); ?>mobile/img/mobel-icon2.png"></div>
		<div class="list_word">
			<h5>U+任务</h5>
			<p>U锁屏独有特殊赚钱任务，单次奖励最高可达50U币，全民爽翻天！</p>
		</div>
	</div>
	<div class="clear"></div>
	<div class="list margin_list">
		<div class="list_img"><img src="<?php echo $this->config->item('domain_static'); ?>mobile/img/mobel-icon3.png"></div>
		<div class="list_word">
			<h5>邀请好友齐赚钱</h5>
			<p>邀请您的好友用U锁屏赚钱，双方都可获得高额奖励，绝不做"坑友"！</p>
		</div>
	</div>
	<div class="clear"></div>
	<div class="list margin_list">
		<div class="list_img"><img src="<?php echo $this->config->item('domain_static'); ?>mobile/img/mobel-icon4.png"></div>
		<div class="list_word">
			<h5>轻松提现</h5>
			<p>7x24小时随时随地提现，想提就提，有钱就是这么任性！</p>
		</div>
	</div>
	<div class="clear"></div>
	<div class="footer">
		<p>U锁屏 www.ulocker.cn 2015 @ALL Rights Reserved</p>
	</div>
</div>
	
</body>
</html>
