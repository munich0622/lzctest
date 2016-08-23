<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>点位对换</title>
    <link rel="stylesheet" href="<?php   echo base_url('public/css/common.css');?>">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/main.css');?>">
    <script type="text/javascript" src="<?php   echo base_url('public/js/modernizr.min.js');?>"></script>
    <script type="text/javascript" src="<?php   echo base_url('public/js/jquery-1.8.3.min.js');?>"></script>
</head>
<body>
<div class="topbar-wrap white">
    <div class="topbar-inner clearfix">
    </div>
</div>
<div class="container clearfix">
    <?php $this->load->view('left');?>
    <!--/sidebar-->
    <div class="main-wrap">
        <?php $this->load->view('menu');?>
        <div class="result-wrap">
            <div class="result-content">
                <form action="/user/swap_sub" method="post"  name="myform" >
                    <table class="insert-tab" width="100%">
                        <tbody>
                            <tr>
                                <th><i class="require-red">*</i>请输入要对换的会员手机号码1：</th>
                                <td>
                                    <input class="common-text required" name="phone1" size="50" value="" type="text">
                                </td>
                            </tr>
                            <tr>
                                <th><i class="require-red">*</i>请输入要对换的会员手机号码2：</th>
                                <td>
                                    <input class="common-text required" name="phone2" size="50" value="" type="text">
                                </td>
                            </tr>
                            <tr>
                                <th>&nbsp</th>
                                <td>
                                    <input type="button" class="check_phone" value="查询" is_click="no">
                                </td>
                            </tr>
                        </tbody>
                   </table>
                </form>
                <table class="insert-tab" id="user_info" width="100%">
                    
                </table align=center>
                <table class="insert-tab" width="100%" >
                <tr><td style="text-align: center"><input type="button" value="确定交换" class="swap_sub"></td></tr>
                </table>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
	$('.check_phone').click(function(){
		var phone1 = $("input[name='phone1']").val();
		var phone2 = $("input[name='phone2']").val();
		if(phone1 == '' || phone2 == ''){
			alert('请输入要对换的两个手机号码!');
			return false;
		}

		$.ajax({  
            url: "/user/check_user_info", 
            data: {"phone1": phone1,"phone2": phone2},
            dataType: "json",
            type:"post",
            success: function (ret) { 
               if(ret.success == true){
                   $('#user_info').html(ret.code);
                   $('.check_phone').attr('is_click','yes');
                   return false;
               }else{
                   alert(ret.code);
                   return false;
                   //延长收货失败
               }
            }
        });
	});

	$('.swap_sub').click(function(){
		if($('.check_phone').attr('is_click') == 'no'){
			alert('请先点击查询查看用户信息确认无误后再交换点位!');
			return false;
		}
		if(confirm('点位交换之后密码被重置为123456,二维码需要重新生成，是否确定交换？')){
			var phone1 = $("input[name='phone1']").val();
			var phone2 = $("input[name='phone2']").val();
			if(phone1 == '' || phone2 == ''){
				alert('请输入要对换的两个手机号码!');
				return false;
			}
			$.ajax({  
	            url: "/user/swap_sub", 
	            data: {"phone1": phone1,"phone2": phone2},
	            dataType: "json",
	            type:"post",
	            success: function (ret) { 
	            	alert(ret.code);
	            	if(ret.success == true){
	            		$('.check_phone').attr('is_click','no');
		           	}
	            }
	        });
		}
	});
});
</script>
</body>
</html>