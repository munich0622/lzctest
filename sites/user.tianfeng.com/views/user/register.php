<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>天丰会员注册</title>
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/font-awesome.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/ectouch.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/user.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/photoswipe.css');?>">
</head>

<body>
<div class="con">
<div class="ect-bg">
  <header class="ect-header ect-margin-tb ect-margin-lr text-center ect-bg icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history"></a> <span>注册</span></header>
</div>
<div class="user-register"> 
  <div class="tab-content">
    <div class="tab-pane active" id="one">
      <form name="subform" class="subform" method="post" action="/register/register_sub" >
        <div class="flow-consignee ect-bg-colorf">
          <ul>
            <li>
              <div class="input-text"><b>登录名称：</b><span>
                <input placeholder="请输入手机号码" name="phone" type="text">
                </span></div>
            </li>
            <li>
              <div class="input-text"><b>姓名：</b><span>
                <input placeholder="请输入姓名" name="uname"  type="text">
                </span></div>
            </li>
            <li>
              <div class="input-text"><b>微信号：</b><span>
                <input placeholder="请输入微信号" name="weixin_name" type="text">
                </span></div>
            </li>
            <li>
              <div class="input-text"><b>身份证：</b><span>
                <input placeholder="请输入身份证" name="id_card"  type="text">
                </span></div>
            </li>
            <li>
              <div class="input-text"><b>银行卡号：</b><span>
                <input placeholder="请输入银行卡号" name="bank_num"  type="text">
                </span></div>
            </li>
            <li>
              <div class="input-text"><b>选择所属银行：</b>
              <span>
              	<select name="bank"> 
            	  <option value ="0" selected="selected">请选择</option>  
            	  <?php foreach($bank as $key=>$val):?>
                  <option value ="<?php echo $val['id']?>"><?php echo $val['bank_name'];?></option>  
                  <?php endforeach;?>
                </select>
              </span>
              </div>
            </li>
            <li>
              <div class="input-text pas_1"><b>密码：</b><span>
                <input  placeholder="请输入登录密码" class="inputBg" name="password" type="password"  id="password1" datatype="*6-20">
                <input placeholder="请输入登录密码" class="inputBg" id="password_text" type="text" style="display:none;" onBlur="blurText()" />
                </span><i class="glyphicon glyphicon-eye-open" onClick="clickText();"></i></div>
            </li>
            <li>
              <div class="input-text pas_2"><b>确认密码：</b><span>
                <input  placeholder="请再输入登录密码" class="inputBg" name="password_confirm"  id="password2"  type="password" datatype="*6-20">
                <input placeholder="请再输入登录密码" class="inputBg" id="password_text_2" type="text" style="display:none;" onBlur="blurText_2()" />
                </span><i class="glyphicon glyphicon-eye-open" onClick="clickText_2();"></i></div>
            </li>
          </ul>
        </div>
        <div class="ect-padding-lr ect-padding-tb">
          <input type="hidden" name="tj_uid" value="<?php echo $uid;?>" />
          <button name="" type="submit" class="btn btn-info ect-btn-info ect-colorf ect-bg">注册</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

 <a id="scrollUp" href="#top" style="position: fixed; z-index: 10;"><i class="fa fa-angle-up"></i></a>
<style>
#scrollUp {
	border-radius:100%;
	background-color: #777;
	color: #eee;
	font-size: 40px;
	line-height: 1;text-align: center;text-decoration: none;bottom: 1em;right: 10px;overflow: hidden;width: 46px;
	height: 46px;
	border: none;
	opacity: 0.6;
}
</style>
<script type="text/javascript" src="<?php echo base_url('public/mobile/js/jquery.min.js') ;?>" ></script> 
<script language="javascript">
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
 
<script type="text/javascript">
/*点击更换密码框为文本框*/
function clickText(){
	if($("#password_text").is(":hidden")&&$("#password1").is(":visible")&&!$(".input-text .glyphicon-eye-open").hasClass("glyphicon-eye-close")){
	  	var pwd = $("#password1").val();
       	$("#password1").hide();
       	$("#password_text").val(pwd).show();
		$(".pas_1 .glyphicon-eye-open").addClass("glyphicon-eye-close");
	}else{
		var pwd_text = $("#password_text").val();
       	$("#password_text").hide();
       	$("#password1").val(pwd_text).show();
		$(".pas_1 .glyphicon-eye-open").removeClass("glyphicon-eye-close");			
	}
}
function blurText(){
	if($("#password_text").is(":hidden")&&$("#password1").is(":visible")&&!$(".pas_1 .glyphicon-eye-open").hasClass("glyphicon-eye-close")){
	  	var pwd = $("#password1").val();
       	$("#password_text").val(pwd);
	}else{
		var pwd_text = $("#password_text").val();
       	$("#password1").val(pwd_text);		
	}
}


/*点击更换密码框为文本框*/
function clickText_2(){
	if($("#password_text_2").is(":hidden")&&$("#password2").is(":visible")&&!$(".pas_2 .glyphicon-eye-open").hasClass("glyphicon-eye-close")){
	  	var pwd = $("#password2").val();
       	$("#password2").hide();
       	$("#password_text_2").val(pwd).show();
		$(".pas_2 .glyphicon-eye-open").addClass("glyphicon-eye-close");
	}else{
		var pwd_text = $("#password_text_2").val();
       	$("#password_text_2").hide();
       	$("#password2").val(pwd_text).show();
		$(".pas_2 .glyphicon-eye-open").removeClass("glyphicon-eye-close");			
	}
}
function blurText_2(){
	if($("#password_text_2").is(":hidden")&&$("#password2").is(":visible")&&!$(".pas_2 .glyphicon-eye-open").hasClass("glyphicon-eye-close")){
	  	var pwd = $("#password2").val();
       	$("#password_text_2").val(pwd);
	}else{
		var pwd_text = $("#password_text_2").val();
       	$("#password2").val(pwd_text);		
	}
}

</script>