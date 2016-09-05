<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>一级用户列表</title>
    <link rel="stylesheet" href="<?php   echo base_url('public/css/common.css');?>">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/main.css');?>">
    <script type="text/javascript" src="<?php   echo base_url('public/js/modernizr.min.js');?>"></script>
    <script type="text/javascript" src="<?php   echo base_url('public/js/jquery-1.8.3.min.js');?>"></script>
    
</head>
<body>
<div class="topbar-wrap white">
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

            </div>
        </div>
        <div class="result-wrap">
            <form name="myform" id="myform" method="post">
                <div class="result-content">
                    <table class="result-tab" width="100%">
                        <tr>
                            <th>会员点位</th>
                            <th>会员姓名</th>
                            <th>会员电话</th>
                            <th>会员等级</th>
                            <th>会员所属银行</th>
                            <th>会员银行卡号</th>
                            <th>会员身份证号</th>
                        </tr>
                    <?php if(count($list) > 0):?>
                    <?php foreach($list as $key=>$val):?>
                        <tr>
                        	<td><?php echo $val['uid'];?></td>
                        	<td><?php echo $val['uname'];?></td>
                        	<td><?php echo $val['phone'];?></td>
                        	<td><?php echo $val['level'];?></td>
                        	<td><?php echo $bank_list[$val['bank']]?></td>
                        	<td><?php echo $val['bank_num'];?></td>
                        	<td><?php echo $val['id_card'];?></td>
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

</script>
</body>
</html>