<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>上传csv</title>
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
                <form action="/xiufu/xiufu_pay_sub" method="post"  name="myform" enctype="multipart/form-data">
                    <table class="insert-tab" width="100%">
                        <tbody>
                            <tr>
                                <th><i class="require-red">*</i>请输入要修复的订单号：</th>
                                <td >
                                    <input class="common-text required" name="order_sn" size="50" value="" type="text">
                                    <input type="submit" value="确定修复" >
                                </td>
                            </tr>
                        </tbody></table>
                </form>
            </div>
        </div>

    </div>
    
</div>
</body>
</html>