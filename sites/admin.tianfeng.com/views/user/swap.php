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
                                    <input type="button" class="check_phone" value="查询" >
                                </td>
                            </tr>
                        </tbody>
                   </table>
                </form>
                <table class="insert-tab" id="user_info" width="100%">
                    <tbody>
                        <tr>
                            <th>会员信息1：</th>
                            <td>用户名称:ss</td>
                            <th>会员信息2：</th>
                            <td>用户名称:ss</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>手机号码:ss</td>
                            <td>&nbsp;</td>
                            <td>手机号码:ss</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>微信名称:ss</td>
                            <td>&nbsp;</td>
                            <td>微信名称:ss</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>身份证:ss</td>
                            <td>&nbsp;</td>
                            <td>身份证:ss</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>所属银行:ss</td>
                            <td>&nbsp;</td>
                            <td>所属银行:ss</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>银行卡号:ss</td>
                            <td>&nbsp;</td>
                            <td>银行卡号:ss</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>用户等级:ss</td>
                            <td>&nbsp;</td>
                            <td>用户等级:ss</td>
                        </tr>
                   </tbody>
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
            url: "/user/delay_received", 
            data: {"id": son_order_id},
            dataType: "json",
            type:"post",
            success: function (ret) { 
               if(ret.s == '1'){
                   alert(ret.m);
                   location.reload();
               }else{
                   alert(ret.m);
                   //延长收货失败
               }
            }
        });
	});
});
</script>
</body>
</html>