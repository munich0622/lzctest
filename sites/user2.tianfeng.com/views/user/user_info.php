<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>个人资料</title>
    <link href="/public/css/UserCSS.css" rel="stylesheet" type="text/css" />
    <script src="/public/js/ops.js" type="text/javascript"></script>
    <script src="/public/js/clipboard/clipboard.min.js" type="text/javascript"></script>
    <!--[if IE]>
		<script src="/public/js/ie/html5shiv.min.js"></script>
	<![endif]-->
    <script type="text/javascript">
        //下面用于图片上传预览功能
        function setImagePreview(avalue) {
        var docObj=document.getElementById("doc");
         
        var imgObjPreview=document.getElementById("preview");
        if(docObj.files &&docObj.files[0])
        {
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '150px';
        imgObjPreview.style.height = '180px';
        //imgObjPreview.src = docObj.files[0].getAsDataURL();
         
        //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
        }
        else
        {
        //IE下，使用滤镜
        docObj.select();
        var imgSrc = document.selection.createRange().text;
        var localImagId = document.getElementById("localImag");
        //必须设置初始大小
        localImagId.style.width = "150px";
        localImagId.style.height = "180px";
        //图片异常的捕捉，防止用户修改后缀来伪造图片
        try{
        localImagId.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
        localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
        }
        catch(e)
        {
        alert("您上传的图片格式不正确，请重新选择!");
        return false;
        }
        imgObjPreview.style.display = 'none';
        document.selection.empty();
        }
        return true;
        }
    </script>
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
            <form action="/user/info_sub" id="user_from" method="post" enctype="multipart/form-data" >
            <div class="u-form-wrap">
                <div id="autobid_form" class="mt10 newbg noborderleft">
                    <div class="m-form-box mt10">
                        <div class="m-form-til"><b>我的个人资料</b></div>
                        <div class="i-item i-long-item">
                            <label class="i-til">用户名(手机号码)：</label>
                            <div class="i-right">
                                <div class="i-txt"><i><?php echo $user['phone'];?></i></div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">真实姓名：</label>
                            <div class="i-right">
                                <div class="i-txt"><input class="i-inp" type="text" name="uname" id="uname" value="<?php echo $user['uname'];?>" style="height:20px;" /></div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">头像：</label>
                            <div class="i-right">
                                <div id="localImag"><img id="preview" src="<?php echo $user['head_img_url'];?>" width="150" height="180" style="display: block; width: 150px; height: 180px;"></div>
								<div style="margin-top:8px;"><input type="file" name="head_img_url" id="doc" style="width:150px;" onchange="javascript:setImagePreview();"></div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">身份证号：</label>
                            <div class="i-right">
                                <div class="i-txt"><input class="i-inp" type="text" name="id_card" id="id_card" value="<?php echo $user['id_card'];?>" style="height:20px;" /></div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">银行卡账号：</label>
                            <div class="i-right">
                                <div class="i-txt"><input class="i-inp" type="text" name="bank_num" id="bank_num" value="<?php echo $user['bank_num'];?>" style="height:20px;" /></div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">所属银行：</label>
                            <div class="i-right">
                                <div class="i-txt"><input class="i-inp" type="text" name="bank" id="bank" value="<?php echo $user['bank'];?>" style="height:20px;" /> (填写时包括支行名称,方便转账)</div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">当日奖励：</label>
                            <div class="i-right">
                                <div class="i-txt"><i class="red"><?php echo $user['day_award'];?></i> 元</div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">日分红奖励累计：</label>
                            <div class="i-right">
                                <div class="i-txt"><i class="red"><?php echo $user['today_reward'];?></i> 元</div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">团队奖励累计：</label>
                            <div class="i-right">
                                <div class="i-txt"><i class="red"><?php echo $user['team_reward'];?></i> 元</div>
                            </div>
                        </div>
                        <div class="i-item i-long-item">
                            <label class="i-til">账户余额：</label>
                            <div class="i-right">
                                <div class="i-txt"><i class="red"><?php echo $user['money'];?></i> 元</div>
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
		if($.trim($('#uname').val()) == ''){
			alert('请填写真实姓名!');
			return false;
		}

		if($.trim($('#preview').attr('src')) == ''){
			alert('请上传头像!');
			return false;
		}

		if($.trim($('#id_card').val()) == ''){
			alert('请填写身份证号码!');
			return false;
		}

		if($.trim($('#bank_num').val()) == ''){
			alert('请填写银行卡账号!');
			return false;
		}

		if($.trim($('#bank').val()) == ''){
			alert('请填写银行名称!');
			return false;
		}

		$('#user_from').submit();
	});
});
</script>