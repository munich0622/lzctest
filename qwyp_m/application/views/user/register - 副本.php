<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人管理</title>
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
<div class="div_head">
    <span style="float:left">当前位置是：用户任务管理-》个人管理</span>
</div>
<form name="subform" class="subform" method="post" action="/register/register_sub" >
	用户名:<input type="text" name="uname" value="" /><br />
	手机号:<input type="text" name="phone" value="" /><br />
	微信名称:<input type="text" name="weixin_name" value="" /><br />
	身份证号:<input type="text" name="id_card" value="" /><br />
	银行卡号:<input type="text" name="bank_num" value="" /><br />
	<?php if(!empty($bank)):?>
	选择所属银行
	<select name="bank"> 
	  <option value ="0" selected="selected">请选择</option>  
	<?php foreach($bank as $key=>$val):?>
      <option value ="<?php echo $val['id']?>"><?php echo $val['bank_name'];?></option>  
    <?php endforeach;?>
    </select>
    <br />
    <?php endif;?>
	密码:<input type="text" name="password" value="" /><br />
	确认密码:<input type="text" name="password_confirm" value="" /><br />
	<input type="hidden" name="tj_uid" value="<?php echo $uid;?>" /><br />
	<input type="button" class="button" value="保存" />
	<input type="reset" value="取消" />
</form>
</body>
</html>

<script type="text/javascript">
$(document).ready(function($) {
	$('.button').click(function(){
		var phone       = $("input[name='phone']").val();
		var uname       = $("input[name='uname']").val();
		var weixin_name = $("input[name='weixin_name']").val();
		var id_card     = $("input[name='id_card']").val();
		var bank_num    = $("input[name='bank_num']").val();
		var password    = $("input[name='password']").val();
		var password_confirm = $("input[name='password_confirm']").val();
		
		if(!check_phone(phone)){
			alert('手机格式不正确！');
			return false;
		}

		if(!check_password(uname)){
			alert('用户名要在6-20个字符之间！');
			return false;
		}

		if(weixin_name == ''){
			alert('微信名称不能位空！');
			return false;
		}

		if(!checkParity(id_card)){
			alert('身份证格式不正确！');
			return false;
		}
		
		var filter  = /^\d{16}|\d{19}$/;
		if (!check_bank(bank_num)){
			alert('银行卡号不正确！');
			return false;
		}

		
		if(!check_password(password) || !check_password(password_confirm)){
			alert('密码要在6-20个字符之间！');
			return false;
		}

		if(password != password_confirm){
			alert('两次输入的密码不相等！');
			return false;
		}
		
		
		$(".subform").submit();
	});
});

</script>
