<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- saved from url=(0037)http://dolocker.doplatform.com:10005/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>U锁屏|优锁屏|广州市国数信息技术有限公司|锁屏|赚钱App|手机赚钱|话费充值|锁屏软件|惠锁屏|纹字锁屏|一键锁屏|锁屏精灵|疯狂锁屏|学生赚|拿铁锁屏|学生兼职|红包锁屏</title>
<meta name="Keywords" content="U锁屏,优锁屏,广州市国数信息技术有限公司,锁屏,赚钱App,手机赚钱,话费充值,锁屏软件,惠锁屏,纹字锁屏,一键锁屏,锁屏精灵,疯狂锁屏,学生赚,拿铁锁屏,学生兼职,红包锁屏" />
<meta name="Description" content="U锁屏,又名优锁屏,是广州市国数信息技术有限公司隆重推出的赚钱锁屏APP。与市场上的其他锁屏软件,如惠锁屏,纹字锁屏,一键锁屏,锁屏精灵,疯狂锁屏,学生赚,拿铁锁屏,红包锁屏等APP齐名,主要为用户提供兼职赚钱的机会,并具体话费充值,Q币充值等功能,特别针对有 学生赚 需求的用户提供兼职机会。" />
<link href="<?php echo $this->config->item('domain_static'); ?>common/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->config->item('domain_static'); ?>www/css/web_home.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $this->config->item('domain_static'); ?>common/js/weixin.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('domain_static'); ?>common/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
	
	function IsPC() {
    var userAgentInfo = navigator.userAgent;
    var Agents = ["Android", "iPhone",
                "SymbianOS", "Windows Phone",
                "iPad", "iPod"];
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}

var ret = IsPC();
if(!ret){
	window.location.href='./mobile_index.html';
}
</script>

<body>
	<div id="touming">
	<div class="head">
		<div class="logo"><img src="<?php echo $this->config->item('domain_static'); ?>common/img/logo.png"></div>
		<div class="nav">
			<ul>
				<li><a class="current" href="<?php echo $this->config->item('domain_www'); ?>">首页</a></li>
				<li><a href="<?php echo $this->config->item('domain_www'); ?>home/contact">联系我们</a></li>
			</ul>
		</div>
	</div>
	<div class="center">
		<div class="center_download"><a href="javascript:void(0);" onclick="downLoadApk();"><img src="<?php echo $this->config->item('domain_static'); ?>www/img/download_pc.png"></a></div>
	</div>
	<div class="footer">
		<p>优锁屏 www.ulocker.cn 2015 @ALL Rights Reserved</p>
	</div>
</div>
</body></html>
