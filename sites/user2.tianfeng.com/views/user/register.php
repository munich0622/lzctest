<!DOCTYPE html>
<!-- saved from url=(0046)http://www.pvplus.com.cn/account/Register.aspx -->
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>
	全民光伏 PV Plus 用户注册
</title>
<link rel="stylesheet" href="<?php echo base_url('public/css/PVLook.css');?>">
<link rel="stylesheet" href="<?php echo base_url('public/css/xiao_mian.css');?>">
<link rel="stylesheet" href="<?php echo base_url('public/css/pv_reset.css');?>">
<script type="text/javascript" src="<?php   echo base_url('public/js/jquery-1.11.2.min.js');?>"></script>
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
                    <a href="">登陆</a>
                    <span class="split-line">|</span>
                    <a href="">注册</a>
                    <span class="split-line">|</span>
                    <a href="">首页</a>
                </div>
            </div>  
            <div class="register-form">
                <h2>注册新账号</h2>
                <div class="form-wrap">
                    <div class="form-row" id="form-row-accout">
                        <div class="form-row-input has-icon">
                            <i class="form-input-icon icon-accout"></i>
                            <input type="text" placeholder="请输入手机号" class="input-long" name="accout" id="reg-accout">
                        </div>
                        <div class="form-row-tip">
                            <div class="form-tip-inner">
                                <span class="form-tip-errInfo form-tip-info">请填写正确的手机号码</span>
                                <span class="form-tip-warnInfo form-tip-info">手机号码已注册,你可直接<a href="http://www.pvplus.com.cn/account/login.aspx" class="exsited-login">登陆</a></span>
                            </div>
                        </div>
                    </div>   
                    <div class="form-row verifyCode-row" id="form-row-imgcode">
                        <div class="form-row-input ">
                            <input type="text" class="input-short" name="imgcode" id="reg-codeVerify">
                            <img src="/public/images/checkcode.aspx" alt="" class="reg-change" id="code-verify-img" title="点击可切换">
                            <a href="javascript:;" class="reg-change">换一张</a>
                         </div>
                         <div class="form-row-tip">
                            <div class="form-tip-inner">
                                <span class="form-tip-errInfo form-tip-info">图形验证码不正确</span>
                            </div>
                        </div>
                    </div> 
                    <div class="form-row cl" id="form-row-mobilecode">
                         <div class="form-row-input ">
                            <div class="fl">
                                <input type="text" class="input-short" name="mobilecode" id="mobileVerify">
                            </div>
                            <button class="get-verify-btn" id="reg-get-mobileVerify" disabled="disabled">
                                获取验证码
                            </button>
                         </div>
                        <div class="form-row-tip">
                            <div class="form-tip-inner">
                                <span class="form-tip-errInfo form-tip-info">短信验证码不正确</span>
                            </div>
                        </div>
                    </div> 
                    <div class="form-row" id="reg-password-row">
                        <div class="form-row-input has-icon">
                            <i class="form-input-icon icon-password"></i>
                            <input type="password" placeholder="请输入密码" name="pwd" class="input-long" id="reg-password">
                        </div>
                        <div class="form-row-tip">
                            <div class="form-tip-inner">
                                密码长度必须在6到15位数之间!
                            </div>
                        </div>
                    </div> 
                    <div class="form-row" id="reg-passwordReapeat-row">
                        <div class="form-row-input has-icon">
                            <i class="form-input-icon icon-password"></i>
                            <input type="password" placeholder="请确认密码" name="pwd2" class="input-long" id="reg-passwordRepeat">
                        </div>
                        <div class="form-row-tip">
                            <div class="form-tip-inner">
                                两次密码输入不一致!
                            </div>
                        </div>
                    </div> 

                    <button type="button" id="submit-register" class="register-btn">同意协议并注册</button>
                    <div class="form-row-agree">
                        <input type="checkbox" id="register-agree" checked="">
                        <label for="register-agree">
                            <i>我已经阅读并同意遵守</i>
                        </label>
                        <a href="" target="_blank">全民光伏服务条款</a>
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
            <h1>一分钟注册，让您成为光伏互联网新股东！</h1>
        </div>
        <div class="botoom_right">
            <a href="" class="bottom-rightnow-register">立即注册</a>
        </div>
    </div>
</div>


<!--页脚版权 -->
<footer class="has-block-border home-copyright master-copyright">
    <div class="home-copyright-cont" id="footer-wrap">


        <div class="home-copyright-link">
            <a href="">关于我们</a><span class="split-line">|</span>
            <a href="">全民光伏计划</a><span class="split-line">|</span>
            <a href="">使用条款</a><span class="split-line">|</span>
            <a href="">联系我们</a><span class="split-line">|</span>
            <a href="">帮助中心</a>
        </div>
        <div class="master-copyright-logo">
            <div class="copyright-logo-left copyright-logo-item" style="background: none; padding-top: 0px;">
                <img src="/public/images/logo.png" alt="全民光伏的logo！">
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
</body></html>