<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>支付列表</title>
    <link rel="stylesheet" href="<?php   echo base_url('public/css/common.css');?>">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/main.css');?>">
    <script type="text/javascript" src="<?php   echo base_url('public/js/modernizr.min.js');?>"></script>
    <script type="text/javascript" src="<?php   echo base_url('public/js/jquery-1.8.3.min.js');?>"></script>
    
</head>
<body>
<div class="topbar-wrap white">
    <!--<div class="topbar-inner clearfix">-->
        <div class="topbar-logo-wrap clearfix">
            <h1 class="topbar-logo none"><a href="index.html" class="navbar-brand">管理员中心</a></h1>
        </div>
    </div>
</div>
<div class="container clearfix">
    <?php $this->load->view('left');?>
    <!--/sidebar-->
    <div class="main-wrap">
        <?php $this->load->view('menu');?>
        <div class="search-wrap">
            <div class="search-content">
                <form action="#" method="post">
                    <table class="search-tab">
                        <tr>
                            <th width="120">选择类型:</th>
                            <td>
                                <select name="type">
                                    <option value="0">全部</option>
                                    <option value="1">注册</option>
                                    <option value="2">升级</option>
                                </select>
                            </td>
                            <th width="120">支付状态:</th>
                            <td>
                                <select name="status">
                                    <option value="0">全部</option>
                                    <option value="2">未支付</option>
                                    <option value="1">已支付</option>pay_list
                                </select>
                            </td>
                            <th width="120">是否打款给客户:</th>
                            <td>
                                <select name="is_dakuan">
                                    <option value="0">全部</option>
                                    <option value="2">未打款给客户</option>
                                    <option value="1">已打款给客户</option>
                                </select>
                            </td>
<!--                             <th width="70">关键字:</th> -->
<!--                             <td><input class="common-text" placeholder="关键字" name="keywords" value="" id="" type="text"></td> -->
<!--                             <td><input class="btn btn-primary btn2" name="sub" value="查询" type="submit"></td> -->
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="result-wrap">
            <form name="myform" id="myform" method="post">
                <div class="result-title">
                    <div class="result-list">
                        <a id="batchDel" href="javascript:void(0)"><i class="icon-font"></i>批量删除</a>
                        <a id="updateOrd" href="javascript:void(0)"><i class="icon-font"></i>更新排序</a>
                    </div>
                </div>
                <div class="result-content">
                    <table class="result-tab" width="100%">
                        <tr>
                            <th>支付id</th>
                            <th>支付人姓名</th>
                            <th>支付人电话</th>
                            <th>支付类型</th>
                            <th>支付时间</th>
                            <th>支付状态</th>
                            <th>支付金额</th>
                            <th>接收人姓名</th>
                            <th>接收人电话</th>
                            <th>接收人卡号(银行名称)</th>
                            <th>打款给接受人的金额</th>
                            <th>系统服务费</th>
                            <th>打款时间</th>
                            <th>操作</th>
                        </tr>
                    <?php if(count($list) > 0):?>
                    <?php foreach($list as $key=>$val):?>
                        <tr>
                        	<td><?php echo $val['id'];?></td>
                        	<td><?php echo $user_arr_info[$val['pay_uid']]['uname'];?></td>
                        	<td><?php echo $user_arr_info[$val['pay_uid']]['phone'];?></td>
                        	<td><?php echo $val['type'] == 1 ? '注册' : '升级'.'('.$val['content'].')';?></td>
                        	<td><?php echo date("Y-m-d H:i:s",$val['time']);?></td>
                        	<td><?php echo $val['status'] == 1 ? '已支付' : '未支付';?></td>
                        	<td><?php echo $val['price'];?></td>
                        	<td><?php echo isset($user_arr_info[$val['receive_uid']]) ? $user_arr_info[$val['receive_uid']]['uname'] : '系统';?></td>
                        	<td><?php echo isset($user_arr_info[$val['receive_uid']]) ? $user_arr_info[$val['receive_uid']]['phone'] : '系统';?></td>
                        	<td><?php echo isset($user_arr_info[$val['receive_uid']]) ? $user_arr_info[$val['receive_uid']]['bank_num'].'('.$user_arr_info[$val['receive_uid']]['bank_name'].')' : '系统';?></td>
                        	<td><?php echo $val['dk_money'];?></td>
                        	<td><?php echo $val['service_money'];?></td>
                        	<td><?php if($val['dakuan_id'] > 0){echo date("Y-m-d H:i:s",$val['dk_time']);}else{ echo '未打款';}?></td>
                        	<td><?php if(isset($user_arr_info[$val['receive_uid']])):?><a href="javascript:void(0)" class="dakuan" dk_id = "<?php echo $val['id'];?>">打款</a><?php endif;?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif;?>
                    </table>
                    <div class="list-page"><?php echo $page_html?></div>
                </div>
            </form>
        </div>
    </div>
    <!--/main-->
</div>
<script type="text/javascript">
$(function(){

	$(".dakuan").click(function(){
		var money = prompt("请输入给对方的钱:","");
		money = parseInt(money);
		if(money > 0){
			var pay_id = $(this).attr("dk_id");
			$.ajax({  
	            url: "/pay/dakuan", 
	            data: {"money": money,"pay_id":pay_id},
	            dataType: "json",
	            type:"post",
	            success: function (ret) { 
	               alert(ret.data);
	               if(ret.success == true){
	                   location.reload();
	               }
	            }
	        });
		}
	});
})

</script>
</body>
</html>