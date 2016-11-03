<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>设置个人密码</title>
    <link href="/public/css/UserCSS.css" rel="stylesheet" type="text/css" />
    <script src="/public/js/ops.js" type="text/javascript"></script>
    <script src="/public/js/clipboard/clipboard.min.js" type="text/javascript"></script>
    <!--[if IE]>
		<script src="/public/js/ie/html5shiv.min.js"></script>
	<![endif]-->
</head>
<body>
    <?php $this->load->view('header');?>
    <div class="row" style="margin-top: 10px;"></div>
    <div class="row">
        <?php $this->load->view('left');?>
        <div class="u-main">
            <div class="u-tab-wrap">
                <ul class="u-tab clearfix">
                    <li val="user_autobid_box" class="current"><a>个人资料设置</a></li></ul>
            </div>
            <form action="/user/set_pass_sub" id="user_from" method="post">
            <div class="u-form-wrap">
                <div id="autobid_form" class="mt10 newbg noborderleft">
                    <div class="m-form-box mt10">
                        <div class="m-form-til"><b>设置个人密码</b></div>
                        <div class="i-item i-long-item">
                            <label class="i-til">旧密码：</label>
                            <div class="i-right">
                                <div class="i-txt"><input class="i-inp" type="text" name="old_pass" id="old_pass" value="" style="height:20px;" /></div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">新密码：</label>
                            <div class="i-right">
                                <div class="i-txt"><input class="i-inp" type="text" name="new_pass" id="new_pass" value="" style="height:20px;" /></div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">重复新密码：</label>
                            <div class="i-right">
                                <div class="i-txt"><input class="i-inp" type="text" name="re_new_pass" id="re_new_pass" value="" style="height:20px;" /> </div>
                            </div>
                        </div>
                        <div class="i-item i-long-item hasborderbot" style="text-align: center;"></div>
                        <div class="i-item-btn i-item-btn2" style="text-align: center; height: 50px; line-height: 50px;">
                            <button type="button" id="do_save" class="i-btn-txt1"> 保 存</button>
                            &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="i-btn-txt2">关 闭</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
$(function(){
	$('#do_save').click(function(){
		if($.trim($('#old_pass').val()) == '' || $('#old_pass').val().length < 6 ){
			alert('请输入旧密码!');
			return false;
		}

		if($.trim($('#new_pass').val()) == '' || $('#new_pass').val().length < 6){
			alert('请输入新密码!');
			return false;
		}

		if($.trim($('#re_new_pass').val()) == ''){
			alert('确认新密码不能为空!');
			return false;
		}

		if($.trim($('#new_pass').val()) != $.trim($('#re_new_pass').val())){
			alert('两次输入的新密码不一致!');
			return false;
		}

		$('#user_from').submit();
	});
});
</script>