<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>显示个人二维码</title>
<style>
.task_img {
position:absolute;top:250px;right:450px;
border:2px solid #686868;


}
.qrcode{}
</style>
<link href="<?php   echo base_url('public/css/mine.css');?>" type="text/css" rel="stylesheet">
<script src="<?php  echo base_url('public/js/jquery-1.7.2.min.js');?>"></script> 
<script src="<?php  echo base_url('public/js/common.js');?>"></script> 
</head>
<body>
<div class="div_head"><span style="float:left">当前位置是：用户任务管理-》查看个人二维码</span></div>
<img style="width:50%;height:50%;margin-left:50%;" src="<?php echo $qr_code_img_url;?>" />
</body>
</html>

<script type="text/javascript">
$(document).ready(function($) {
	$('.button').click(function(){
		var phone = $("input[name='phone']").val();
		if(!check_phone(phone)){
			alert('手机格式不正确！');
			return false;
		}
		
		$(".subform").submit();
	});
});

</script>
