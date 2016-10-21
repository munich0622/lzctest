<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<title></title>
<link rel="stylesheet" href="<?php echo base_url('public/css/PVLook.css');?>">
<link rel="stylesheet" href="<?php echo base_url('public/css/xiao_mian.css');?>">
<link rel="stylesheet" href="<?php echo base_url('public/css/pv_reset.css');?>">
<link rel="stylesheet" href="<?php echo base_url('public/css/login.css');?>">
    <style>
        label {
            font-weight: 400;
            font-size: 14px;
            margin-right: 20px;
            display: inline-block;
        }

        input, textarea {
            width: auto;
            margin: 0px;
        }

        .middle {
            height: 556px;
            width: 1090px;
        }

        .main {
            background-color: #F18D00;
            background-image: none;
            height: 555px;
        }

        .login {
            background: white;
            border-radius: 8px;
            padding-left: 25px;
            width: 399px;
            height: 500px;
            float: right;
        }

        .from-user-login {
            display: none;
        }

        .radio-warpper {
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .aleart-phone {
            width: 325px;
            margin-bottom: 30px;
            text-indent: 20px;
        }

        .exist-code {
            height: 26px;
            border-radius: 8px;
            border: 1px dotted #819091;
            text-indent: 20px;
            margin-right: 10px;
        }

        .logo-icon-type {
            margin-top: 8px;
        }

        .pv-right-now {
            display: block;
            width: 294px;
            height: 44px;
            line-height: 44px;
            background-color: #F39800;
            color: white;
            font-weight: 600;
            font-size: 18px;
            text-align: center;
            border-radius: 5px;
        }

        .foget-pwd {
            float: right;
            margin-right: 20px;
        }

        .icon-weixin {
            display: block;
            width: 40px;
            height: 40px;
            text-indent: -9999px;
            background: url(/public/images/weixin.png) no-repeat;
            float: right;
            margin-right: 30px;
        }


            .icon-weixin:hover {
                background: url(/public/images/cweixin.png) no-repeat;
            }

        .form-user-login {
            display: none;
        }

        .common-login .from-user-login {
            display: block;
        }

        .common-login .pvnumber {
            display: block;
        }

        .common-login .pvnumber {
            display: none;
        }

        .tip {
            padding-top: 20px;
        }

        .tab-login-type {
            cursor: pointer;
        }
        tr{
			height:60px;
        }
    </style>
	<script type="text/javascript" src="<?php   echo base_url('public/js/jquery-1.11.2.min.js');?>"></script>
</head>
<body>
<!-- 编辑页通用顶部(更改绑定等) -->
<header class="edit-header">
	<div class="comeW" style="height:100px;">
		<h1 class="edit-header-left">
			<a href="" class="home-link" title="回到首页" style="background:none;"><img src="/public/images/logo.png" style="margin-top:20px;"></a>
		</h1>
		<div class="edit-header-right not-login">  <!-- not-login has-login 切换-->
			<div class="has-login-link">
			    <span>您好！</span><a href=""></a><span class="split-line">|</span>
			    <a href="">安全退出</a><span class="split-line">|</span>
			</div>
			<div class="not-login-link">
			    <a href="/index/index">登录</a><span class="split-line">|</span>
			    <a href="/register/index">注册</a><span class="split-line">|</span>
			</div>
			<a href="">首页</a>
		</div>
	</div>
</header>
<div style="clear:both;"></div>
    <!-- 主体内容 -->
    <div class="main">
        <div class="middle">
            <div class="login-images-middle"></div>
            <div class="login">
                <div class="tip">登&nbsp;&nbsp;录</div>
                <div class="radio-warpper">
                    <label for="ring-up" class="radio-common-logo tab-login-type">
                        <input type="radio" name="login_type" id="ring-up" value="0" data-login="user-login-main common-login" checked="checked">
                        <span>普通登录</span>
                    </label>
                </div>
                <div class="user-login-mian common-login" id="user-login-mian">
                    <div class="pvnumber">
                        <ul>
                            <li class="myphone">
                                <input type="text" class="phone aleart-phone" name="txt_mobile" id="txt_mobile" placeholder="已注册过的手机号"></li>
                            <li class="exist-accout-code">
                                <input type="text" name="mobilecode" id="mobilecode" class="gainyanzheng exist-code" placeholder="输入动态验证码">
                                <input class="checkCode" type="button" id="time" value="免费获取验证码" disabled="disabled" style="background-color: rgb(217, 217, 217)">
                                <div class="tip_check" id="tip_check_code">
                                    <label class="register_title" id="tip_code" style="color: red;"></label>
                                </div>
                            </li>
                            <li class="mybutton">
                                <input type="button" value="马上登录" class="btnlogin" id="btnMobileLogin" style="cursor: pointer;">
                            </li>
                        </ul>
                    </div>
                    <div class="from-user-login">
                        <table>
                            <tbody><tr>
                                <td colspan="2" class="logo-item-info">

                                    <i class="iconfont logo-icon-type">
                                        <img src="/public/images/head.png"></i>
                                    <input type="text" id="userid" name="userid" class="phone" value="" placeholder="请输入手机号/用户账号">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <i class="iconfont logo-icon-type">
                                        <img src="/public/images/password.png"></i>
                                    <input type="password" id="pwd" name="pwd" class="pass" placeholder="请输入密码">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="chkRemember">
                                        <input name="chkRemember" id="chkRemember" value="1" type="checkbox"><span class="remember">记住登录名</span>
                                    </label>
                                </td>
                                <td class="forget">
                                    <a href="">忘记密码？</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 0;">
                                    <input type="button" value="马上登录" class="btnlogin" id="btnUserNameLogin" style="cursor: pointer;">
                                </td>
                            </tr>
                        </tbody></table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- 页脚组件 -->
    <!-- 底部悬浮窗 -->
<div class="bottom_con">
    <div class="bottom_box">
        <div class="bottom_left">
            <img src="/public/images/bottomlogo.png">
        </div>
        <div class="botoom_center">
            <h1 style="display:none;">一分钟注册，让您成为成冠國際互联网新股东！</h1>
        </div>
        <div class="botoom_right">
            <a href="" class="bottom-rightnow-register">立即注册</a>
        </div>
    </div>
</div>


<!--页脚版权 -->
<footer class="has-block-border home-copyright master-copyright" >
    <div class="home-copyright-cont comeW foot_div" id="footer-wrap">
        <div class="home-copyright-link">
            <a href="">关于我们</a><span class="split-line">|</span>
            <a href="">成冠國際展望</a><span class="split-line">|</span>
            <a href="">使用条款</a><span class="split-line">|</span>
            <a href="">免责声明</a><span class="split-line">|</span>
            <a href="">联系我们</a><span class="split-line">|</span>
            <a href="">帮助中心</a>
        </div>
        <div class="master-copyright-logo">
            <div class="copyright-logo-left copyright-logo-item" style="background: none; padding-top: 0px;">
                <img src="/public/images/logo.png" alt="全民成冠國際的logo！">
                <p>服务时间：9：00-18：00(正常工作日)</p>
                <p>客服电话：400-000-000</p>
            </div>
            <div class="copyright-logo-right copyright-logo-item">
                <img src="/public/images/qrcode.png" class="erweima" style="width:120px;height:120px;">
                <div class="tv-xiaocong">官方微信号</div>
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript">
$('#btnUserNameLogin').click(function(){
	var phone    = $('#userid').val();
	var password = $('#pwd').val();
	if($.trim(phone) == '' || $.trim(password) == ''){
		alert('用户名或者登录密码不能为空!');
		return false;
	}
	$.ajax({  
        url: "/index/login_sub", 
        data: {"phone": phone,"password": password},
        dataType: "json",
        type:"post",
        success: function (ret) { 
           if(ret.success == true){
        	   alert(ret.data);
               location.href = "/user/index";
           }else{
        	   alert(ret.data);
           }
        }
    })
});

    
</script>
</body>
</html>