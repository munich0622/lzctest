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
                    <h5>我的直推会员</h5>
                </div>
                <?php if(!empty($list)):?>
                <div class="glzx_sjvip2">
					<table cellpadding="0" cellspacing="0" border="0" class="hybiaoge">
  					<tbody>
  						<tr class="tr_bs">
                            <td>头像</td>
                            <td>昵称</td>
                            <td width="250">注册时间</td>
                        </tr>
                        <?php foreach($list as $key=>$val):?>
            		    <tr class="tr_bs">
                          <td><img src="<?php echo $val['head_img_url']?>" height="50" width="50" style="margin:10px;"></td>
                          <td><?php echo $val['uname'];?></td>
            			  <td><?php echo date("Y-m-d H:i:s",$val['reg_time']);?></td>
                    	</tr>
                    	<?php endforeach;?>
                    	<?php echo $page_html;?>
            		</tbody>
            		</table>
                    <div class="clear"></div>
                    <?php echo $page_html;?>
       			</div>
       			<?php else:?>
       			<?php echo "暂无推荐会员";?>
       			<?php endif;?>
       			<div class="tgljs"><label>推广链接：<input type="text" id="foo" class="form-control" value="<?php echo $user['link'];?>"> 
                <a id="d_clip_button"  data-clipboard-action="copy" data-clipboard-target="#foo" href="javascript:;">复制链接</a><br /><span>您可将该链接发给您的朋友，推荐他注册成为我们的会员，效果同上</span></div>
           </div> 
           <script type="text/javascript">
                //点击赋值到剪切板
                var clipboard = new Clipboard('#d_clip_button');
                clipboard.on('success', function(e) {
                	alert("文字已复制到剪贴板中");
                    //console.log(e);
                });
        	</script>
        </div>
    </div>
    </div>
</body>
</html>
