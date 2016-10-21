<!DOCTYPE html>
<!-- saved from url=(0046)http://www.pvplus.com.cn/account/Register.aspx -->
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link rel="stylesheet" href="/public/css/PVLook.css">
<link rel="stylesheet" href="/public/css/xiao_mian2.css">
<link rel="stylesheet" href="/public/css/pv_reset.css">
<script type="text/javascript" src="/public/js/jquery-1.11.2.min.js"></script>
    <!--[if lt IE 9]>
        <script src="/public/js/ie/DOMAssistantCompressed-2.7.4.js"></script>
        <script src="/public/js/ie/ie-css3.js"></script>
        <script src="/public/js/ie/html5shiv.min.js"></script>
        <script src="/public/js/ie/respond.min.js"></script>  
    <![endif]-->

</head>
<body>
    <header class="register-header">
        <div class="register-header-mask"></div>
    </header>
    <main class="comeW">
        <div class="register-wrap">
            <div class="register-action-bar cl">
                <div class="fl register-logo">
                    <img src="/public/images/whitelogo.png" alt="">
                </div>
                <div class="fr register-other-link">
                    <a href="/index/index">登陆</a>
                    <span class="split-line">|</span>
                    <a href="/register/index">注册</a>
                    <span class="split-line">|</span>
                    <a href="/index/index">首页</a>
                </div>
            </div>  
            <div class="register-form">
                <h2>注册新账号</h2>
                <div class="form-wrap">
                    <div class="form-row" id="form-row-accout">
                        <div class="form-row-input has-icon">
                            <i class="form-input-icon icon-accout"></i>
                            <input type="text" placeholder="请输入手机号" class="input-long" name="phone" id="phone">
                        </div>
                        <div class="form-row-tip"><span class="form-tip-inner">请填写正确的手机号码</span></div>
                    </div>   
                    <div class="form-row cl" id="form-row-mobilecode">
                         <div class="form-row-input">
                            <div class="fl">
                                <input type="text" class="input-short" name="invitation_code" id="invitation_code">
                            </div>
                            <button class="get-verify-btn">填写邀请码</button>
                         </div>
                        <div class="form-row-tip"><span class="form-tip-inner">邀请码不正确</span></div>
                    </div> 
                    <div class="form-row" id="reg-password-row">
                        <div class="form-row-input has-icon">
                            <i class="form-input-icon icon-password"></i>
                            <input type="password" placeholder="请输入密码" name="pwd" class="input-long" id="pwd">
                        </div>
                        <div class="form-row-tip"><div class="form-tip-inner">密码长度必须在6到15位数之间!</div></div>
                    </div> 
                    <div class="form-row" id="reg-passwordReapeat-row">
                        <div class="form-row-input has-icon">
                            <i class="form-input-icon icon-password"></i>
                            <input type="password" placeholder="请确认密码" name="pwd2" class="input-long" id="pwd2">
                        </div>
                        <div class="form-row-tip"><div class="form-tip-inner">两次密码输入不一致!</div></div>
                    </div> 
                    <button type="button" id="submit-register" class="register-btn">同意协议并注册</button>
                    <div class="form-row-agree">
                        <input type="checkbox" name="register-agree" id="register-agree" checked="checked">
                        <label for="register-agree">
                            <i>我已经阅读并同意遵守</i>
                        </label>
                        <a href="javascript:void(0);" target="_blank">聚丰国际服务条款</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- 底部悬浮窗 -->
<div class="bottom_con">
    <div class="bottom_box">
        <div class="bottom_left">
            <img src="/public/images/bottomlogo.png">
        </div>
        <div class="botoom_center">
            <h1>一分钟注册，让您成为聚丰国际新股东！</h1>
        </div>
        <div class="botoom_right">
            <a href="javascript:void(0);" class="bottom-rightnow-register">立即注册</a>
        </div>
    </div>
</div>

<!--页脚版权 -->
<footer class="has-block-border home-copyright master-copyright">
    <div class="home-copyright-cont" id="footer-wrap">
        <div class="home-copyright-link">
            <a href="">关于我们</a><span class="split-line">|</span>
            <a href="">聚丰国际计划</a><span class="split-line">|</span>
            <a href="">使用条款</a><span class="split-line">|</span>
            <a href="">联系我们</a><span class="split-line">|</span>
            <a href="">帮助中心</a>
        </div>
        <div class="master-copyright-logo">
            <div class="copyright-logo-left copyright-logo-item" style="background: none; padding-top: 0px;">
                <img src="/public/images/logo.png" alt="聚丰国际的logo！">
                <p>服务时间：9：00-18：00(正常工作日)</p>
                <p>客服电话：400-0755-294</p>
            </div>
            <div class="copyright-logo-right copyright-logo-item">
                <img src="/public/images/qrcode.jpg" class="erweima">
                <div class="tv-xiaocong">官方微信号</div>
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript">
$(document).ready(function() { 
	$('#submit-register').click(function(){
		if($("#register-agree").is(':checked') == false){
			alert('请先阅读并同意改协议！');
			return false;
		}
		
		var phone = $('#phone').val();
		var pattern = /^(147|13[0-9]|15[0-9]|18[0-9]|17[0-9])\d{8}$/;
		if(phone == '' || !pattern.test(phone)){
			$('#phone').parent().parent().find('.form-row-tip').show();
			return false;
		}else{
			$('#phone').parent().parent().find('.form-row-tip').hide();
		}

		var invitation_code = $('#invitation_code').val();
		if(invitation_code == ''){
			$('#invitation_code').parent().parent().parent().find('.form-row-tip').show();
			return false;
		}else{
			$('#invitation_code').parent().parent().parent().find('.form-row-tip').hide();
		}

		var pwd = $('#pwd').val();
		if(pwd == '' || pwd.length < 6 || pwd.length > 15){
			$('#pwd').parent().parent().find('.form-row-tip').show();
			return false;
		}else{
			$('#pwd').parent().parent().find('.form-row-tip').hide();
		}

		var pwd2 = $('#pwd2').val();
		if(pwd2 == '' || pwd != pwd2){
			$('#pwd2').parent().parent().find('.form-row-tip').show();
			return false;
		}else{
			$('#pwd2').parent().parent().find('.form-row-tip').hide();
		}

		$.ajax({  
            url: "/register/register_sub", 
            data: {"phone": phone,"invitation_code": invitation_code,"pwd": pwd,"pwd2": pwd2},
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
        });
	});
	
})
</script>
</body>
</html>