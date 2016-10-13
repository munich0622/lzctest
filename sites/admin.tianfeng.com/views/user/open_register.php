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
                                <th style="width:30%"><i class="require-red"></i>是否对外开放注册：</th>
                                <td>
                                <input type="radio" name="is_open"  value="1" <?php if($is_open_register == 1):?>checked="true"<?php endif;?>> 是 : 
								<input type="radio" name="is_open" value="0" <?php if($is_open_register == 0):?>checked="true"<?php endif;?>?> 否 :
                                </td>
                            </tr>
                        </tbody>
                   </table>
                </form>
                <table class="insert-tab" width="100%" >
                <tr><td style="text-align: center"><input type="button" value="确定" class="swap_sub"></td></tr>
                </table>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
	$('.swap_sub').click(function(){
		var is_open = $('input[name="is_open"]:checked').val();
		$.ajax({  
            url: "/user/open_register_sub", 
            data: {"is_open":is_open},
            dataType: "json",
            type:"post",
            success: function (ret) { 
            	alert(ret.code);
            }
        });
	});
});
</script>
</body>
</html>