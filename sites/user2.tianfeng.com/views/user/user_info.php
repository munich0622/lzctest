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
                <div class="ucenter-info mt10">
                    <div class="info-title">
                        <h5>我的个人主页</h5>
                    </div>
                    <div class="info">
                        <ul class="info-img"><li><img src="<?php echo $user['head_img_url'];?>" class="avatar" /></li></ul>
                        <div class="info-main">
                            <div class="row"><label>用户名：</label><?php echo $user['uname'];?></div>
                            <div class="row"><label>注册时间：</label><?php echo date('Y-m-d H:i:s',$user['reg_time']);?></div>
                            <div class="row"><label>点股数量：</label>(总/出局)：4/2</div>
                            <div class="row"><label>推荐人：</label><span class="orange">
                            <?php 
                            if(preg_match("/^1[34578]{1}\d{9}$/",$user['tj_info']['uname']))
                            {
                                echo substr_replace($user['tj_info']['uname'], '****', 3,6);
                            
                            }else{
                                echo $user['tj_info']['uname'];
                            }
                            ?></span></div>
                            <div class="row"><label>日分红：</label><span class="red"><?php echo $user['today_reward'];?></span></div>
                            <div class="row"><label>团队奖励：</label><span class="red"><?php echo $user['team_reward'];?></span></div>
                            <div class="tgljs"><label>推广链接：<input type="text" id="foo" class="form-control" value="<?php echo $user['link'];?>"> 
                            <a id="d_clip_button"  data-clipboard-action="copy" data-clipboard-target="#foo" href="javascript:;">复制链接</a><br /><span>您可将该链接发给您的朋友，推荐他注册成为我们的会员，效果同上</span></div>
                        </div>
                        <div class="clear">
                        </div>
                    </div>
                </div> 
                <div class="ucenter-info mt10">
                <div class="ucenter-tab-box">
                        <ul class="u-tab clearfix">
                            <li class="current"><a>我的账户</a></li>
                            <li><a>关注我的用户</a></li>
                            <li><a>复投记录</a></li>
                            <li><a>收益记录</a></li>
                        </ul>
                </div>
                <div id="tab_box">
                    <div class="u-form-wrap">
                        <dl class="huiyuantwo">
                        	
                            <dd>
                            	<p>
                                	账户余额（元）<br>
                                    <span><a href="javascript:void(0);"><?php echo $user['money'];?></a></span><br>
                                    <a href="javascript:void(0);">点击充值</a>
                                </p>
                               
                                <p>
                                	收入总额（元）<br>
                                    <span><a href="javascript:void(0);"><?php echo $user['today_reward']+$user['team_reward'];?></a></span><br>
                                    <a href="javascript:void(0);">查看资金流水</a>
                                </p>
                                
                                <p>
                                	直属推荐会员（人）<br>
                                    <span><a href="/user/tj_list"><?php echo $user['tj_count'];?></a></span><br>
                                    <a href="/user/tj_list">查看直属推荐会员</a>
                                </p>
                                
                            </dd>
                        </dl>
                    </div>
                    <div class="u-form-wrap" style="display: none;">
                        <div>这是关注我的用户</div>
                    </div>
                    <div class="u-form-wrap" style="display: none;">
                    	<div>这是我的复投记录</div>
                    </div>
                    <div class="u-form-wrap" style="display: none;">
                        <div>这是我的收益记录</div>
                    </div>
                </div>                
            </div>
            <script type="text/javascript">

                var $div_li = $(".ucenter-tab-box ul li");

                $div_li.click(function () {

                    $(this).addClass("current").siblings().removeClass("current");

                    var div_index = $div_li.index(this);

                    $("#tab_box>div").eq(div_index).show().siblings().hide();

                }).hover(function () {

                    $(this).addClass("hover");

                }, function () {

                    $(this).removeClass("hover");

                });

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
