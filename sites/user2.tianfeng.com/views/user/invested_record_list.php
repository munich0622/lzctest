<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>个人主页</title>
    <link href="/public/css/UserCSS.css" rel="stylesheet" type="text/css" />
    <script src="/public/js/ops.js" type="text/javascript"></script>
    <script src="/public/js/clipboard/clipboard.min.js" type="text/javascript"></script>
    
    <!--[if IE]>
		<script src="/public/js/ie/html5shiv.min.js"></script>
	<![endif]-->
</head>
<body>
	<?php $this->load->view('header');?>
    <div class="row" style="margin-top: 10px;">
    </div>
    <div class="row">
        <?php $this->load->view('left');?>
        <div class="u-main">
            <div class="ucenter">
            <div class="ucenter-info mt10" style="border:0px;">
                <div class="info-title">
                    <h5>我的复投记录</h5>
                </div>
                <?php if(!empty($list)):?>
                <div class="glzx_sjvip2">
					<table cellpadding="0" cellspacing="0" border="0" class="hybiaoge">
  					<tbody>
  						<tr class="tr_bs">
                            <td>编号</td>
                            <td>复投金额</td>
                            <td>每天收益</td>
                            <td>复投时间</td>
                            <td>开始收益时间</td>
                            <td>结束时间</td>
                            <td>上一次获得收益的时间</td>
                            <td>复投状态</td>
                        </tr>
                        <?php foreach($list as $key=>$val):?>
            		    <tr class="tr_bs">
                          	<td><?php echo $val['index'];?></td>
                            <td><?php echo $val['money'];?></td>
                            <td><?php echo $val['profit'];?></td>
                            <td><?php echo date("Y-m-d H:i:s",$val['start_time']);?></td>
                            <td><?php echo date("Y-m-d",$val['start_time']+86400);?></td>
                            <td><?php echo date("Y-m-d",$val['end_time']);?></td>
                            <td><?php echo $val['last_profit_time'] == 0 ? '暂时没获得收益' : date("Y-m-d H:i:s",$val['last_profit_time']);?></td>
                            <td>复投状态</td>
                    	</tr>
                    	<?php endforeach;?>
            		</tbody>
            		</table>
                    <div class="clear"></div>
                    <?php echo $page_html;?>
       			</div>
       			<?php else:?>
       			<?php echo "暂无投资记录";?>
       			<?php endif;?>
       			<div class="tgljs" style="text-align:center;margin-top:50px;"><a href="javascript:;">点击投资点股</a></div>
           </div> 
        </div>
    </div>
    </div>
    <script type="text/javascript">
    $('.tgljs').click(function(){
		if(confirm("是否要复投")){
			$.ajax({  
                url: "/index.php/user/touzi_action", 
                dataType: "json",
                type:"post",
                success: function (ret) { 
                   if(ret.s == '1'){
                       location.reload();
                   }else{
                       //取消订单失败
                   }
                }
            })
		}
    })
    </script>
</body>
</html>
