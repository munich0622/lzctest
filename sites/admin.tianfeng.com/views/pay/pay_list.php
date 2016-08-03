<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>支付列表</title>
    <link rel="stylesheet" href="<?php   echo base_url('public/css/common.css');?>">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/main.css');?>">
    <script type="text/javascript" src="<?php   echo base_url('public/js/modernizr.min.js');?>"></script>
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
                            <th>支付描述</th>
                            <th>支付时间</th>
                            <th>支付状态</th>
                            <th>支付金额</th>
                            <th>接收人姓名</th>
                            <th>接收人电话</th>
                            <th>接收人卡号(银行名称)</th>
                            <th>打款给接受人的金额</th>
                            <th>系统服务费</th>
                            <th>打款时间</th>
                        </tr>
                    <?php if(count($list) > 0):?>
                    <?php foreach($list as $key=>$val):?>
                        
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
</body>
</html>